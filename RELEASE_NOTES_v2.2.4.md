# Release Notes - AMI Laravel Package v2.2.4

## 🔧 React Socket API Compatibility Hotfix

**Release Date**: October 20, 2025  
**Version**: v2.2.4  
**Package**: `shahkochaki/ami-laravel-asterisk-manager-interface`

---

## 🚨 Critical Fix: React Socket v1.16+ Compatibility

این نسخه مشکل compatibility با نسخه‌های جدید `react/socket` را حل می‌کند.

## 🐛 مشکل حل شده

### خطای React Socket API

```
Call to undefined method React\Socket\Connector::create()
```

**علت**: در نسخه‌های جدید `react/socket` (v1.16+), متد `create()` حذف شده و باید از `connect()` استفاده کرد.

**راه‌حل**: تغییر API call ها مطابق با نسخه جدید React Socket.

## 🔧 تغییرات اعمال شده

### 1. اصلاح Factory::create() Method

```php
// قبل (مشکل‌ساز):
$promise = $this->connector->create($options['host'], $options['port'])

// بعد (حل شده):
$uri = $options['host'] . ':' . $options['port'];
$promise = $this->connector->connect($uri)
```

### 2. API Changes مدیریت شده

- ✅ `$connector->create($host, $port)` → `$connector->connect($host . ':' . $port)`
- ✅ URI format connection برای React Socket v1.16+
- ✅ حفظ تمام functionality های قبلی
- ✅ Backward compatibility با promise handling

### 3. Stream Type Handling

```php
// حذف deprecated type hints
->then(function ($stream) {  // بدون type hint برای compatibility
    return new Client($stream, new \Shahkochaki\Ami\Parser());
})
```

## 📦 جدول سازگاری React Socket

| نسخه Package | React Socket | API Method             | وضعیت         |
| ------------ | ------------ | ---------------------- | ------------- |
| v2.2.4+      | v1.16+       | `connect($uri)`        | ✅ فعال       |
| v2.2.3       | v1.15-       | `create($host, $port)` | ❌ Deprecated |
| v2.2.2       | v1.15-       | `create($host, $port)` | ❌ Deprecated |

## 🚀 نحوه بروزرسانی

### برای پروژه‌های موجود:

```bash
# بروزرسانی package
composer update shahkochaki/ami-laravel-asterisk-manager-interface

# اطمینان از نصب react/socket جدید
composer update react/socket

# پاک کردن cache
php artisan config:clear
```

### بررسی نسخه React Socket:

```bash
composer show react/socket
```

## ✅ تست عملکرد

پس از بروزرسانی:

```bash
# تست اتصال AMI
php artisan ami:action Ping

# تست Factory connection
php artisan ami:listen

# بررسی عدم خطای create()
```

## 🔍 قبل از بروزرسانی

اگر این خطا را می‌بینید:

```
Call to undefined method React\Socket\Connector::create()
Fatal error in Factory.php line 46
```

**راه‌حل**: بروزرسانی به v2.2.4

## 💡 تکنیکال Details

### React Socket API Changes:

**Old API (Deprecated):**

```php
$connector->create($host, $port)->then(...)
```

**New API (v2.2.4):**

```php
$connector->connect("$host:$port")->then(...)
```

### Connection URI Format:

- ✅ `"192.168.1.100:5038"`
- ✅ `"localhost:5038"`
- ✅ `"asterisk.domain.com:5038"`

## 🔧 Dependencies بررسی شده

- ✅ `react/socket: ^1.16.0`
- ✅ `react/dns: ^1.0`
- ✅ `clue/ami-react: ^0.5|^1.0`
- ✅ Laravel 9-12 compatibility
- ✅ PHP 8.0-8.3 compatibility

## 🛠️ Breaking Changes

**هیچ breaking change نیست!** این بروزرسانی کاملاً backward compatible است:

- ✅ همان API برای کاربران
- ✅ همان method signatures
- ✅ همان return types
- ✅ همان error handling

## 📋 مقایسه قبل و بعد

### قبل (v2.2.3):

```bash
$ php artisan ami:action Ping
Call to undefined method React\Socket\Connector::create()
❌ Failed
```

### بعد (v2.2.4):

```bash
$ php artisan ami:action Ping
✅ AMI connection successful
✅ Ping response received
```

## 🎯 مزایای v2.2.4

1. **Future-Proof**: سازگار با جدیدترین React packages
2. **Stable API**: بدون تغییر در استفاده از package
3. **Better Performance**: بهره‌وری بهتر با React Socket جدید
4. **Long-term Support**: آماده برای dependency های آینده

## 📞 پشتیبانی

در صورت مشکل:

- 🐛 [GitHub Issues](https://github.com/shahkochaki/ami-laravel-asterisk-manager-interface/issues)
- 📧 **Email**: ali.shahkochaki7@gmail.com
- 📖 **Documentation**: [README.md](README.md)

## 🔄 Auto-Update Script

برای بروزرسانی آسان:

```bash
#!/bin/bash
# update-ami.sh
composer update shahkochaki/ami-laravel-asterisk-manager-interface
composer update react/socket
php artisan config:clear
php artisan cache:clear
echo "✅ AMI Package updated to v2.2.4"
```

---

⭐ **اگر این بروزرسانی مشکل شما را حل کرد، لطفاً ستاره بدهید!**

**Made with ❤️ for Laravel & Asterisk developers**
