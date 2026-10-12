@echo off
title SIREKOWIS v2.0 - Server Disporabudpar Kota Banjarbaru
cd /d "%~dp0"
set PATH=%PATH%;C:\xampp\php;C:\xampp\mysql\bin
echo =========================================================
echo    MENJALANKAN SERVER SIREKOWIS v2.0 KOTA BANJARBARU
echo =========================================================
echo.
echo URL Aplikasi: http://127.0.0.1:8000
echo Login Admin : admin@sirekowis.id / password
echo.
echo Tekan Ctrl+C untuk menghentikan server.
echo.
php artisan serve --host=127.0.0.1 --port=8000
pause
