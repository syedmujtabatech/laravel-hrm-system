@echo off
title ZenithHR Global Enterprise - Live Cloudflare Launcher
color 0E
echo Starting ZenithHR Local Server on Port 8004...
start "" /b php artisan serve --port=8004
echo Starting Cloudflare Tunnel for ZenithHR...
"C:\Users\Mujtaba Arshad\.gemini\antigravity\cloudflared.exe" tunnel --url http://127.0.0.1:8004
pause
