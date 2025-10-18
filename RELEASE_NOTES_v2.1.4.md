# Release Notes - Version 2.1.4

## 🔧 **Symfony Console Compatibility Fix**

**Release Date:** October 18, 2025  
**Version:** 2.1.4  
**Priority:** 🔴 CRITICAL  
**Focus:** Fix fatal error with modern Symfony Console versions

---

## ⚠️ **Critical Issue Resolved**

### 🚨 **Fatal Error Fixed**

- **Problem:** `FatalError: Declaration of Shahkochaki\Ami\Commands\Command::execute() must be compatible with Symfony\Component\Console\Command\Command::execute(): int`
- **Impact:** Queue workers and AMI commands crashed with fatal errors
- **Root Cause:** Missing return type declaration in console command base class
- **Solution:** Updated `execute()` method signature to match Symfony Console v4.4+ requirements

### 🔄 **Technical Changes**

#### Updated Method Signature
```php
// Before (v2.1.3)
protected function execute(InputInterface $input, OutputInterface $output)

// After (v2.1.4)  
protected function execute(InputInterface $input, OutputInterface $output): int
```

#### Normalized Return Values
- **Success cases:** Returns `0` (success exit code)
- **Failure cases:** Returns `1` (error exit code)  
- **Handler results:** Properly converts mixed return types to integers

### 📦 **Affected Commands**
- `php artisan ami:listen`
- `php artisan ami:sms`
- `php artisan ami:ussd`
- `php artisan ami:cli`
- All custom AMI commands extending base Command class

---

## 🚀 **Installation & Update**

### New Installation
```bash
composer require shahkochaki/ami-laravel-asterisk-manager-interface:^2.1.4
```

### Update Existing Installation
```bash
composer update shahkochaki/ami-laravel-asterisk-manager-interface
```

---

## 🔄 **Migration**

**Automatic** - No code changes required in your application.

This is a **drop-in replacement** that maintains full backward compatibility while fixing the fatal error.

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
- Fixed fatal error when running AMI commands through queue workers
- Fixed method signature compatibility with modern Symfony Console
- Ensured proper exit code handling for all command scenarios

---

## ⚡ **Performance & Stability**

- **Zero performance impact** - only signature change
- **Enhanced reliability** - proper exit code handling
- **Future-proof** - compatible with upcoming Symfony versions

---

## 🙏 **Credits**

Thanks to the community for reporting this critical issue promptly.

---

## 📞 **Support**

- **GitHub Issues:** [Report bugs](https://github.com/shahkochaki/ami-laravel-asterisk-manager-interface/issues)
- **Documentation:** [Read the docs](https://github.com/shahkochaki/ami-laravel-asterisk-manager-interface#readme)
- **Email:** ali.shahkochaki7@gmail.com