# Release Notes - AMI Laravel Package v2.2.3

## 🐛 Type Compatibility Hotfix

**Release Date**: October 20, 2025  
**Version**: v2.2.3  
**Package**: `shahkochaki/ami-laravel-asterisk-manager-interface`

---

## 🚨 Critical Fix: Laravel 12 & PHP 8+ Compatibility

این نسخه مشکل type compatibility با Laravel 12 و نسخه‌های جدید PHP را حل می‌کند.

## 🐛 مشکل حل شده

### خطای Type Declaration Compatibility

```
Declaration of OutputStyle::isQuiet() must be compatible with...
```

**علت**: در نسخه‌های جدید Symfony Console و Laravel 12، متدهای `OutputStyle` باید return type declaration داشته باشند.

**راه‌حل**: اضافه کردن `: bool` به تمام متدهای مربوطه.

## 🔧 تغییرات اعمال شده

### 1. اصلاح OutputStyle Methods

```php
// قبل (مشکل‌ساز):
public function isQuiet()
{
    return $this->output->isQuiet();
}

// بعد (حل شده):
public function isQuiet(): bool
{
    return $this->output->isQuiet();
}
```

### 2. متدهای اصلاح شده

- ✅ `isQuiet(): bool`
- ✅ `isVerbose(): bool`
- ✅ `isVeryVerbose(): bool`
- ✅ `isDebug(): bool`

### 3. پشتیبانی از Laravel 11

```json
{
  "illuminate/support": "^9.0|^10.0|^11.0|^12.0",
  "illuminate/console": "^9.0|^10.0|^11.0|^12.0",
  "illuminate/events": "^9.0|^10.0|^11.0|^12.0",
  "illuminate/contracts": "^9.0|^10.0|^11.0|^12.0"
}
```

## 📦 جدول پشتیبانی

| نسخه   | PHP  | Laravel  | Symfony Console | وضعیت         |
| ------ | ---- | -------- | --------------- | ------------- |
| v2.2.3 | 8.0+ | 9.x-12.x | 5.x-6.x         | ✅ فعال       |
| v2.2.2 | 8.0+ | 9.x-12.x | 5.x             | ⚠️ Type Issue |
| v2.2.1 | 8.0+ | 9.x-12.x | 5.x             | ⚠️ Type Issue |

## 🚀 نحوه بروزرسانی

### برای پروژه‌های موجود:

```bash
# بروزرسانی package
composer update shahkochaki/ami-laravel-asterisk-manager-interface

# پاک کردن cache
php artisan config:clear
php artisan cache:clear
```

### برای پروژه‌های جدید:

```bash
# نصب آخرین نسخه
composer require shahkochaki/ami-laravel-asterisk-manager-interface:^2.2.3
```

## ✅ تست عملکرد

پس از بروزرسانی، برای اطمینان از حل مشکل:

```bash
# تست اتصال
php artisan ami:action Ping

# تست مدیریت سیستم
php artisan ami:system status

# تست در محیط Laravel 12
php artisan --version
```

## 🐛 قبل از بروزرسانی

اگر این خطا را می‌بینید:

```
Declaration of Shahkochaki\Ami\Commands\OutputStyle::isQuiet()
must be compatible with Symfony\Component\Console\Style\SymfonyStyle::isQuiet(): bool
```

**راه‌حل**: بروزرسانی به v2.2.3

## 💡 نکات مهم

1. **Backward Compatible**: این بروزرسانی کاملاً سازگار با نسخه‌های قبلی است
2. **No Breaking Changes**: هیچ تغییری در API یا استفاده از package نیست
3. **Automatic Fix**: فقط composer update کافی است

## 🔍 تست شده روی

- ✅ Laravel 9.x + PHP 8.0
- ✅ Laravel 10.x + PHP 8.1
- ✅ Laravel 11.x + PHP 8.2
- ✅ Laravel 12.x + PHP 8.3

## 📋 قبل و بعد

### قبل (v2.2.2):

```bash
$ composer install
Fatal error: Declaration of OutputStyle::isQuiet() must be compatible...
```

### بعد (v2.2.3):

```bash
$ composer install
✅ Package installed successfully

$ php artisan ami:action Ping
✅ Works perfectly
```

## 🙏 تشکر

از کاربرانی که این مشکل را گزارش کردند و کمک کردند تا سریعاً حل شود، تشکر می‌کنیم.

## 📞 پشتیبانی

در صورت مشکل:

- 🐛 [GitHub Issues](https://github.com/shahkochaki/ami-laravel-asterisk-manager-interface/issues)
- 📧 **Email**: ali.shahkochaki7@gmail.com
- 📖 **Documentation**: [README.md](README.md)

---

⭐ **اگر این بروزرسانی مشکل شما را حل کرد، لطفاً ستاره بدهید!**

**Made with ❤️ for Laravel & Asterisk developers**
