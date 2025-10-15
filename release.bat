@echo off
REM AMI Package Release Script v2.0.1 for Windows
REM Author: Ali Shahkochaki

setlocal EnableDelayedExpansion

set VERSION=2.0.1
set PACKAGE_NAME=shahkochaki/ami

echo 🚀 Releasing %PACKAGE_NAME% v%VERSION%
echo ==================================

REM Check if composer.json exists
if not exist "composer.json" (
    echo ❌ Error: composer.json not found. Please run this script from the package root directory.
    pause
    exit /b 1
)

REM Check if version matches in composer.json
findstr /C:"\"version\": \"%VERSION%\"" composer.json >nul
if errorlevel 1 (
    echo ❌ Error: Version %VERSION% not found in composer.json
    pause
    exit /b 1
)

REM Check git status
git status --porcelain >nul 2>&1
if not errorlevel 1 (
    for /f %%i in ('git status --porcelain') do (
        echo ⚠️  Warning: Working directory is not clean.
        echo Please commit or stash changes first.
        set /p choice="Continue anyway? (y/N): "
        if /i not "!choice!"=="y" exit /b 1
        goto :continue
    )
)
:continue

REM Run tests
echo 🧪 Running tests...
where composer >nul 2>&1
if not errorlevel 1 (
    composer test
    if errorlevel 1 (
        echo ❌ Tests failed. Please fix them before releasing.
        pause
        exit /b 1
    )
) else (
    echo ⚠️  Composer not found, skipping tests
)

REM Check if tag exists
git tag -l | findstr /C:"v%VERSION%" >nul
if not errorlevel 1 (
    echo ❌ Tag v%VERSION% already exists
    pause
    exit /b 1
)

REM Commit changes if any
git status --porcelain | findstr . >nul
if not errorlevel 1 (
    echo 📝 Committing changes...
    git add .
    git commit -m "Release v%VERSION%

- Updated to PHP 8.0+ and Laravel 9.0+
- Enhanced documentation with bilingual support  
- Added modern service classes and features
- Improved performance and security
- Added comprehensive testing suite

See RELEASE_NOTES.md for detailed changelog."
)

REM Create tag
echo 🏷️  Creating tag v%VERSION%...
git tag -a "v%VERSION%" -m "Release v%VERSION%

🚀 Major Release: Modernized for PHP 8+ and Laravel 9+

Key Features:
✨ Modern PHP 8.0+ and Laravel 9.0+ support
🏗️ Enhanced architecture with service classes
📱 Advanced SMS management with bulk operations
📞 Improved call control and monitoring
🔧 Standalone CLI tool for non-Laravel usage
📚 Comprehensive bilingual documentation
🔒 Enhanced security and performance
🧪 Comprehensive testing suite

Migration Guide:
- Update to PHP 8.0+ and Laravel 9.0+
- Run: composer require shahkochaki/ami:^2.0
- Update configuration and environment variables
- Test thoroughly before production deployment

Full changelog: CHANGELOG.md
Release notes: RELEASE_NOTES.md"

REM Push tag
echo 📤 Pushing tag to origin...
git push origin "v%VERSION%"

REM Push main branch
echo 📤 Pushing main branch...
git push origin main

echo ✅ Successfully released %PACKAGE_NAME% v%VERSION%!
echo.
echo 🎉 Release Summary:
echo    - Version: v%VERSION%
echo    - Tag: v%VERSION%
echo    - Branch: main
echo    - Repository: https://github.com/shahkochaki/ami
echo.
echo 📦 Next Steps:
echo    1. The release will appear on GitHub: https://github.com/shahkochaki/ami/releases
echo    2. Packagist will auto-update: https://packagist.org/packages/shahkochaki/ami
echo    3. Users can install with: composer require shahkochaki/ami:^2.0
echo.
echo 🙏 Thank you for using AMI package!

pause