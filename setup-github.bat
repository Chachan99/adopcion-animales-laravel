@echo off
echo ========================================
echo   Configuracion de GitHub para Render
echo ========================================
echo.

echo 1. Asegurate de haber creado un repositorio en GitHub
echo 2. Copia la URL de tu repositorio (ejemplo: https://github.com/usuario/repo.git)
echo.

set /p repo_url="Ingresa la URL de tu repositorio GitHub: "

if "%repo_url%"=="" (
    echo Error: Debes ingresar una URL valida
    pause
    exit /b 1
)

echo.
echo Configurando repositorio remoto...
git remote add origin %repo_url%

echo Cambiando a rama main...
git branch -M main

echo Subiendo codigo a GitHub...
git push -u origin main

echo.
echo ========================================
echo   Configuracion completada!
echo ========================================
echo.
echo Siguiente paso:
echo 1. Ve a render.com y crea una cuenta
echo 2. Sigue la guia en RENDER_DEPLOYMENT_GUIDE.md
echo.
pause