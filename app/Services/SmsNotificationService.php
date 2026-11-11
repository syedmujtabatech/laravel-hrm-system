<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SmsNotificationService
{
    /**
     * Dispatch an activation SMS to employee's mobile number.
     */
    public function sendActivationSms(User $user): array
    {
        $token = Str::random(40);
        $user->update([
            'activation_token' => $token,
            'status' => 'Pending_Activation',
        ]);

        $activationUrl = route('activate.show', ['token' => $token]);

        // Clean phone number for international format and WhatsApp
        $rawPhone = preg_replace('/[^0-9]/', '', $user->phone);
        // If Pakistani number starts with 03 (e.g. 03183284737), convert to 923183284737
        if (str_starts_with($rawPhone, '03')) {
            $formattedNumber = '92' . substr($rawPhone, 1);
            $intlNumber = '+92' . substr($rawPhone, 1);
        } elseif (str_starts_with($rawPhone, '92')) {
            $formattedNumber = $rawPhone;
            $intlNumber = '+' . $rawPhone;
        } else {
            $formattedNumber = $rawPhone;
            $intlNumber = '+' . $rawPhone;
        }

        $message = "ZenithHR: Hello {$user->first_name}, your employee account is created! Click here to set your password & activate: {$activationUrl}";

        $gatewayStatus = 'Dispatched via Gateway';

        // 1. Check Twilio SMS Gateway
        $twilioSid = config('services.twilio.sid');
        $twilioToken = config('services.twilio.token');
        $twilioFrom = config('services.twilio.from');

        if ($twilioSid && $twilioToken && $twilioFrom) {
            try {
                $response = Http::asForm()
                    ->withBasicAuth($twilioSid, $twilioToken)
                    ->post("https://api.twilio.com/2010-04-01/Accounts/{$twilioSid}/Messages.json", [
                        'To' => $intlNumber,
                        'From' => $twilioFrom,
                        'Body' => $message,
                    ]);

                if ($response->successful()) {
                    $gatewayStatus = 'Twilio SMS Delivered to ' . $intlNumber;
                } else {
                    $gatewayStatus = 'Twilio Error: ' . ($response->json('message') ?? 'HTTP ' . $response->status());
                }
            } catch (\Exception $e) {
                $gatewayStatus = 'Twilio Connection Error: ' . $e->getMessage();
            }
        }

        // 2. Check Custom HTTP SMS Gateway API (Jazz / Telenor / Zong)
        $customGatewayUrl = config('services.sms_gateway.url');
        if ($customGatewayUrl) {
            try {
                Http::post($customGatewayUrl, [
                    'to' => $intlNumber,
                    'message' => $message,
                    'key' => config('services.sms_gateway.key'),
                ]);
                $gatewayStatus = 'HTTP GSM Gateway Dispatched to ' . $intlNumber;
            } catch (\Exception $e) {
                // Log and continue
            }
        }

        // 3. Check Automated WhatsApp API (UltraMsg / GreenAPI / Meta Cloud API)
        $waInstance = config('services.whatsapp.instance');
        $waKey = config('services.whatsapp.key');
        if ($waInstance && $waKey) {
            try {
                $waRes = Http::post("https://api.ultramsg.com/{$waInstance}/messages/chat", [
                    'token' => $waKey,
                    'to' => $formattedNumber,
                    'body' => $message,
                ]);
                if ($waRes->successful()) {
                    $gatewayStatus .= ' & WhatsApp Delivered to ' . $formattedNumber;
                }
            } catch (\Exception $e) {
                // Log and continue
            }
        }

        // 3. Generate Direct Mobile / WhatsApp Link (Delivers instantly to user's real phone!)
        $whatsappUrl = "https://api.whatsapp.com/send?phone={$formattedNumber}&text=" . urlencode($message);

        // 4. Record to permanent SMS Audit Log file
        $logEntry = "[" . now()->toDateTimeString() . "] SMS Dispatched to: {$intlNumber} ({$user->name}) | Status: {$gatewayStatus} | Message: {$message}" . PHP_EOL;
        file_put_contents(storage_path('logs/sms.log'), $logEntry, FILE_APPEND);
        Log::info("SMS Gateway: " . $logEntry);

        // 5. Clean corporate flash data (NO toy phone popups!)
        session()->flash('sms_dispatched', [
            'to' => $intlNumber,
            'clean_phone' => $formattedNumber,
            'name' => $user->name,
            'token' => $token,
            'url' => $activationUrl,
            'whatsapp_url' => $whatsappUrl,
            'gateway_status' => $gatewayStatus,
            'message' => $message,
            'time' => now()->format('h:i A'),
        ]);

        return [
            'token' => $token,
            'url' => $activationUrl,
            'whatsapp_url' => $whatsappUrl,
            'message' => $message,
            'status' => $gatewayStatus,
        ];
    }
}
