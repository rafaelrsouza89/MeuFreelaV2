@echo off
title MeuFreela - Iniciando...
color 0A

echo.
echo  ============================================
echo     MeuFreela - Iniciando Sistema
echo  ============================================
echo.

set PROJECT=c:\Users\orafa\OneDrive\Documentos\GitHub\MeuFreelaV2\laravel
set XAMPP=C:\xampp

echo  [1/4] Verificando MySQL (XAMPP)...

:: Checa se o MySQL já está rodando na porta 3306
netstat -ano | findstr ":3306" | findstr "LISTENING" >nul 2>&1
if %errorlevel% == 0 (
    echo         MySQL ja esta rodando. OK
) else (
    echo         Iniciando MySQL...
    start "XAMPP MySQL" /min "%XAMPP%\mysql_start.bat"
    echo         Aguardando MySQL subir...
    timeout /t 5 /nobreak >nul

    :: Verifica novamente
    netstat -ano | findstr ":3306" | findstr "LISTENING" >nul 2>&1
    if %errorlevel% neq 0 (
        echo.
        echo  [AVISO] MySQL nao respondeu na porta 3306.
        echo  Verifique se o XAMPP esta instalado corretamente.
        echo  Continuando mesmo assim...
        echo.
        timeout /t 2 /nobreak >nul
    ) else (
        echo         MySQL iniciado com sucesso. OK
    )
)

echo  [2/4] Iniciando Backend (Laravel)...
start "MeuFreela - Backend" /min cmd /k "cd /d %PROJECT% && php artisan serve --host=127.0.0.1 --port=8000"

echo  [3/4] Iniciando Frontend (Vite)...
start "MeuFreela - Frontend" /min cmd /k "cd /d %PROJECT% && npm run dev"

echo  [4/4] Aguardando servidores subirem...
timeout /t 4 /nobreak >nul

echo  Abrindo navegador...
start "" "http://127.0.0.1:8000"

echo.
echo  ============================================
echo   Sistema rodando!
echo   MySQL:    porta 3306 (XAMPP)
echo   Backend:  http://127.0.0.1:8000
echo   Vite HMR: http://localhost:5173
echo  ============================================
echo.
pause
