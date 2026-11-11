@echo off
title ZenithHR Global Enterprise - Laravel HRM
echo ==========================================================
echo Starting ZenithHR Global Enterprise Laravel HRM System...
echo ==========================================================
echo Opening browser at http://127.0.0.1:8000 ...
timeout /t 2 /nobreak >nul
start http://127.0.0.1:8000
php artisan serve --port=8000
