@echo off
title MeuFreela - Iniciando...
color 0A

echo.
echo  ============================================
echo     MeuFreela - Iniciando Sistema
echo  ============================================
echo.

set PROJECT=c:\Users\orafa\OneDrive\Documentos\GitHub\MeuFreelaV2\laravel

echo  [1/3] Iniciando Backend (Laravel)...
start "MeuFreela - Backend" /min cmd /k "cd /d %PROJECT% && php artisan serve --host=127.0.0.1 --port=8000"

echo  [2/3] Iniciando Frontend (Vite)...
start "MeuFreela - Frontend" /min cmd /k "cd /d %PROJECT% && npm run dev"

echo  [3/3] Aguardando servidores subirem...
timeout /t 4 /nobreak >nul

echo  Abrindo navegador...
start "" "http://127.0.0.1:8000"

echo.
echo  ============================================
echo   Sistema rodando!
echo   Backend:  http://127.0.0.1:8000
echo   Vite HMR: http://localhost:5173
echo  ============================================
echo.
pause
