# System Management for Asterisk/Issabel Server

این بسته امکان مدیریت سیستم سرور Asterisk/Issabel را فراهم می‌کند، شامل خاموش کردن، ریست کردن و نظارت بر وضعیت سرور.

## ویژگی‌های اصلی

- **خاموش کردن سرور**: امکان خاموش کردن تدریجی یا فوری سرور
- **ریست کردن سرور**: امکان ریست کردن تدریجی یا فوری سرور
- **بارگیری مجدد تنظیمات**: بارگیری مجدد تنظیمات کل سیستم یا ماژول خاص
- **نظارت بر وضعیت**: دریافت اطلاعات کامل از وضعیت سرور
- **مدیریت منابع**: نظارت بر مصرف حافظه و منابع سیستم

## استفاده از Service

### SystemManager Service

```php
use Shahkochaki\Ami\Services\SystemManager;

// ایجاد instance از SystemManager
$systemManager = new SystemManager([
    'host' => 'localhost',
    'port' => 5038,
    'username' => 'admin',
    'secret' => 'amp111'
]);

// خاموش کردن تدریجی سرور
$result = $systemManager->shutdownServer(true, 'System maintenance');

// ریست کردن فوری سرور
$result = $systemManager->restartServer(false, 'Emergency restart');

// بارگیری مجدد تنظیمات SIP
$result = $systemManager->reloadConfiguration('sip');

// دریافت وضعیت سرور
$status = $systemManager->getServerStatus();
```

### استفاده از Facade

```php
use Shahkochaki\Ami\Facades\SystemManager;

// خاموش کردن تدریجی
SystemManager::shutdownServer(true, 'Scheduled maintenance');

// ریست کردن فوری
SystemManager::emergencyRestart();

// دریافت کانال‌های فعال
$channels = SystemManager::getActiveChannels();

// برنامه‌ریزی خاموش کردن با تاخیر 30 دقیقه
$schedule = SystemManager::scheduleShutdown(30, true, 'Maintenance window');
```

## استفاده از Command Line

### دستور `ami:system`

```bash
# خاموش کردن تدریجی سرور
php artisan ami:system shutdown --graceful

# ریست کردن فوری سرور
php artisan ami:system restart --force

# بارگیری مجدد ماژول SIP
php artisan ami:system reload --module=sip

# دریافت وضعیت سرور
php artisan ami:system status

# خاموش کردن با تنظیمات اتصال سفارشی
php artisan ami:system shutdown --host=192.168.1.100 --port=5038 --username=admin --secret=mypass
```

### پارامترهای دستور

- `operation`: عملیات مورد نظر (shutdown|restart|reload|status)
- `--graceful`: استفاده از خاموش/ریست تدریجی
- `--module=MODULE`: ماژول خاص برای بارگیری مجدد
- `--force`: اجرا بدون تأیید
- `--host=HOST`: آدرس سرور AMI
- `--port=PORT`: پورت سرور AMI
- `--username=USER`: نام کاربری AMI
- `--secret=PASS`: رمز عبور AMI

## توابع موجود

### توابع اصلی سیستم

#### `shutdownServer($graceful = true, $reason = 'System maintenance')`

خاموش کردن سرور Asterisk

- `$graceful`: اگر true باشد، منتظر اتمام تماس‌های فعال می‌ماند
- `$reason`: دلیل خاموش کردن

#### `restartServer($graceful = true, $reason = 'System maintenance')`

ریست کردن سرور Asterisk

- `$graceful`: اگر true باشد، منتظر اتمام تماس‌های فعال می‌ماند
- `$reason`: دلیل ریست

#### `reloadConfiguration($module = null)`

بارگیری مجدد تنظیمات

- `$module`: نام ماژول خاص (اختیاری) - اگر null باشد، کل تنظیمات بارگیری می‌شود

#### `getServerStatus()`

دریافت اطلاعات کامل وضعیت سرور شامل:

- اطلاعات سیستم
- زمان فعالیت (uptime)
- کانال‌های فعال
- مصرف حافظه
- آمار تماس‌ها

### توابع اضطراری

#### `emergencyShutdown()`

