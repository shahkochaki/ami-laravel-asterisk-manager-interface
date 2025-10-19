<?php

/**
 * مثال‌های استفاده از SystemManager برای مدیریت سرور Asterisk/Issabel
 * 
 * این فایل نحوه استفاده از توابع مختلف SystemManager را نشان می‌دهد
 */

require_once 'vendor/autoload.php';

use Shahkochaki\Ami\Services\SystemManager;
use Shahkochaki\Ami\Facades\SystemManager as SystemManagerFacade;

// ==============================================
// مثال 1: استفاده مستقیم از Service
// ==============================================

echo "=== استفاده مستقیم از SystemManager Service ===\n";

// ایجاد instance با تنظیمات اتصال
$systemManager = new SystemManager([
    'host' => 'localhost',
    'port' => 5038,
    'username' => 'admin',
    'secret' => 'amp111'
]);

// دریافت وضعیت سرور
echo "دریافت وضعیت سرور...\n";
$status = $systemManager->getServerStatus();
print_r($status);

// دریافت کانال‌های فعال
echo "\nدریافت کانال‌های فعال...\n";
$channels = $systemManager->getActiveChannels();
print_r($channels);

// ==============================================
// مثال 2: بارگیری مجدد تنظیمات
// ==============================================

echo "\n=== بارگیری مجدد تنظیمات ===\n";

// بارگیری مجدد تنظیمات SIP
echo "بارگیری مجدد تنظیمات SIP...\n";
$result = $systemManager->reloadConfiguration('sip');
echo "نتیجه: " . ($result ? "موفق" : "ناموفق") . "\n";

// بارگیری مجدد کل تنظیمات
echo "بارگیری مجدد کل تنظیمات...\n";
$result = $systemManager->reloadConfiguration();
echo "نتیجه: " . ($result ? "موفق" : "ناموفق") . "\n";

// ==============================================
// مثال 3: عملیات شرطی بر اساس وضعیت سیستم
// ==============================================

echo "\n=== عملیات شرطی ===\n";

// بررسی وجود تماس‌های فعال قبل از خاموش کردن
$channels = $systemManager->getActiveChannels();

if (empty($channels)) {
    echo "هیچ تماس فعالی وجود ندارد. امکان خاموش کردن فوری...\n";
    // $systemManager->shutdownServer(false, 'No active calls');
    echo "خاموش کردن فوری اجرا خواهد شد.\n";
} else {
    echo "تماس‌های فعال شناسایی شدند. خاموش کردن تدریجی...\n";
    // $systemManager->shutdownServer(true, 'Active calls detected');
    echo "خاموش کردن تدریجی اجرا خواهد شد.\n";
}

// ==============================================
// مثال 4: برنامه‌ریزی عملیات
// ==============================================

echo "\n=== برنامه‌ریزی عملیات ===\n";

// برنامه‌ریزی ریست برای 30 دقیقه آینده
$restartSchedule = $systemManager->scheduleRestart(30, true, 'Scheduled maintenance');
echo "برنامه‌ریزی ریست:\n";
print_r($restartSchedule);

// برنامه‌ریزی خاموش کردن برای 60 دقیقه آینده
$shutdownSchedule = $systemManager->scheduleShutdown(60, true, 'End of business hours');
echo "\nبرنامه‌ریزی خاموش کردن:\n";
print_r($shutdownSchedule);

// ==============================================
// مثال 5: نظارت بر منابع سیستم
// ==============================================

echo "\n=== نظارت بر منابع سیستم ===\n";

$resources = $systemManager->getSystemResources();
echo "منابع سیستم:\n";
print_r($resources);

// ==============================================
// مثال 6: استفاده از Facade (در محیط Laravel)
// ==============================================

echo "\n=== استفاده از Facade ===\n";

// نکته: این بخش فقط در محیط Laravel کار می‌کند
/*
// دریافت وضعیت با Facade
$status = SystemManagerFacade::getServerStatus();

// ریست اضطراری
SystemManagerFacade::emergencyRestart();

// خاموش کردن تدریجی
SystemManagerFacade::shutdownServer(true, 'Maintenance required');
*/

echo "توابع Facade در محیط Laravel قابل استفاده هستند.\n";

// ==============================================
// مثال 7: مدیریت خطاها
// ==============================================

echo "\n=== مدیریت خطاها ===\n";

try {
    // تلاش برای اتصال با تنظیمات نادرست
    $wrongSystemManager = new SystemManager([
        'host' => 'wrong-host',
        'port' => 9999,
        'username' => 'wrong-user',
        'secret' => 'wrong-pass'
    ]);

    $status = $wrongSystemManager->getServerStatus();
} catch (Exception $e) {
    echo "خطا در اتصال: " . $e->getMessage() . "\n";
    echo "لطفاً تنظیمات اتصال را بررسی کنید.\n";
}

// ==============================================
// مثال 8: چک کردن سلامت سیستم
// ==============================================

echo "\n=== چک سلامت سیستم ===\n";

function checkSystemHealth($systemManager)
{
    echo "بررسی سلامت سیستم...\n";

    // دریافت وضعیت
    $status = $systemManager->getServerStatus();

    if (isset($status['error'])) {
        echo "❌ خطا در دریافت وضعیت سیستم\n";
        return false;
    }

    // بررسی کانال‌های فعال
    $channels = $systemManager->getActiveChannels();
    $channelCount = is_array($channels) ? count($channels) : 0;
    echo "📞 تعداد کانال‌های فعال: {$channelCount}\n";

    // بررسی منابع
    $resources = $systemManager->getSystemResources();
    if (!isset($resources['error'])) {
        echo "💾 وضعیت منابع: سالم\n";
    } else {
        echo "⚠️ مشکل در دریافت اطلاعات منابع\n";
    }

    echo "✅ سیستم در وضعیت مطلوب است\n";
    return true;
}

// اجرای چک سلامت
$isHealthy = checkSystemHealth($systemManager);

if (!$isHealthy) {
    echo "سیستم نیاز به توجه دارد.\n";
}

echo "\n=== پایان مثال‌ها ===\n";
echo "برای اطلاعات بیشتر، فایل docs/SYSTEM_MANAGEMENT.md را مطالعه کنید.\n";
