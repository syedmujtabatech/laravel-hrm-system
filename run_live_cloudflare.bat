@echo off
title ZenithHR - Port 8011
start /b "" "C:\xampp\php\php.exe" -S 127.0.0.1:8011 -t "D:\laravel-hrm-system\public"
"C:\Users\Mujtaba Arshad\.gemini\antigravity\cloudflared.exe" tunnel --url http://127.0.0.1:8011