خاموش کردن فوری بدون انتظار برای تماس‌های فعال

#### `emergencyRestart()`

ریست فوری بدون انتظار برای تماس‌های فعال

### توابع برنامه‌ریزی

#### `scheduleShutdown($delayMinutes, $graceful = true, $reason = 'Scheduled maintenance')`

برنامه‌ریزی خاموش کردن با تاخیر زمانی

#### `scheduleRestart($delayMinutes, $graceful = true, $reason = 'Scheduled maintenance')`

برنامه‌ریزی ریست با تاخیر زمانی

### توابع نظارت

#### `getActiveChannels()`

دریافت لیست کانال‌های فعال

#### `getSystemResources()`

دریافت اطلاعات مصرف منابع سیستم

## مثال‌های عملی

### مثال 1: خاموش کردن برنامه‌ریزی شده

```php
use Shahkochaki\Ami\Services\SystemManager;

$systemManager = new SystemManager();

// بررسی وجود تماس‌های فعال
$channels = $systemManager->getActiveChannels();

if (empty($channels)) {
    // خاموش کردن فوری
    $systemManager->shutdownServer(false, 'No active calls');
} else {
    // خاموش کردن تدریجی
    $systemManager->shutdownServer(true, 'Active calls detected');
}
```

### مثال 2: ریست سیستم با بررسی وضعیت

```php
use Shahkochaki\Ami\Services\SystemManager;

$systemManager = new SystemManager();

// دریافت وضعیت سیستم
$status = $systemManager->getServerStatus();

// بررسی شرایط برای ریست
if (isset($status['memory']) && $this->isHighMemoryUsage($status['memory'])) {
    // ریست به دلیل مصرف بالای حافظه
    $systemManager->restartServer(true, 'High memory usage detected');
}
```

### مثال 3: بارگیری مجدد تنظیمات ماژول‌های خاص

```php
use Shahkochaki\Ami\Services\SystemManager;

$systemManager = new SystemManager();

// بارگیری مجدد تنظیمات SIP
$systemManager->reloadConfiguration('sip');

// بارگیری مجدد تنظیمات Dialplan
$systemManager->reloadConfiguration('dialplan');

// بارگیری مجدد کل تنظیمات
$systemManager->reloadConfiguration();
```

## پیکربندی

تنظیمات اتصال AMI در فایل `config/ami.php` تعریف شده‌اند:

```php
return [
    'host' => env('AMI_HOST', 'localhost'),
    'port' => env('AMI_PORT', 5038),
    'username' => env('AMI_USERNAME', 'admin'),
    'secret' => env('AMI_SECRET', 'amp111'),
    'connection_timeout' => env('AMI_CONNECTION_TIMEOUT', 10),
    'read_timeout' => env('AMI_READ_TIMEOUT', 10),
];
```

## رویدادها

این سرویس رویدادهای زیر را منتشر می‌کند:

- `ami.system.operation.sent`: هنگام ارسال دستور سیستم
- `ami.system.operation.completed`: هنگام تکمیل دستور سیستم

```php
// شنود رویدادها
Event::listen('ami.system.operation.sent', function ($command, $operation, $request) {
    Log::info("System operation sent: {$operation}");
});

Event::listen('ami.system.operation.completed', function ($command, $operation, $response) {
    Log::info("System operation completed: {$operation}");
});
```

## نکات امنیتی

- همیشه قبل از عملیات مخرب، تأیید کاربر را دریافت کنید
- از `--force` فقط در موارد اضطراری استفاده کنید
- عملیات سیستم را در ساعات کم ترافیک انجام دهید
- قبل از ریست، backup از تنظیمات تهیه کنید

## عیب‌یابی

اگر با خطا مواجه شدید:

1. اتصال به AMI را بررسی کنید
2. دسترسی‌های کاربر AMI را تأیید کنید
3. وضعیت سرور Asterisk را بررسی کنید
4. لاگ‌های سیستم را بررسی کنید

```bash
# بررسی وضعیت Asterisk
sudo systemctl status asterisk

# بررسی لاگ‌های Asterisk
sudo tail -f /var/log/asterisk/full
```
