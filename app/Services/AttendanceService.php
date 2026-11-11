<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Biometric punch clock (Punch In / Punch Out toggle).
     */
    public function punchClock(User $user): Attendance
    {
        $today = Carbon::today()->toDateString();
        $record = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        $currentTime = Carbon::now()->format('H:i:s');

        if (!$record) {
            // Punch In
            $isLate = Carbon::now()->hour >= 10;
            return Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'check_in' => $currentTime,
                'status' => $isLate ? 'Late' : 'Present',
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'device' => request()->userAgent() ?? 'Web Terminal',
            ]);
        }

        // Punch Out
        if ($record->check_in && !$record->check_out) {
            $checkIn = Carbon::parse($record->check_in);
            $checkOut = Carbon::now();
            $hours = round($checkIn->diffInMinutes($checkOut) / 60, 2);

            $record->update([
                'check_out' => $currentTime,
                'work_hours' => $hours,
                'status' => $hours < 4 ? 'HalfDay' : $record->status,
            ]);
        }

        return $record;
    }

    public function getTodayRecord(int $userId): ?Attendance
    {
        return Attendance::where('user_id', $userId)
            ->where('date', Carbon::today()->toDateString())
            ->first();
    }
}
