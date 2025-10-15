#!/bin/bash

# AMI Package Release Script v2.0.1
# Author: Ali Shahkochaki

set -e

VERSION="2.0.1"
PACKAGE_NAME="shahkochaki/ami"

echo "🚀 Releasing $PACKAGE_NAME v$VERSION"
echo "=================================="

# Check if we're in the right directory
if [ ! -f "composer.json" ]; then
    echo "❌ Error: composer.json not found. Please run this script from the package root directory."
    exit 1
fi

# Check if version matches in composer.json
if ! grep -q "\"version\": \"$VERSION\"" composer.json; then
    echo "❌ Error: Version $VERSION not found in composer.json"
    exit 1
fi

# Check if git is clean
if [ -n "$(git status --porcelain)" ]; then
    echo "⚠️  Warning: Working directory is not clean. Please commit or stash changes first."
    echo "Uncommitted changes:"
    git status --short
    read -p "Continue anyway? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

# Run tests
echo "🧪 Running tests..."
if command -v composer &> /dev/null; then
    composer test || {
        echo "❌ Tests failed. Please fix them before releasing."
        exit 1
    }
else
    echo "⚠️  Composer not found, skipping tests"
fi

# Check if tag already exists
if git tag -l | grep -q "^v$VERSION$"; then
    echo "❌ Tag v$VERSION already exists"
    exit 1
fi

# Add and commit changes if any
if [ -n "$(git status --porcelain)" ]; then
    echo "📝 Committing changes..."
    git add .
    git commit -m "Release v$VERSION

- Updated to PHP 8.0+ and Laravel 9.0+
- Enhanced documentation with bilingual support
- Added modern service classes and features
- Improved performance and security
- Added comprehensive testing suite

See RELEASE_NOTES.md for detailed changelog."
fi

# Create and push tag
echo "🏷️  Creating tag v$VERSION..."
git tag -a "v$VERSION" -m "Release v$VERSION

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

echo "📤 Pushing tag to origin..."
git push origin "v$VERSION"

# Push main branch as well
echo "📤 Pushing main branch..."
git push origin main

echo "✅ Successfully released $PACKAGE_NAME v$VERSION!"
echo ""
echo "🎉 Release Summary:"
echo "   - Version: v$VERSION"
echo "   - Tag: v$VERSION"
echo "   - Branch: main"
echo "   - Repository: https://github.com/shahkochaki/ami"
echo ""
echo "📦 Next Steps:"
echo "   1. The release will appear on GitHub: https://github.com/shahkochaki/ami/releases"
echo "   2. Packagist will auto-update: https://packagist.org/packages/shahkochaki/ami"
echo "   3. Users can install with: composer require shahkochaki/ami:^2.0"
echo ""
echo "🙏 Thank you for using AMI package!"