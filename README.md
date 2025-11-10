# AMI - Laravel Asterisk Manager Interface

<div align="center">
  
![AMI Logo](https://raw.githubusercontent.com/shahkochaki/ami-laravel-asterisk-manager-interface/main/logo.png)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/shahkochaki/ami-laravel-asterisk-manager-interface.svg?style=flat-square)](https://packagist.org/packages/shahkochaki/ami-laravel-asterisk-manager-interface)
[![Total Downloads](https://img.shields.io/packagist/dt/shahkochaki/ami-laravel-asterisk-manager-interface.svg?style=flat-square)](https://packagist.org/packages/shahkochaki/ami-laravel-asterisk-manager-interface)
[![License](https://img.shields.io/packagist/l/shahkochaki/ami-laravel-asterisk-manager-interface.svg?style=flat-square)](https://packagist.org/packages/shahkochaki/ami-laravel-asterisk-manager-interface)
[![PHP Version](https://img.shields.io/packagist/php-v/shahkochaki/ami-laravel-asterisk-manager-interface.svg?style=flat-square)](https://packagist.org/packages/shahkochaki/ami-laravel-asterisk-manager-interface)

</div>

A powerful and easy-to-use Laravel package for connecting to VOIP servers on the Issabel and Asterisk platform via Asterisk Manager Interface (AMI).

## ✨ Key Features

- 🔗 **Easy AMI Connection** - Simplified connection management
- 📱 **SMS Messaging** - Send SMS via Chan Dongle
- 📞 **Call Control** - Complete call management and monitoring
- 🎧 **Queue Management** - Advanced call queue handling
- 📊 **Real-time Monitoring** - Live event monitoring and logging
- �️ **System Management** - Server shutdown, restart, and configuration reload
- �🔧 **CLI Commands** - Powerful command-line interface
- 📋 **Interactive CLI** - User-friendly interactive console
- 🌐 **USSD Support** - Execute USSD commands seamlessly
- ⚡ **Async Processing** - Asynchronous event handling with ReactPHP
- 🔒 **High Security** - Secure authentication and connection management
- 📅 **Scheduled Operations** - Queue-based scheduled system operations

## 📋 Requirements

- PHP >= 8.0
- Laravel >= 9.0
- Asterisk/Issabel server with AMI enabled
- Chan Dongle (for SMS and USSD functionality)
- Extension `ext-mbstring`

## 🔄 Version Compatibility

| Package Version | PHP Version | Laravel Version | Features                      | Status     |
| --------------- | ----------- | --------------- | ----------------------------- | ---------- |
| 2.2.5           | 8.0+        | 9.0-12          | Console Command Fix, Docker   | ✅ Latest  |
| 2.2.x           | 8.0+        | 9.0-12          | System Management, Queue Jobs | ✅ Current |
| 2.1.x           | 8.0+        | 9.0-11          | Enhanced Features             | ✅ Stable  |
| 2.0.x           | 8.0+        | 9.0-10          | Modern PHP Features           | ✅ LTS     |
| 1.x             | 5.6+        | 5.1+            | Basic AMI Operations          | ⚠️ Legacy  |

**Note**: Version 2.2.5 includes Docker compatibility fixes and enhanced console command handling.

## 🚀 Installation

### Step 1: Install via Composer

```bash
# Latest stable release (v2.2.5)
composer require shahkochaki/ami-laravel-asterisk-manager-interface

# Or specify exact version
composer require shahkochaki/ami-laravel-asterisk-manager-interface:^2.2.5

# For development version
composer require shahkochaki/ami-laravel-asterisk-manager-interface:dev-master
```

### Docker Installation

For Docker environments, add a `.dockerignore` file to your project root:

```
vendor/
composer.lock
.git/
.env
node_modules/
*.patch
patches/
```

And use this in your Dockerfile:

```dockerfile
# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-cache

# Alternative for complex patch scenarios
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-plugins
```

### Step 2: Register Service Provider

**For Laravel 9+:** The service provider will be automatically discovered.

**For older versions:** Add to your `config/app.php` in the `providers` array:

```php
'providers' => [
    // Other providers...
    Shahkochaki\Ami\Providers\AmiServiceProvider::class,
]
```

### Step 3: Publish Configuration

```bash
php artisan vendor:publish --tag=ami
```

This will create the `config/ami.php` configuration file.

## 🔄 Upgrading from v1.x

If you're upgrading from an older version that supported PHP 5.6+ and Laravel 5.1+:

### 1. Update PHP and Laravel

```bash
# Make sure you have PHP 8.0+ and Laravel 9.0+
php --version
php artisan --version
```

### 2. Update the package

```bash
composer require shahkochaki/ami-laravel-asterisk-manager-interface
```

### 3. Update your code

- Replace `array_get()` helper with `Arr::get()`
- Update event listener syntax if needed
- Check deprecated Laravel features

### 4. Test thoroughly

```bash
php artisan ami:action Ping
```

## ⚙️ Configuration

### AMI Configuration in Asterisk

Before using the package, you need to create an AMI user in Asterisk. Edit `/etc/asterisk/manager.conf`:

```ini
[general]
enabled = yes
port = 5038
bindaddr = 0.0.0.0

[myuser]
secret = mypassword
read = all
write = all
```

After making changes, reload Asterisk:

```bash
asterisk -rx "manager reload"
```

### Laravel Configuration

Edit the `config/ami.php` file:

```php
<?php

return [
    'host' => env('AMI_HOST', '127.0.0.1'),
    'port' => env('AMI_PORT', 5038),
    'username' => env('AMI_USERNAME', 'myuser'),
    'secret' => env('AMI_SECRET', 'mypassword'),

    'dongle' => [
        'sms' => [
            'device' => env('AMI_SMS_DEVICE', 'dongle0'),
        ],
    ],

    'events' => [
        // Event handlers configuration
        'Dial' => [
            // Custom event handlers
        ],
        'Hangup' => [
            // Custom event handlers
        ],
        // More events...
    ],
];
```

### Environment Variables (.env)

```env
AMI_HOST=192.168.1.100
AMI_PORT=5038
AMI_USERNAME=myuser
AMI_SECRET=mypassword
AMI_SMS_DEVICE=dongle0
```

## 🎯 Usage

### 🎧 Event Listening

Listen to all AMI events:

```bash
php artisan ami:listen
```

With console logging:

```bash
php artisan ami:listen --monitor
```

In PHP code:

```php
use Illuminate\Support\Facades\Artisan;

Artisan::call('ami:listen');
```

### 📞 Sending AMI Actions

```bash
# Example: Get channel status
php artisan ami:action Status

# Example: Originate a call
php artisan ami:action Originate --arguments=Channel:SIP/1001 --arguments=Context:default --arguments=Exten:1002 --arguments=Priority:1

# Example: Hangup a call
php artisan ami:action Hangup --arguments=Channel:SIP/1001-00000001
```

In PHP code:

```php
use Illuminate\Support\Facades\Artisan;

// Get channel status
Artisan::call('ami:action', [
    'action' => 'Status'
]);

// Originate a call
Artisan::call('ami:action', [
    'action' => 'Originate',
    '--arguments' => [
        'Channel' => 'SIP/1001',
        'Context' => 'default',
        'Exten' => '1002',
        'Priority' => '1'
    ]
]);
```

### 📱 Sending SMS

Send regular SMS:

```bash
php artisan ami:dongle:sms 09123456789 "Hello, this is a test message"
```

Send long SMS (PDU mode):

```bash
php artisan ami:dongle:sms 09123456789 "Long message..." --pdu
```

Specify device:

```bash
php artisan ami:dongle:sms 09123456789 "Hello" dongle1
```

In PHP code:

```php
// Send regular SMS
Artisan::call('ami:dongle:sms', [
    'number' => '09123456789',
    'message' => 'Hello, this is a test message'
]);

// Send long SMS
Artisan::call('ami:dongle:sms', [
    'number' => '09123456789',
    'message' => 'Long message...',
    '--pdu' => true
]);
```

### 🌐 USSD Commands

```bash
php artisan ami:dongle:ussd dongle0 "*141#"
```

In PHP code:

```php
Artisan::call('ami:dongle:ussd', [
    'device' => 'dongle0',
    'ussd' => '*141#'
]);
```

### 💻 Interactive CLI Interface

Start CLI interface:

```bash
php artisan ami:cli
```

Run command and auto-close:

```bash
php artisan ami:cli "core show channels" --autoclose
```

### 🔧 Usage without Laravel

```bash
php ./vendor/bin/ami ami:listen --host=192.168.1.100 --port=5038 --username=myuser --secret=mypass --monitor
```

## 📚 Advanced Examples

### Event Management

```php
// In a Service Provider or Event Listener
use Illuminate\Support\Facades\Event;

Event::listen('ami.event.dial', function ($event) {
    Log::info('New call started', [
        'caller' => $event['CallerIDNum'],
        'destination' => $event['Destination']
    ]);
});

Event::listen('ami.event.hangup', function ($event) {
    Log::info('Call ended', [
        'channel' => $event['Channel'],
        'cause' => $event['Cause']
    ]);
});
```

### Custom Call Manager Class

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;

class CallManager
{
    public function makeCall($from, $to, $context = 'default')
    {
        return Artisan::call('ami:action', [
            'action' => 'Originate',
            '--arguments' => [
                'Channel' => "SIP/{$from}",
                'Context' => $context,
                'Exten' => $to,
                'Priority' => '1',
                'CallerID' => $from
            ]
        ]);
    }

    public function hangupCall($channel)
    {
        return Artisan::call('ami:action', [
            'action' => 'Hangup',
            '--arguments' => [
                'Channel' => $channel
            ]
        ]);
    }

    public function getChannelStatus()
    {
        return Artisan::call('ami:action', [
            'action' => 'Status'
        ]);
    }
}
```

### Bulk SMS Service

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Collection;

class BulkSmsService
{
    protected $device;

    public function __construct($device = null)
    {
        $this->device = $device ?: config('ami.dongle.sms.device');
    }

    public function sendBulkSms(Collection $recipients, string $message)
    {
        $results = [];

        foreach ($recipients as $number) {
            try {
                $result = Artisan::call('ami:dongle:sms', [
                    'number' => $number,
                    'message' => $message,
                    'device' => $this->device,
                    '--pdu' => strlen($message) > 160
                ]);

                $results[$number] = ['status' => 'success', 'result' => $result];

                // Small delay between messages
                usleep(500000); // 0.5 second

            } catch (\Exception $e) {
                $results[$number] = ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        return $results;
    }
}
```

## 🔍 Supported Events

The library supports all standard Asterisk events:

- **Call Events**: `Dial`, `Hangup`, `NewChannel`, `Bridge`
- **Agent Events**: `AgentConnect`, `AgentComplete`, `AgentLogin`, `AgentLogoff`
- **Queue Events**: `QueueMember`, `QueueParams`, `QueueSummary`
- **Dongle Events**: `DongleDeviceEntry`, `DongleSMSStatus`, `DongleUSSDStatus`
- **System Events**: `Reload`, `Shutdown`, `PeerStatus`

## 🐛 Troubleshooting

### Common Issues

1. **Connection Error**:

   ```
   Connection refused
   ```

   - Check that Asterisk is running
   - Verify port 5038 is open
   - Check firewall settings

2. **Authentication Error**:

   ```
   Authentication failed
   ```

   - Verify username and password
   - Check AMI user permissions

3. **SMS Device Error**:
   ```
   Device not found
   ```
   - Check Chan Dongle status: `dongle show devices`
   - Verify device name is correct

### Logging

Enable detailed logging:

```bash
php artisan ami:listen --monitor
```

### Connection Testing

```bash
# Simple connection test
php artisan ami:action Ping

# Check dongle devices status
php artisan ami:action Command --arguments=Command:"dongle show devices"
```

### Docker-Specific Issues (v2.2.5+)

4. **Docker Build Failures**:
   ```
   No available patcher was able to apply patch
   ```
   **Solution**: Add `.dockerignore` and use optimized Composer install:
   ```dockerfile
   RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-cache
   ```

5. **Console Commands in Docker**:
   ```
   OutputStyle class not found
   ```
   **Solution**: This is fixed in v2.2.5. Update to the latest version:
   ```bash
   composer require shahkochaki/ami-laravel-asterisk-manager-interface:^2.2.5
   ```

6. **Composer Cache Issues**:
   ```bash
   # Clear composer cache before Docker build
   composer clear-cache
   ```

## 🔧 Development

### Running Tests

```bash
composer test
```

### Code Standards

```bash
composer phpcs
```

### Project Structure

```
src/
├── Commands/          # Artisan commands
│   ├── AmiAbstract.php
│   ├── AmiAction.php
│   ├── AmiCli.php
│   ├── AmiListen.php
│   ├── AmiSms.php
│   └── AmiUssd.php
├── Providers/         # Service providers
│   └── AmiServiceProvider.php
├── Factory.php        # AMI connection factory
└── Parser.php         # AMI protocol parser

config/
└── ami.php           # Configuration file

tests/                # Test files
└── ...
```

## 🤝 Contributing

We welcome contributions! Please:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

- Use PSR-4 autoloading
- Run tests before committing
- Add proper PHPDoc comments
- Follow Laravel conventions

## 📄 License

This project is licensed under the [MIT License](LICENSE.md).

## 👨‍💻 Author

**Ali Shahkochaki**

- Website: [shahkochaki.ir](https://shahkochaki.ir)
- Email: ali.shahkochaki7@gmail.com
- GitHub: [@shahkochaki](https://github.com/shahkochaki)

## 🙏 Acknowledgments

- [ReactPHP](https://reactphp.org/) for event loop
- [clue/ami-react](https://github.com/clue/reactphp-ami) for AMI protocol
- Laravel community for the amazing framework

## 📚 Useful Resources

- [Asterisk Manager Interface](https://wiki.asterisk.org/wiki/display/AST/The+Asterisk+Manager+TCP+IP+API)
- [Chan Dongle Documentation](https://github.com/bg111/asterisk-chan-dongle)
- [Issabel Documentation](https://www.issabel.org/documentation/)

---

⭐ If this project helped you, please give it a star!

---

# AMI - رابط مدیریت Asterisk برای Laravel

<div align="center">
  
![لوگو AMI](https://raw.githubusercontent.com/shahkochaki/ami-laravel-asterisk-manager-interface/main/logo.png)

</div>

یک کتابخانه قدرتمند و آسان برای اتصال سرورهای Laravel به سرورهای VOIP بر روی پلتفرم Issabel و Asterisk از طریق Asterisk Manager Interface (AMI).

## ✨ امکانات اصلی

- 🔗 **اتصال آسان به AMI** - مدیریت ساده اتصالات
- 📱 **ارسال SMS** - ارسال پیامک از طریق Chan Dongle
- 📞 **کنترل تماس‌ها** - مدیریت کامل و مانیتورینگ تماس‌ها
- 🎧 **مدیریت صف تماس** - مدیریت پیشرفته صف‌های تماس
- 📊 **مانیتورینگ real-time** - مانیتورینگ و لاگ‌گیری زنده رویدادها
- �️ **مدیریت سیستم** - خاموش، ریست و بارگیری مجدد سرور
- �🔧 **دستورات CLI** - رابط خط فرمان قدرتمند
- 📋 **رابط کاربری تعاملی** - کنسول تعاملی کاربرپسند
- 🌐 **پشتیبانی از USSD** - اجرای دستورات USSD به صورت یکپارچه
- ⚡ **پردازش ناهمزمان** - مدیریت رویدادهای ناهمزمان با ReactPHP
- 🔒 **امنیت بالا** - احراز هویت ایمن و مدیریت اتصال
- 📅 **عملیات برنامه‌ریزی شده** - عملیات زمان‌بندی شده با Queue

## 📋 پیش‌نیازها

- PHP >= 8.0
- Laravel >= 9.0
- سرور Asterisk/Issabel با AMI فعال
- Chan Dongle (برای عملکرد SMS و USSD)
- Extension `ext-mbstring`

## 🔄 سازگاری نسخه‌ها

| نسخه پکیج | نسخه PHP | نسخه Laravel | امکانات جدید                  | وضعیت    |
| --------- | -------- | ------------ | ----------------------------- | -------- |
| 2.1+      | 8.0+     | 9.0+         | System Management, Queue Jobs | ✅ جدید  |
| 2.0       | 8.0+     | 9.0+         | Modern PHP Features           | ✅ فعلی  |
| 1.x       | 5.6+     | 5.1+         | Basic AMI Operations          | ⚠️ قدیمی |

**توجه**: نسخه 2.1+ شامل مدیریت کامل سیستم، عملیات برنامه‌ریزی شده و ویژگی‌های پیشرفته است.

### مقایسه امکانات نسخه‌ها

| ویژگی                       | v1.x | v2.0 | v2.1+ |
| --------------------------- | ---- | ---- | ----- |
| AMI Connection              | ✅   | ✅   | ✅    |
| Event Listening             | ✅   | ✅   | ✅    |
| SMS Sending                 | ✅   | ✅   | ✅    |
| USSD Commands               | ✅   | ✅   | ✅    |
| Call Management             | ✅   | ✅   | ✅    |
| Interactive CLI             | ✅   | ✅   | ✅    |
| Modern PHP (8.0+)           | ❌   | ✅   | ✅    |
| **System Management**       | ❌   | ❌   | ✅    |
| **Server Shutdown/Restart** | ❌   | ❌   | ✅    |
| **Configuration Reload**    | ❌   | ❌   | ✅    |
| **Health Monitoring**       | ❌   | ❌   | ✅    |
| **Scheduled Operations**    | ❌   | ❌   | ✅    |
| **Queue Jobs**              | ❌   | ❌   | ✅    |
| **SystemManager Service**   | ❌   | ❌   | ✅    |
| **Facade Support**          | ❌   | ❌   | ✅    |

## 🚀 شروع سریع / Quick Start

### نصب و راه‌اندازی اولیه

```bash
# نصب پکیج
composer require shahkochaki/ami-laravel-asterisk-manager-interface

# انتشار فایل تنظیمات
php artisan vendor:publish --tag=ami

# تنظیم متغیرهای محیطی
# در فایل .env
AMI_HOST=192.168.1.100
AMI_PORT=5038
AMI_USERNAME=myuser
AMI_SECRET=mypassword
```

### تست اتصال

```bash
# تست اتصال ساده
php artisan ami:action Ping

# گوش دادن به رویدادها
php artisan ami:listen --monitor

# دریافت وضعیت سرور
php artisan ami:system status
```

### مثال سریع مدیریت سیستم

```php
use Shahkochaki\Ami\Services\SystemManager;

// ایجاد instance
$systemManager = new SystemManager();

// دریافت وضعیت سرور
$status = $systemManager->getServerStatus();
echo "Server Status: " . json_encode($status);

// ریست امن سرور
$systemManager->restartServer(true, 'System update');
```

### گام 1: نصب از طریق Composer

```bash
composer require shahkochaki/ami-laravel-asterisk-manager-interface
```

یا برای آخرین نسخه توسعه:

```bash
composer require shahkochaki/ami-laravel-asterisk-manager-interface:dev-master
```

### گام 2: ثبت Service Provider

**برای Laravel 9+:** Service provider به صورت خودکار تشخیص داده می‌شود.

**برای نسخه‌های قدیمی‌تر:** در فایل `config/app.php` در آرایه `providers` اضافه کنید:

```php
'providers' => [
    // سایر providers...
    Shahkochaki\Ami\Providers\AmiServiceProvider::class,
]
```

### گام 3: انتشار فایل تنظیمات

```bash
php artisan vendor:publish --tag=ami
```

این دستور فایل `config/ami.php` را ایجاد می‌کند.

## 🔄 ارتقا از نسخه v1.x

اگر از نسخه قدیمی که از PHP 5.6+ و Laravel 5.1+ پشتیبانی می‌کرد، ارتقا می‌دهید:

### 1. به‌روزرسانی PHP و Laravel

```bash
# مطمئن شوید که PHP 8.0+ و Laravel 9.0+ دارید
php --version
php artisan --version
```

### 2. به‌روزرسانی پکیج

```bash
composer require shahkochaki/ami-laravel-asterisk-manager-interface
```

### 3. به‌روزرسانی کد شما

- `array_get()` helper را با `Arr::get()` جایگزین کنید
- syntax event listener را در صورت نیاز به‌روزرسانی کنید
- ویژگی‌های deprecated Laravel را بررسی کنید

### 4. تست کامل

```bash
php artisan ami:action Ping
```

## ⚙️ تنظیمات

### تنظیمات AMI در Asterisk

قبل از استفاده، باید یک کاربر AMI در Asterisk ایجاد کنید. فایل `/etc/asterisk/manager.conf` را ویرایش کنید:

```ini
[general]
enabled = yes
port = 5038
bindaddr = 0.0.0.0

[myuser]
secret = mypassword
read = all
write = all
```

پس از تغییرات، Asterisk را reload کنید:

```bash
asterisk -rx "manager reload"
```

### تنظیمات Laravel

```php
<?php

return [
    'host' => env('AMI_HOST', '127.0.0.1'),
    'port' => env('AMI_PORT', 5038),
    'username' => env('AMI_USERNAME', 'myuser'),
    'secret' => env('AMI_SECRET', 'mypassword'),

    'dongle' => [
        'sms' => [
            'device' => env('AMI_SMS_DEVICE', 'dongle0'),
        ],
    ],

    'events' => [
        // تنظیمات مدیریت رویدادها
        'Dial' => [
            // مدیریت‌کننده‌های سفارشی رویداد
        ],
        'Hangup' => [
            // مدیریت‌کننده‌های سفارشی رویداد
        ],
        // رویدادهای بیشتر...
    ],
];
```

### متغیرهای محیطی (.env)

```env
AMI_HOST=192.168.1.100
AMI_PORT=5038
AMI_USERNAME=myuser
AMI_SECRET=mypassword
AMI_SMS_DEVICE=dongle0
```

## 🎯 استفاده

### 🎧 گوش دادن به رویدادها

گوش دادن به تمام رویدادهای AMI:

```bash
php artisan ami:listen
```

با نمایش log در کنسول:

```bash
php artisan ami:listen --monitor
```

در کد PHP:

```php
use Illuminate\Support\Facades\Artisan;

Artisan::call('ami:listen');
```

### 📞 ارسال دستورات AMI

```bash
# مثال: وضعیت کانال‌ها
php artisan ami:action Status

# مثال: برقراری تماس
php artisan ami:action Originate --arguments=Channel:SIP/1001 --arguments=Context:default --arguments=Exten:1002 --arguments=Priority:1

# مثال: قطع تماس
php artisan ami:action Hangup --arguments=Channel:SIP/1001-00000001
```

در کد PHP:

```php
use Illuminate\Support\Facades\Artisan;

// دریافت وضعیت کانال‌ها
Artisan::call('ami:action', [
    'action' => 'Status'
]);

// برقراری تماس
Artisan::call('ami:action', [
    'action' => 'Originate',
    '--arguments' => [
        'Channel' => 'SIP/1001',
        'Context' => 'default',
        'Exten' => '1002',
        'Priority' => '1'
    ]
]);
```

### 📱 ارسال SMS

ارسال SMS معمولی:

```bash
php artisan ami:dongle:sms 09123456789 "سلام، این یک پیام تست است"
```

ارسال SMS طولانی (حالت PDU):

```bash
php artisan ami:dongle:sms 09123456789 "پیام طولانی..." --pdu
```

مشخص کردن دستگاه:

```bash
php artisan ami:dongle:sms 09123456789 "سلام" dongle1
```

در کد PHP:

```php
// ارسال SMS معمولی
Artisan::call('ami:dongle:sms', [
    'number' => '09123456789',
    'message' => 'سلام، این یک پیام تست است'
]);

// ارسال SMS طولانی
Artisan::call('ami:dongle:sms', [
    'number' => '09123456789',
    'message' => 'پیام طولانی...',
    '--pdu' => true
]);
```

### 🌐 ارسال دستورات USSD

```bash
php artisan ami:dongle:ussd dongle0 "*141#"
```

در کد PHP:

```php
Artisan::call('ami:dongle:ussd', [
    'device' => 'dongle0',
    'ussd' => '*141#'
]);
```

### 💻 رابط خط فرمان تعاملی

شروع رابط CLI:

```bash
php artisan ami:cli
```

اجرای دستور و بستن خودکار:

```bash
php artisan ami:cli "core show channels" --autoclose
```

### 🔧 استفاده بدون Laravel

```bash
php ./vendor/bin/ami ami:listen --host=192.168.1.100 --port=5038 --username=myuser --secret=mypass --monitor
```

## 📚 مثال‌های پیشرفته

### مدیریت رویدادها

```php
// در یک Service Provider یا Event Listener
use Illuminate\Support\Facades\Event;

Event::listen('ami.event.dial', function ($event) {
    Log::info('تماس جدید شروع شد', [
        'caller' => $event['CallerIDNum'],
        'destination' => $event['Destination']
    ]);
});

Event::listen('ami.event.hangup', function ($event) {
    Log::info('تماس پایان یافت', [
        'channel' => $event['Channel'],
        'cause' => $event['Cause']
    ]);
});
```

### ایجاد کلاس سفارشی برای مدیریت تماس‌ها

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;

class CallManager
{
    public function makeCall($from, $to, $context = 'default')
    {
        return Artisan::call('ami:action', [
            'action' => 'Originate',
            '--arguments' => [
                'Channel' => "SIP/{$from}",
                'Context' => $context,
                'Exten' => $to,
                'Priority' => '1',
                'CallerID' => $from
            ]
        ]);
    }

    public function hangupCall($channel)
    {
        return Artisan::call('ami:action', [
            'action' => 'Hangup',
            '--arguments' => [
                'Channel' => $channel
            ]
        ]);
    }

    public function getChannelStatus()
    {
        return Artisan::call('ami:action', [
            'action' => 'Status'
        ]);
    }
}
```

### سرویس ارسال SMS انبوه

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Collection;

class BulkSmsService
{
    protected $device;

    public function __construct($device = null)
    {
        $this->device = $device ?: config('ami.dongle.sms.device');
    }

    public function sendBulkSms(Collection $recipients, string $message)
    {
        $results = [];

        foreach ($recipients as $number) {
            try {
                $result = Artisan::call('ami:dongle:sms', [
                    'number' => $number,
                    'message' => $message,
                    'device' => $this->device,
                    '--pdu' => strlen($message) > 160
                ]);

                $results[$number] = ['status' => 'success', 'result' => $result];

                // تأخیر کوتاه بین ارسال پیام‌ها
                usleep(500000); // نیم ثانیه

            } catch (\Exception $e) {
                $results[$number] = ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        return $results;
    }
}
```

## 🔍 رویدادهای پشتیبانی شده

کتابخانه از تمام رویدادهای استاندارد Asterisk پشتیبانی می‌کند:

- **رویدادهای تماس**: `Dial`, `Hangup`, `NewChannel`, `Bridge`
- **رویدادهای اپراتور**: `AgentConnect`, `AgentComplete`, `AgentLogin`, `AgentLogoff`
- **رویدادهای صف**: `QueueMember`, `QueueParams`, `QueueSummary`
- **رویدادهای Dongle**: `DongleDeviceEntry`, `DongleSMSStatus`, `DongleUSSDStatus`
- **رویدادهای سیستم**: `Reload`, `Shutdown`, `PeerStatus`

## 🐛 عیب‌یابی

### مشکلات رایج

1. **خطای اتصال**:

   ```
   Connection refused
   ```

   - بررسی کنید که Asterisk در حال اجرا باشد
   - پورت 5038 باز باشد
   - تنظیمات فایروال را بررسی کنید

2. **خطای احراز هویت**:

   ```
   Authentication failed
   ```

   - نام کاربری و رمز عبور را بررسی کنید
   - دسترسی‌های کاربر AMI را بررسی کنید

3. **مشکل ارسال SMS**:
   ```
   Device not found
   ```
   - وضعیت Chan Dongle را بررسی کنید: `dongle show devices`
   - نام دستگاه را درست وارد کرده‌اید

### لاگ‌ها

برای فعال‌سازی لاگ‌های تفصیلی:

```bash
php artisan ami:listen --monitor
```

### تست اتصال

```bash
# تست ساده اتصال
php artisan ami:action Ping

# بررسی وضعیت دستگاه‌های dongle
php artisan ami:action Command --arguments=Command:"dongle show devices"
```

## 🔧 توسعه

### اجرای تست‌ها

```bash
composer test
```

### استانداردهای کد

```bash
composer phpcs
```

### ساختار پروژه

```
src/
├── Commands/          # دستورات Artisan
│   ├── AmiAbstract.php
│   ├── AmiAction.php
│   ├── AmiCli.php
│   ├── AmiListen.php
│   ├── AmiSms.php
│   └── AmiUssd.php
├── Providers/         # Service providers
│   └── AmiServiceProvider.php
├── Factory.php        # کارخانه اتصال AMI
└── Parser.php         # تجزیه‌کننده پروتکل AMI

config/
└── ami.php           # فایل تنظیمات

tests/                # فایل‌های تست
└── ...
```

## 🤝 مشارکت

از مشارکت شما استقبال می‌کنیم! لطفاً:

1. پروژه را Fork کنید
2. یک branch جدید ایجاد کنید (`git checkout -b feature/amazing-feature`)
3. تغییرات را commit کنید (`git commit -m 'Add amazing feature'`)
4. به branch خود push کنید (`git push origin feature/amazing-feature`)
5. یک Pull Request ایجاد کنید

### راهنمای توسعه

- از PSR-4 autoloading استفاده کنید
- تست‌ها را اجرا کنید قبل از commit
- PHPDoc مناسب اضافه کنید
- از استانداردهای Laravel پیروی کنید

## 📄 مجوز

این پروژه تحت مجوز [MIT License](LICENSE.md) منتشر شده است.

## 👨‍💻 سازنده

**Ali Shahkochaki**

- وب‌سایت: [shahkochaki.ir](https://shahkochaki.ir)
- ایمیل: ali.shahkochaki7@gmail.com
- گیت‌هاب: [@shahkochaki](https://github.com/shahkochaki)

## 🙏 تشکر

- [ReactPHP](https://reactphp.org/) برای event loop
- [clue/ami-react](https://github.com/clue/reactphp-ami) برای پروتکل AMI
- جامعه Laravel برای فریمورک فوق‌العاده

## 📚 منابع مفید

- [Asterisk Manager Interface](https://wiki.asterisk.org/wiki/display/AST/The+Asterisk+Manager+TCP+IP+API)
- [مستندات Chan Dongle](https://github.com/bg111/asterisk-chan-dongle)
- [مستندات Issabel](https://www.issabel.org/documentation/)

---

⭐ اگر این پروژه برایتان مفید بود، لطفاً ستاره بدهید!

**Made with ❤️ for Iranian developers**
'secret' => env('AMI_SECRET', 'mypassword'),

    'dongle' => [
        'sms' => [
            'device' => env('AMI_SMS_DEVICE', 'dongle0'),
        ],
    ],

    'events' => [
        // Event handlers configuration
        'Dial' => [
            // Custom event handlers
        ],
        'Hangup' => [
            // Custom event handlers
        ],
        // More events...
    ],

];

````

### متغیرهای محیطی (.env)

```env
AMI_HOST=192.168.1.100
AMI_PORT=5038
AMI_USERNAME=myuser
AMI_SECRET=mypassword
AMI_SMS_DEVICE=dongle0
````

## 🎯 استفاده / Usage

### 🎧 گوش دادن به رویدادها / Event Listening

گوش دادن به تمام رویدادهای AMI:

```bash
php artisan ami:listen
```

با نمایش log در کنسول:

```bash
php artisan ami:listen --monitor
```

در کد PHP:

```php
use Illuminate\Support\Facades\Artisan;

Artisan::call('ami:listen');
```

### 📞 ارسال دستورات AMI / Sending AMI Actions

```bash
# مثال: وضعیت کانال‌ها
php artisan ami:action Status

# مثال: برقراری تماس
php artisan ami:action Originate --arguments=Channel:SIP/1001 --arguments=Context:default --arguments=Exten:1002 --arguments=Priority:1

# مثال: قطع تماس
php artisan ami:action Hangup --arguments=Channel:SIP/1001-00000001
```

در کد PHP:

```php
use Illuminate\Support\Facades\Artisan;

// دریافت وضعیت کانال‌ها
Artisan::call('ami:action', [
    'action' => 'Status'
]);

// برقراری تماس
Artisan::call('ami:action', [
    'action' => 'Originate',
    '--arguments' => [
        'Channel' => 'SIP/1001',
        'Context' => 'default',
        'Exten' => '1002',
        'Priority' => '1'
    ]
]);
```

### 📱 ارسال SMS / Sending SMS

ارسال SMS معمولی:

```bash
php artisan ami:dongle:sms 09123456789 "سلام، این یک پیام تست است"
```

ارسال SMS طولانی (PDU mode):

```bash
php artisan ami:dongle:sms 09123456789 "پیام طولانی..." --pdu
```

مشخص کردن دستگاه:

```bash
php artisan ami:dongle:sms 09123456789 "سلام" dongle1
```

در کد PHP:

```php
// ارسال SMS معمولی
Artisan::call('ami:dongle:sms', [
    'number' => '09123456789',
    'message' => 'سلام، این یک پیام تست است'
]);

// ارسال SMS طولانی
Artisan::call('ami:dongle:sms', [
    'number' => '09123456789',
    'message' => 'پیام طولانی...',
    '--pdu' => true
]);
```

### 🌐 ارسال دستورات USSD / USSD Commands

```bash
php artisan ami:dongle:ussd dongle0 "*141#"
```

در کد PHP:

```php
Artisan::call('ami:dongle:ussd', [
    'device' => 'dongle0',
    'ussd' => '*141#'
]);
```

### 💻 رابط خط فرمان تعاملی / Interactive CLI

شروع رابط CLI:

```bash
php artisan ami:cli
```

اجرای دستور و بستن خودکار:

```bash
php artisan ami:cli "core show channels" --autoclose
```

### 🔧 استفاده بدون Laravel / Without Laravel

```bash
php ./vendor/bin/ami ami:listen --host=192.168.1.100 --port=5038 --username=myuser --secret=mypass --monitor
```

### 🖥️ مدیریت سیستم / System Management

**جدید!** امکان کنترل کامل سرور Asterisk/Issabel:

#### خاموش کردن و ریست سرور / Server Shutdown & Restart

```bash
# خاموش کردن تدریجی سرور
php artisan ami:system shutdown --graceful

# ریست فوری سرور
php artisan ami:system restart --force

# دریافت وضعیت سرور
php artisan ami:system status

# بارگیری مجدد تنظیمات
php artisan ami:system reload --module=sip
```

#### استفاده از SystemManager Service

```php
use Shahkochaki\Ami\Services\SystemManager;

$systemManager = new SystemManager([
    'host' => 'localhost',
    'port' => 5038,
    'username' => 'admin',
    'secret' => 'amp111'
]);

// خاموش کردن تدریجی
$systemManager->shutdownServer(true, 'System maintenance');

// ریست فوری
$systemManager->restartServer(false, 'Emergency restart');

// بارگیری مجدد تنظیمات SIP
$systemManager->reloadConfiguration('sip');

// دریافت وضعیت کامل سرور
$status = $systemManager->getServerStatus();

// برنامه‌ریزی ریست برای 30 دقیقه آینده
$schedule = $systemManager->scheduleRestart(30, true, 'Scheduled maintenance');
```

#### استفاده از Facade

```php
use Shahkochaki\Ami\Facades\SystemManager;

// خاموش کردن تدریجی
SystemManager::shutdownServer(true, 'Scheduled maintenance');

// ریست اضطراری
SystemManager::emergencyRestart();

// دریافت کانال‌های فعال
$channels = SystemManager::getActiveChannels();

// نظارت بر منابع سیستم
$resources = SystemManager::getSystemResources();
```

#### عملیات برنامه‌ریزی شده با Queue

```php
use Shahkochaki\Ami\Jobs\SystemManagementJob;

// برنامه‌ریزی ریست برای 1 ساعت آینده
SystemManagementJob::scheduleRestart(60, true, 'Nightly maintenance');

// برنامه‌ریزی خاموش کردن برای 2 ساعت آینده
SystemManagementJob::scheduleShutdown(120, true, 'End of business hours');

// برنامه‌ریزی بارگیری مجدد تنظیمات
SystemManagementJob::scheduleReload(30, 'dialplan');
```

## 📚 مثال‌های پیشرفته / Advanced Examples

### مدیریت رویدادها

```php
// در یک Service Provider یا Event Listener
use Illuminate\Support\Facades\Event;

Event::listen('ami.event.dial', function ($event) {
    Log::info('New call started', [
        'caller' => $event['CallerIDNum'],
        'destination' => $event['Destination']
    ]);
});

Event::listen('ami.event.hangup', function ($event) {
    Log::info('Call ended', [
        'channel' => $event['Channel'],
        'cause' => $event['Cause']
    ]);
});
```

### ایجاد کلاس سفارشی برای مدیریت تماس‌ها

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;

class CallManager
{
    public function makeCall($from, $to, $context = 'default')
    {
        return Artisan::call('ami:action', [
            'action' => 'Originate',
            '--arguments' => [
                'Channel' => "SIP/{$from}",
                'Context' => $context,
                'Exten' => $to,
                'Priority' => '1',
                'CallerID' => $from
            ]
        ]);
    }

    public function hangupCall($channel)
    {
        return Artisan::call('ami:action', [
            'action' => 'Hangup',
            '--arguments' => [
                'Channel' => $channel
            ]
        ]);
    }

    public function getChannelStatus()
    {
        return Artisan::call('ami:action', [
            'action' => 'Status'
        ]);
    }
}
```

### سرویس ارسال SMS انبوه

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Collection;

class BulkSmsService
{
    protected $device;

    public function __construct($device = null)
    {
        $this->device = $device ?: config('ami.dongle.sms.device');
    }

    public function sendBulkSms(Collection $recipients, string $message)
    {
        $results = [];

        foreach ($recipients as $number) {
            try {
                $result = Artisan::call('ami:dongle:sms', [
                    'number' => $number,
                    'message' => $message,
                    'device' => $this->device,
                    '--pdu' => strlen($message) > 160
                ]);

                $results[$number] = ['status' => 'success', 'result' => $result];

                // تأخیر کوتاه بین ارسال پیام‌ها
                usleep(500000); // 0.5 second

            } catch (\Exception $e) {
                $results[$number] = ['status' => 'error', 'message' => $e->getMessage()];
            }
        }

        return $results;
    }
}
```

### سرویس مدیریت سیستم پیشرفته

```php
<?php

namespace App\Services;

use Shahkochaki\Ami\Services\SystemManager;
use Illuminate\Support\Facades\Log;

class AdvancedSystemManager
{
    protected $systemManager;

    public function __construct()
    {
        $this->systemManager = new SystemManager();
    }

    /**
     * بررسی سلامت سیستم و اقدام در صورت نیاز
     */
    public function performHealthCheck()
    {
        $status = $this->systemManager->getServerStatus();
        $resources = $this->systemManager->getSystemResources();
        $channels = $this->systemManager->getActiveChannels();

        $issues = [];

        // بررسی خطاها
        if (isset($status['error'])) {
            $issues[] = 'Server status error: ' . $status['error'];
        }

        // بررسی مصرف بالای کانال‌ها
        $channelCount = is_array($channels) ? count($channels) : 0;
        if ($channelCount > 100) {
            $issues[] = "High channel usage: {$channelCount} active channels";
        }

        // لاگ مشکلات
        if (!empty($issues)) {
            Log::warning('System health issues detected', $issues);

            // ارسال اعلان یا اقدام خودکار
            $this->handleHealthIssues($issues);
        }

        return [
            'healthy' => empty($issues),
            'issues' => $issues,
            'channel_count' => $channelCount,
            'timestamp' => now()
        ];
    }

    /**
     * خاموش کردن امن با بررسی شرایط
     */
    public function safeShutdown($reason = 'System maintenance')
    {
        // بررسی کانال‌های فعال
        $channels = $this->systemManager->getActiveChannels();

        if (empty($channels)) {
            Log::info('No active calls, proceeding with immediate shutdown');
            return $this->systemManager->shutdownServer(false, $reason);
        } else {
            Log::info('Active calls detected, using graceful shutdown', [
                'active_channels' => count($channels)
            ]);
            return $this->systemManager->shutdownServer(true, $reason);
        }
    }

    /**
     * مدیریت مشکلات سلامت سیستم
     */
    protected function handleHealthIssues(array $issues)
    {
        foreach ($issues as $issue) {
            if (str_contains($issue, 'High channel usage')) {
                // اقدام برای کاهش بار
                Log::warning('Implementing load reduction measures');
                // می‌توانید اینجا اقدامات خاصی انجام دهید
            }
        }
    }
}
```

## 🔍 رویدادهای پشتیبانی شده / Supported Events

کتابخانه از تمام رویدادهای استاندارد Asterisk پشتیبانی می‌کند:

- **Call Events**: `Dial`, `Hangup`, `NewChannel`, `Bridge`
- **Agent Events**: `AgentConnect`, `AgentComplete`, `AgentLogin`, `AgentLogoff`
- **Queue Events**: `QueueMember`, `QueueParams`, `QueueSummary`
- **Dongle Events**: `DongleDeviceEntry`, `DongleSMSStatus`, `DongleUSSDStatus`
- **System Events**: `Reload`, `Shutdown`, `PeerStatus`
- **Management Events**: `ami.system.operation.sent`, `ami.system.operation.completed`

## 🐛 عیب‌یابی / Troubleshooting

### مشکلات رایج

1. **خطای اتصال**:

   ```
   Connection refused
   ```

   - بررسی کنید که Asterisk در حال اجرا باشد
   - پورت 5038 باز باشد
   - تنظیمات فایروال

2. **خطای احراز هویت**:

   ```
   Authentication failed
   ```

   - نام کاربری و رمز عبور را بررسی کنید
   - دسترسی‌های کاربر AMI را بررسی کنید

3. **مشکل ارسال SMS**:
   ```
   Device not found
   ```
   - وضعیت Chan Dongle را بررسی کنید: `dongle show devices`
   - نام دستگاه را درست وارد کرده‌اید

### لاگ‌ها

برای فعال‌سازی لاگ‌های تفصیلی:

```bash
php artisan ami:listen --monitor
```

### تست اتصال

```bash
# تست ساده اتصال
php artisan ami:action Ping

# بررسی وضعیت دستگاه‌های dongle
php artisan ami:action Command --arguments=Command:"dongle show devices"
```

## 🔧 توسعه / Development

### اجرای تست‌ها

```bash
composer test
```

### استانداردهای کد

```bash
composer phpcs
```

### ساختار پروژه

```
src/
├── Commands/          # Artisan commands
│   ├── AmiAbstract.php
│   ├── AmiAction.php
│   ├── AmiCli.php
│   ├── AmiListen.php
│   ├── AmiSms.php
│   ├── AmiUssd.php
│   └── AmiSystemControl.php   # NEW: System management command
├── Services/          # Service classes
│   ├── BulkSmsService.php
│   ├── CallManager.php
│   └── SystemManager.php      # NEW: System management service
├── Jobs/              # Queue jobs
│   ├── BulkSmsJob.php
│   └── SystemManagementJob.php # NEW: Scheduled system operations
├── Facades/           # Laravel facades
│   ├── Ami.php
│   └── SystemManager.php      # NEW: System management facade
├── Providers/         # Service providers
│   └── AmiServiceProvider.php
├── Factory.php        # AMI connection factory
└── Parser.php         # AMI protocol parser

config/
└── ami.php           # Configuration file

docs/                 # Documentation
├── SYSTEM_MANAGEMENT.md    # NEW: System management guide
└── ...

examples/             # Usage examples
├── system_management_examples.php  # NEW: System management examples
└── ...

tests/                # Test files
└── ...
```

## 🤝 مشارکت / Contributing

از مشارکت شما استقبال می‌کنیم! لطفاً:

1. Fork کنید
2. یک branch جدید ایجاد کنید (`git checkout -b feature/amazing-feature`)
3. تغییرات را commit کنید (`git commit -m 'Add amazing feature'`)
4. به branch خود push کنید (`git push origin feature/amazing-feature`)
5. یک Pull Request ایجاد کنید

### راهنمای توسعه

- از PSR-4 autoloading استفاده کنید
- تست‌ها را اجرا کنید قبل از commit
- PHPDoc مناسب اضافه کنید
- از استانداردهای Laravel پیروی کنید

## 📄 مجوز / License

این پروژه تحت مجوز [MIT License](LICENSE.md) منتشر شده است.

## 👨‍💻 سازنده / Author

**Ali Shahkochaki**

- Website: [shahkochaki.ir](https://shahkochaki.ir)
- Email: ali.shahkochaki7@gmail.com
- GitHub: [@shahkochaki](https://github.com/shahkochaki)

## 🙏 تشکر / Acknowledgments

- [ReactPHP](https://reactphp.org/) برای event loop
- [clue/ami-react](https://github.com/clue/reactphp-ami) برای AMI protocol
- جامعه Laravel برای فریمورک عالی

## 📚 منابع مفید / Useful Resources

- [Asterisk Manager Interface](https://wiki.asterisk.org/wiki/display/AST/The+Asterisk+Manager+TCP+IP+API)
- [Chan Dongle Documentation](https://github.com/bg111/asterisk-chan-dongle)
- [Issabel Documentation](https://www.issabel.org/documentation/)
- [System Management Guide](docs/SYSTEM_MANAGEMENT.md) - راهنمای کامل مدیریت سیستم
- [System Management Examples](examples/system_management_examples.php) - مثال‌های عملی
- [Troubleshooting Guide](docs/TROUBLESHOOTING.md) - راهنمای عیب‌یابی و حل مشکلات

## 🆕 What's New in v2.2.5

### 🐛 Critical Fixes
- **✅ Docker Compatibility**: Fixed console command execution in Docker environments
- **✅ Composer Issues**: Resolved patch application failures during Docker builds  
- **✅ Production Stability**: Enhanced error handling and class loading
- **✅ Console Commands**: Fixed OutputStyle class resolution issues

### 🚀 Enhancements
- **🔧 Docker Support**: Added `.dockerignore` template and build optimizations
- **📚 Documentation**: Updated with Docker best practices and troubleshooting
- **🛡️ Error Handling**: Improved exception handling in command execution
- **⚡ Performance**: Optimized dependency loading and command initialization

### 📦 Quick Update
```bash
composer update shahkochaki/ami-laravel-asterisk-manager-interface
```

## 🆕 Previous Features - System Management (v2.1+)

### Core System Management
- ✅ **SystemManager Service**: Complete control of Asterisk/Issabel server
- ✅ **System Commands**: CLI commands for system management
- ✅ **Scheduled Operations**: Queue-based scheduled operations
- ✅ **Health Monitoring**: System health and resource monitoring
- ✅ **Safe Operations**: Safe operations with condition checking
- ✅ **Event Integration**: Integration with Laravel event system

### امکانات جدید:

```php
// خاموش کردن و ریست سرور
SystemManager::shutdownServer(true, 'Maintenance');
SystemManager::restartServer(false, 'Emergency');

// نظارت بر سیستم
$status = SystemManager::getServerStatus();
$resources = SystemManager::getSystemResources();

// عملیات برنامه‌ریزی شده
SystemManagementJob::scheduleRestart(60, true, 'Nightly restart');
```

### دستورات جدید CLI:

```bash
php artisan ami:system shutdown --graceful
php artisan ami:system restart --force
php artisan ami:system reload --module=sip
php artisan ami:system status
```

## 📈 Release History

| Version | Date       | Key Features                                    |
| ------- | ---------- | ----------------------------------------------- |
| v2.2.5  | 2025-11-10 | 🐛 Docker fixes, Console command improvements   |
| v2.2.4  | 2025-10-20 | 🔧 React Socket API compatibility              |
| v2.2.3  | 2025-09-15 | 🚀 Performance improvements                    |
| v2.1.x  | 2025-08-xx | 🖥️ System Management features                  |
| v2.0.x  | 2025-06-xx | 🎯 Modern PHP 8.0+ support                     |

## 🎯 Quick Links

- **📦 Packagist**: [shahkochaki/ami-laravel-asterisk-manager-interface](https://packagist.org/packages/shahkochaki/ami-laravel-asterisk-manager-interface)
- **📋 Changelog**: [CHANGELOG.md](CHANGELOG.md)
- **📄 Release Notes**: [Latest Release](RELEASE_NOTES_v2.2.5.md)
- **🐛 Issues**: [GitHub Issues](https://github.com/shahkochaki/ami-laravel-asterisk-manager-interface/issues)
- **💡 Discussions**: [GitHub Discussions](https://github.com/shahkochaki/ami-laravel-asterisk-manager-interface/discussions)

---

⭐ اگر این پروژه برایتان مفید بود، لطفاً ستاره بدهید!

**Made with ❤️ for Iranian developers and global PHP community**
