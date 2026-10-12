@echo off
title SIREKOWIS v2.0 - Cloudflare Public Tunnel
cd /d "%~dp0"
echo ========================================================
echo   MEMBUKA AKSES PUBLIK SIREKOWIS (CLOUDFLARE TUNNEL)
echo ========================================================
echo.
echo Pastikan server Laravel sudah berjalan di port 8000!
echo Sedang membuat link publik HTTPS...
echo.
cloudflared.exe tunnel --url http://127.0.0.1:8000 --no-autoupdate
pause
