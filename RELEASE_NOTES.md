# Release Notes - AMI v2.0.1

🎉 **Major Release**: Modernized for PHP 8+ and Laravel 9+

## 🚀 What's New in v2.0.1

### 🔥 Breaking Changes
- **PHP 8.0+ Required**: Upgraded from PHP 5.6+
- **Laravel 9.0+ Required**: Upgraded from Laravel 5.1+
- **Modern Dependencies**: Updated all packages to latest versions

### ✨ New Features

#### 🏗️ **Enhanced Architecture**
- **Service Classes**: New `CallManager` and `BulkSmsService` classes
- **Connection Pooling**: Advanced connection management with health monitoring
- **Laravel Facade**: Easy access via `Ami::makeCall()`, `Ami::sendSms()`
- **Auto-Discovery**: Service providers automatically registered in Laravel 9+

#### 📱 **Advanced SMS Management**
```php
// Bulk SMS with retry logic
$smsService = new BulkSmsService();
$results = $smsService->sendBulkSms($recipients, $message, [
    'device' => 'dongle0',
    'max_retries' => 3,
    'delay' => 500
]);
```

#### 📞 **Enhanced Call Control**
```php
// Advanced call management
$callManager = new CallManager();
$callManager->makeCall('1001', '1002', 'default');
$callManager->transferCall($channel, '1003');
$callManager->parkCall($channel);
```

#### 🔧 **Standalone CLI Tool**
```bash
# Use without Laravel
php bin/ami ami:listen --host=192.168.1.100 --monitor
php bin/ami ami:action Status --username=admin --secret=pass
```

#### ⚡ **Background Processing**
```php
// Queue bulk operations
use Shahkochaki\Ami\Jobs\BulkSmsJob;

BulkSmsJob::dispatch($recipients, $message, $options);
```

### 📚 **Documentation Improvements**

#### 🌍 **Bilingual Support**
- Complete English documentation
- Full Persian translation for Iranian developers
- Code examples in both languages

#### 📖 **Enhanced Guides**
- Step-by-step installation
- Configuration best practices
- Advanced usage examples
- Troubleshooting guide
- Migration guide from v1.x

### 🔒 **Security & Performance**

#### 🛡️ **Security Features**
- Rate limiting middleware
- IP whitelisting support
- Enhanced authentication
- Secure connection management

#### 🚀 **Performance Optimizations**
- Connection pooling
- Async event processing
- Response caching
- Health monitoring

### 🧪 **Testing & Quality**

#### ✅ **Comprehensive Testing**
- Unit tests for all components
- Integration tests with real AMI
- Performance benchmarks
- Code coverage reports

#### 📊 **Code Quality**
- PSR-12 coding standards
- Static analysis with PHPStan
- Automated code formatting
- Continuous integration

## 📋 Migration Guide

### From v1.x to v2.0.1

#### 1. **Update Requirements**
```bash
# Ensure PHP 8.0+ and Laravel 9.0+
php --version  # Should be 8.0+
php artisan --version  # Should be Laravel 9.0+
```

#### 2. **Update Package**
```bash
composer require shahkochaki/ami:^2.0
```

#### 3. **Update Configuration**
```bash
# Re-publish configuration
php artisan vendor:publish --tag=ami --force
```

#### 4. **Code Changes**
- Replace `array_get()` with `Arr::get()`
- Update event listener syntax if needed
- Use new service classes (optional but recommended)

#### 5. **Environment Variables**
Add new variables to `.env`:
```env
AMI_HOST=192.168.1.100
AMI_PORT=5038
AMI_USERNAME=your_user
AMI_SECRET=your_secret
AMI_SMS_DEVICE=dongle0
```

## 🎯 **Why Upgrade?**

### ⚡ **Performance**
- **PHP 8 JIT**: Up to 2x faster execution
- **Connection Pooling**: Reduced connection overhead
- **Async Processing**: Non-blocking operations

### 🔒 **Security**
- **Latest Dependencies**: Security patches included
- **Rate Limiting**: Protection against abuse
- **Enhanced Authentication**: Better security controls

### 🛠️ **Developer Experience**
- **Modern PHP Features**: Typed properties, match expressions
- **Better IDE Support**: Improved autocompletion and error detection
- **Comprehensive Documentation**: Easier to learn and use

### 🔮 **Future-Proof**
- **Laravel 10/11 Ready**: Compatible with upcoming versions
- **Long-term Support**: Better maintenance and updates
- **Modern Architecture**: Easier to extend and customize

## 🤝 **Community & Support**

### 🇮🇷 **For Iranian Developers**
- Persian documentation and examples
- Local community support
- Timezone-friendly support hours

### 🌍 **International Support**
- GitHub issues and discussions
- Email support: ali.shahkochaki7@gmail.com
- Community contributions welcome

## 📦 **Installation**

### Fresh Installation
```bash
composer require shahkochaki/ami
php artisan vendor:publish --tag=ami
```

### Docker Support
```dockerfile
FROM php:8.1-fpm
RUN composer require shahkochaki/ami
```

## 🔗 **Useful Links**

- 📖 [Documentation](README.md)
- 🐛 [Report Issues](https://github.com/shahkochaki/ami/issues)
- 💬 [Discussions](https://github.com/shahkochaki/ami/discussions)
- 📧 [Email Support](mailto:ali.shahkochaki7@gmail.com)
- 🌐 [Author Website](https://shahkochaki.ir)

---

⭐ **Star the project** if it helps you!

**Made with ❤️ for the PHP and Iranian developer community**