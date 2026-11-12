@echo off
cd /d "%~dp0"

if not exist "C:\xampp\php\php.exe" (
    echo PHP was not found at C:\xampp\php\php.exe
    echo Update the path in start-store.bat to match your XAMPP installation.
    pause
    exit /b 1
)

"C:\xampp\php\php.exe" artisan serve
echo.
echo The Laravel server has stopped.
pause
