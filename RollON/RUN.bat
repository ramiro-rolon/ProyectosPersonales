@echo off
title Roll-ON - Servidor Local
color 0A

echo.
echo  ========================================
echo         ROLL-ON - Cotizador PHP
echo  ========================================
echo.

set PHP_PATH=

set "PATHS=C:\xampp\php\php.exe;C:\wamp64\bin\php\php8.2\php.exe;C:\wamp64\bin\php\php8.1\php.exe;C:\wamp64\bin\php\php8.0\php.exe;C:\wamp64\bin\php\php7.4\php.exe;C:\laragon\bin\php\php-8.2\php.exe;C:\laragon\bin\php\php-8.1\php.exe;C:\laragon\bin\php\php-8.0\php.exe;C:\php\php.exe"

for %%P in ("%PATHS:;=" "%") do (
    if exist %%~P (
        set PHP_PATH=%%~P
        goto :found
    )
)

echo [ERROR] PHP no encontrado.
echo.
echo Instala XAMPP, WAMP o Laragon, o agrega PHP al PATH del sistema.
echo.
echo Buscaste en:
echo   - C:\xampp\php\php.exe
echo   - C:\wamp64\bin\php\php8.*\php.exe
echo   - C:\laragon\bin\php\php-*\php.exe
echo   - C:\php\php.exe
echo.
pause
exit /b 1

:found
echo [OK] PHP encontrado: %PHP_PATH%

cd /d "%~dp0public"

echo.
echo [*] Abriendo navegador...
start http://localhost:8000

echo [*] Iniciando servidor en http://localhost:8000
echo [*] Presiona Ctrl+C para detener
echo.

"%PHP_PATH%" -S localhost:8000

pause
