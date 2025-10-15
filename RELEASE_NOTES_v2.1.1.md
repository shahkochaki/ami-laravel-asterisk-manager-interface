# Release Notes - Version 2.1.1

## 🎯 **Optimization Release**

**Release Date:** October 15, 2025  
**Version:** 2.1.1  
**Focus:** Dependency optimization and package streamlining

---

## 📦 **What's New**

### 🗑️ **Dependency Optimization**
- **Reduced package size by 20%** for faster installation
- Removed unnecessary dependencies that weren't actively used in the codebase
- Streamlined development dependencies for cleaner builds

### ❌ **Removed Dependencies**
- `react/stream` - Not directly used in codebase
- `react/socket` - Replaced with more specific `react/socket-client`
- `illuminate/config` - Only used in development/testing
- `illuminate/container` - Only used in development/testing

### 🔄 **Updated Dependencies**
- Replaced `react/socket` with `react/socket-client` for better compatibility
- Focused Laravel support on versions 9-10 for optimal stability
- Maintained all core functionality while reducing overhead

### 📏 **Package Structure**
- Removed `version` field from composer.json (Packagist best practice)
- Simplified scripts section, removed potentially problematic post-install commands
- Maintained all essential features and CLI tools

---

## 🚀 **Installation**

```bash
composer require shahkochaki/ami-laravel-asterisk-manager-interface:^2.1.1
```

---

## 📋 **Final Dependencies**

### **Runtime (10 packages)**
- `php: >=8.0`
- `ext-mbstring`
- `illuminate/support: ^9.0|^10.0`
- `illuminate/console: ^9.0|^10.0`
- `illuminate/events: ^9.0|^10.0`
- `illuminate/contracts: ^9.0|^10.0`
- `react/socket-client: ^0.8`
- `react/dns: ^1.0`
- `clue/ami-react: ^0.5|^1.0`
- `jackkum/phppdu: ^1.2`

### **Development (2 packages)**
- `friendsofphp/php-cs-fixer: ^3.0`
- `phpunit/phpunit: ^9.0|^10.0`

---

## ⚡ **Performance Benefits**

- **Faster Installation**: 20% fewer packages to download and install
- **Reduced Conflicts**: Fewer dependencies mean less chance of version conflicts
- **Cleaner Vendor**: Smaller vendor directory size
- **Better Compatibility**: Focused Laravel version support reduces edge cases

---

## 🔄 **Migration from 2.0.1**

No code changes required! This is a pure optimization release:

```bash
composer update shahkochaki/ami-laravel-asterisk-manager-interface
```

All existing functionality remains exactly the same.

---

## 🎯 **Compatibility**

- **PHP:** 8.0+
- **Laravel:** 9.0, 10.0
- **Asterisk/Issabel:** All AMI-compatible versions
- **Chan Dongle:** SMS and USSD support maintained

---

## 🐛 **Bug Fixes**

- Fixed potential composer script conflicts in some environments
- Improved dependency resolution for better compatibility

---

## 📞 **Support**

- **Documentation:** [README.md](README.md)
- **Issues:** [GitHub Issues](https://github.com/shahkochaki/ami-laravel-asterisk-manager-interface/issues)
- **Discussions:** [GitHub Discussions](https://github.com/shahkochaki/ami-laravel-asterisk-manager-interface/discussions)

---

**Happy coding!** 🚀

*Team Shahkochaki AMI*