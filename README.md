# 📞 AMI - Laravel Asterisk Manager Interface

[![Latest Version on Packagist](https://img.shields.io/packagist/v/shahkochaki/ami.svg?style=flat-square)](https://packagist.org/packages/shahkochaki/ami)
[![Total Downloads](https://img.shields.io/packagist/dt/shahkochaki/ami.svg?style=flat-square)](https://packagist.org/packages/shahkochaki/ami)
[![License](https://img.shields.io/packagist/l/shahkochaki/ami.svg?style=flat-square)](https://packagist.org/packages/shahkochaki/ami)
[![PHP Version](https://img.shields.io/packagist/php-v/shahkochaki/ami.svg?style=flat-square)](https://packagist.org/packages/shahkochaki/ami)

یک کتابخانه قدرتمند و آسان برای اتصال سرورهای Laravel به سرورهای VOIP بر روی پلتفرم Issabel و Asterisk از طریق Asterisk Manager Interface (AMI).

A powerful and easy-to-use Laravel package for connecting to VOIP servers on the Issabel and Asterisk platform via Asterisk Manager Interface (AMI).

## ✨ امکانات اصلی / Key Features

- 🔗 **اتصال آسان به AMI** - Easy AMI connection management
- 📱 **ارسال SMS** - Send SMS via Chan Dongle
- 📞 **کنترل تماس‌ها** - Call control and monitoring
- 🎧 **مدیریت صف تماس** - Queue management
- 📊 **مانیتورینگ real-time** - Real-time event monitoring
- 🔧 **دستورات CLI** - CLI commands for easy management
- 📋 **رابط کاربری CLI** - Interactive CLI interface
- 🌐 **پشتیبانی از USSD** - USSD command support
- ⚡ **Async Processing** - Asynchronous event handling با ReactPHP
- 🔒 **امنیت بالا** - Secure authentication and connection management

## 📋 پیش‌نیازها / Requirements

- PHP >= 5.6.0
- Laravel >= 5.1
- Asterisk/Issabel server with AMI enabled
- Chan Dongle (برای SMS و USSD)
- Extension `ext-mbstring`

## 🚀 نصب / Installation

### گام 1: نصب از طریق Composer

```bash
composer require shahkochaki/ami
```

یا برای آخرین نسخه توسعه:

```bash
composer require shahkochaki/ami:dev-master
```

### گام 2: ثبت Service Provider

در فایل `config/app.php` در آرایه `providers` اضافه کنید:

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

## ⚙️ تنظیمات / Configuration

### تنظیمات AMI در Asterisk

قبل از استفاده، باید یک کاربر AMI در Asterisk ایجاد کنید. فایل `/etc/asterisk/manager.conf`:

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

فایل `config/ami.php` را ویرایش کنید:

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

### متغیرهای محیطی (.env)

```env
AMI_HOST=192.168.1.100
AMI_PORT=5038
AMI_USERNAME=myuser
AMI_SECRET=mypassword
AMI_SMS_DEVICE=dongle0
```

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

## 🔍 رویدادهای پشتیبانی شده / Supported Events

کتابخانه از تمام رویدادهای استاندارد Asterisk پشتیبانی می‌کند:

- **Call Events**: `Dial`, `Hangup`, `NewChannel`, `Bridge`
- **Agent Events**: `AgentConnect`, `AgentComplete`, `AgentLogin`, `AgentLogoff`
- **Queue Events**: `QueueMember`, `QueueParams`, `QueueSummary`
- **Dongle Events**: `DongleDeviceEntry`, `DongleSMSStatus`, `DongleUSSDStatus`
- **System Events**: `Reload`, `Shutdown`, `PeerStatus`

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

---

⭐ اگر این پروژه برایتان مفید بود، لطفاً ستاره بدهید!

**Made with ❤️ for Iranian developers**

## Installation and configuration

You must create your own [Ami User](https://www.asteriskguru.com/tutorials/manager_conf.html) before installation

Also check that port 5038 (default-changeable) is available.

Make sure that the extension socket is active.

To install as a [composer](https://getcomposer.org/) package to be used with Laravel 5 and above, simply run:

```sh
composer require "shahkochaki/ami" or composer require "shahkochaki/ami:dev-master"
```

Once it's installed, you can register the service provider in `config/app.php` in the `providers` array:

```php
'providers' => [
  \shahkochaki\Ami\Providers\AmiServiceProvider::class,
]
```

Then publish assets with `php artisan vendor:publish`. This will add the file `config/ami.php`.

## Usage

**Connection options**

You are can specify connection parameters for each command.

| Option     | Description                               |
| ---------- | ----------------------------------------- |
| --host     | Asterisk AMI server host                  |
| --port     | Asterisk AMI server port                  |
| --username | Asterisk AMI server username              |
| --secret   | Asterisk AMI server secret                |
| --range    | Default number range, for example 100,300 |

**Listen ami events**

```sh
php artisan ami:listen
```

```php
Artisan::call('ami:listen');
```

If would you like to see event log in the console use _monitor_ option

```sh
php artisan ami:listen --monitor
```

**Send ami action**

```sh
php artisan ami:action <action> --arguments=<key>:<value> --arguments=<key>:<value> ...
```

```php
Artisan::call('ami:action', [
    'action'      => <action>,
    '--arguments' => [
        <key> => <value>
        ...
    ]
]);
```

**Send sms messages using chan dongle**

```sh
php artisan ami:dongle:sms <phone> <message> <device?>
```

```php
Artisan::call('ami:dongle:sms', [
    'phone'   => <phone>,
    'message' => <message>,
    'device'  => <device?>,
]);
```

For sending long messages use _pdu_ mode.

```sh
php artisan ami:dongle:sms <phone> <message> <device?> --pdu
```

```php
Artisan::call('ami:dongle:sms', [
    'phone'   => <phone>,
    'message' => <message>,
    'device'  => <device?>,
    '--pdu'   => true,
]);
```

Argument device is not required.

**Send ussd commands using chan dongle**

```sh
php artisan ami:dongle:ussd <device> <ussd>
```

```php
Artisan::call('ami:dongle:ussd', [
    'device' => <device>,
    'ussd'   => <ussd>,
]);
```

**Send ami commands**

This command started cli interface for ami. Command attribute is optional.

```sh
php artisan ami:cli [command]
```

Close cli interface after sending command.

```sh
php artisan ami:cli [command] --autoclose
```

```php
Artisan::call('ami:cli', [
    'command'     => [command],
    '--autoclose' => true,
]);
```

**Without Laravel**

```sh
php ./vendor/bin/ami ami:listen --host=127.0.0.1 --port=5038 --username=username --secret=secret --monitor
```
