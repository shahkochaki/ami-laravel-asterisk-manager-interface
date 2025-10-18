# Release Notes - Version 2.1.5

## 🔧 **Additional Symfony Console Compatibility Fix**

**Release Date:** October 18, 2025  
**Version:** 2.1.5  
**Priority:** 🔴 CRITICAL  
**Focus:** Fix remaining console method signature compatibility

---

## ⚠️ **Additional Critical Issue Resolved**

### 🚨 **Run Method Fatal Error Fixed**

- **Problem:** `FatalError: Declaration of Shahkochaki\Ami\Commands\Command::run() must be compatible with Symfony\Component\Console\Command\Command::run(): int`
- **Impact:** Console commands still crashed despite v2.1.4 fix
- **Root Cause:** Missing return type declaration in `run()` method
- **Solution:** Updated `run()` method signature to match Symfony Console requirements

### 🔄 **Technical Changes**

#### Updated Method Signature

```php
// Before (v2.1.4)
public function run(InputInterface $input, OutputInterface $output)

// After (v2.1.5)
public function run(InputInterface $input, OutputInterface $output): int
```

### 📦 **Complete Fix Coverage**

Now both critical methods are properly compatible:

- ✅ `execute()` method - Fixed in v2.1.4
- ✅ `run()` method - Fixed in v2.1.5

### 🎯 **Affected Commands**

- `php artisan ami:listen`
- `php artisan ami:sms`
- `php artisan ami:ussd`
- `php artisan ami:cli`
- All custom AMI commands extending base Command class

---

## 🚀 **Installation & Update**

### New Installation

```bash
composer require shahkochaki/ami-laravel-asterisk-manager-interface:^2.1.5
```

### Update Existing Installation

```bash
composer update shahkochaki/ami-laravel-asterisk-manager-interface
```

---

## 🔄 **Migration**

**Automatic** - No code changes required in your application.

This is a **drop-in replacement** that completes the Symfony Console compatibility fix.

### Verification

After updating, verify the fix by running:

```bash
php artisan queue:work fastJob
# or any AMI command like:
php artisan ami:listen
```

---

## 📋 **Compatibility**

- ✅ **PHP:** 8.0, 8.1, 8.2, 8.3
- ✅ **Laravel:** 9.x, 10.x, 11.x, 12.x
- ✅ **Symfony Console:** 4.4+, 5.x, 6.x, 7.x
- ✅ **Asterisk/Issabel:** All supported versions

---

## 🐛 **Bug Fixes**

### Command Execution

- Fixed remaining fatal error in `run()` method signature
- Complete Symfony Console compatibility achieved
- All console command operations now stable

---

## ⚡ **Performance & Stability**

- **Zero performance impact** - only signature changes
- **Complete reliability** - all method signatures now compatible
- **Future-proof** - fully compatible with modern Symfony versions

---

## 🙏 **Credits**

Thanks for the immediate feedback on the remaining compatibility issue.

---

## 📞 **Support**

- **GitHub Issues:** [Report bugs](https://github.com/shahkochaki/ami-laravel-asterisk-manager-interface/issues)
- **Documentation:** [Read the docs](https://github.com/shahkochaki/ami-laravel-asterisk-manager-interface#readme)
- **Email:** ali.shahkochaki7@gmail.com
