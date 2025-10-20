# راهنمای عیب‌یابی - AMI Laravel Package

## مشکلات رایج و راه‌حل‌ها

### 🚨 خطای "Target class [ami] does not exist"

#### علت:

این خطا زمانی رخ می‌دهد که Laravel نتواند کلاس `ami` را در Service Container پیدا کند.

#### راه‌حل‌ها:

#### 1. بررسی ثبت Service Provider

**برای Laravel 9+:**
Service Provider باید به صورت خودکار تشخیص داده شود، اما برای اطمینان:

```bash
# پاک کردن cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# بازسازی autoload
composer dump-autoload
```

**برای Laravel قدیمی‌تر:**
در فایل `config/app.php` در بخش `providers` اضافه کنید:

```php
'providers' => [
    // ...
    Shahkochaki\Ami\Providers\AmiServiceProvider::class,
],
```

#### 2. انتشار فایل تنظیمات

```bash
php artisan vendor:publish --tag=ami
```

#### 3. تنظیم متغیرهای محیطی

در فایل `.env` خود:

```env
AMI_HOST=192.168.1.100
AMI_PORT=5038
AMI_USERNAME=your_ami_user
AMI_SECRET=your_ami_password
```

#### 4. بررسی فایل تنظیمات

مطمئن شوید که فایل `config/ami.php` وجود دارد و شامل تنظیمات صحیح است:

```php
<?php

return [
    'host' => env('AMI_HOST', '127.0.0.1'),
    'port' => env('AMI_PORT', 5038),
    'username' => env('AMI_USERNAME'),
    'secret' => env('AMI_SECRET'),
    // ...
];
```

### 🔧 نحوه استفاده صحیح

#### استفاده از Service:

```php
// روش 1: Dependency Injection
use Shahkochaki\Ami\Services\AmiService;

class YourController extends Controller
{
    protected $ami;

    public function __construct(AmiService $ami)
    {
        $this->ami = $ami;
    }

    public function test()
    {
        return $this->ami->ping();
    }
}
```

```php
// روش 2: Service Container
$ami = app('ami');
$result = $ami->ping();
```

```php
// روش 3: Helper Function
$ami = resolve('ami');
$result = $ami->makeCall('1001', '1002');
```

#### استفاده از Facade:

```php
use Shahkochaki\Ami\Facades\Ami;

// تست اتصال
$result = Ami::ping();

// برقراری تماس
$result = Ami::makeCall('1001', '1002');

// ارسال SMS
$result = Ami::sendSms('09123456789', 'Hello World');
```

#### استفاده مستقیم از Service Class:

```php
use Shahkochaki\Ami\Services\AmiService;

$ami = new AmiService([
    'host' => '192.168.1.100',
    'port' => 5038,
    'username' => 'admin',
    'secret' => 'mypass'
]);

$result = $ami->ping();
```

### 🐛 سایر مشکلات رایج

#### 1. خطای اتصال: "Connection refused"

**علت:** سرور Asterisk در دسترس نیست یا AMI غیرفعال است.

**راه‌حل:**

```bash
# بررسی وضعیت Asterisk
sudo systemctl status asterisk

# بررسی تنظیمات AMI
sudo nano /etc/asterisk/manager.conf

# اعمال تغییرات
sudo asterisk -rx "manager reload"
```

#### 2. خطای احراز هویت: "Authentication failed"

**علت:** نام کاربری یا رمز عبور اشتباه است.

**راه‌حل:**

```ini
# در فایل /etc/asterisk/manager.conf
[general]
enabled = yes
port = 5038
bindaddr = 0.0.0.0

[your_user]
secret = your_password
read = all
write = all
```

#### 3. خطای دسترسی: "Permission denied"

**علت:** کاربر AMI دسترسی کافی ندارد.

**راه‌حل:**

```ini
# در فایل manager.conf
[your_user]
secret = your_password
read = all
write = all  # برای عملیات سیستم ضروری است
```

#### 4. مشکل پیدا نکردن دستگاه SMS: "Device not found"

**راه‌حل:**

```bash
# بررسی وضعیت Chan Dongle
sudo asterisk -rx "dongle show devices"

# در صورت عدم نمایش دستگاه، بررسی تنظیمات
sudo nano /etc/asterisk/dongle.conf
```

### 🔍 دستورات تست و بررسی

#### تست اتصال AMI:

```bash
# تست ساده
php artisan ami:action Ping

# تست با نمایش جزئیات
php artisan ami:action Ping --monitor

# تست با تنظیمات سفارشی
php artisan ami:action Ping --host=192.168.1.100 --port=5038 --username=admin --secret=mypass
```

#### تست عملیات مختلف:

```bash
# وضعیت کانال‌ها
php artisan ami:action Status

# دریافت اطلاعات سیستم
php artisan ami:system status

# تست ارسال SMS
php artisan ami:dongle:sms 09123456789 "Test message"

# گوش دادن به رویدادها
php artisan ami:listen --monitor
```

### 📊 بررسی لاگ‌ها

#### لاگ‌های Laravel:

```bash
# مشاهده لاگ‌های Laravel
tail -f storage/logs/laravel.log
```

#### لاگ‌های Asterisk:

```bash
# لاگ‌های کامل Asterisk
sudo tail -f /var/log/asterisk/full

# لاگ‌های AMI
sudo tail -f /var/log/asterisk/manager.log

# لاگ‌های Chan Dongle
sudo tail -f /var/log/asterisk/messages
```

### 🛠️ تشخیص مشکل به صورت سیستماتیک

#### مرحله 1: بررسی Package

```bash
# بررسی نصب package
composer show shahkochaki/ami-laravel-asterisk-manager-interface

# بررسی Service Provider
php artisan route:list | grep ami
```

#### مرحله 2: بررسی تنظیمات

```bash
# بررسی فایل تنظیمات
cat config/ami.php

# بررسی متغیرهای محیطی
php artisan tinker
>>> config('ami')
```

#### مرحله 3: بررسی اتصال

```bash
# تست اتصال
telnet YOUR_ASTERISK_IP 5038

# یا
nc -zv YOUR_ASTERISK_IP 5038
```

#### مرحله 4: تست در محیط Laravel

```php
// در php artisan tinker
$ami = app('ami');
dd($ami);

// تست ping
$result = $ami->ping();
dd($result);
```

### 💡 نکات مهم

1. **پورت فایروال:** مطمئن شوید پورت 5038 باز است
2. **دسترسی‌ها:** کاربر AMI باید `read=all` و `write=all` داشته باشد
3. **SSL:** برای اتصال امن، تنظیمات SSL را بررسی کنید
4. **Performance:** برای سرورهای پرترافیک، connection pooling را فعال کنید

### 📞 دریافت کمک

اگر مشکل شما حل نشد:

1. **GitHub Issues:** https://github.com/shahkochaki/ami-laravel-asterisk-manager-interface/issues
2. **Email:** ali.shahkochaki7@gmail.com
3. **مستندات:** README.md و SYSTEM_MANAGEMENT.md

### 🔄 بروزرسانی Package

```bash
# بروزرسانی به آخرین نسخه
composer update shahkochaki/ami-laravel-asterisk-manager-interface

# پاک کردن cache‌ها
php artisan config:clear
php artisan cache:clear

# انتشار مجدد تنظیمات (در صورت نیاز)
php artisan vendor:publish --tag=ami --force
```
