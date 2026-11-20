@echo off
title EazyTech Solution - dev server
rem Uses Laragon's PHP 8.3. Edit the path below if your Laragon lives elsewhere.
set "PATH=C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64;C:\laragon\bin\composer;%PATH%"
cd /d "%~dp0"
echo.
echo   Website : http://127.0.0.1:8000
echo   Admin   : http://127.0.0.1:8000/admin/login
echo.
echo   (Make sure Laragon's MySQL is running)
echo.
start "" http://127.0.0.1:8000
php artisan serve --host=127.0.0.1 --port=8000
