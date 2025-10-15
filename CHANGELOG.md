# Changelog

All notable changes to the `shahkochaki/ami` package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- 🚀 Enhanced README with comprehensive documentation in Persian and English
- 🔧 Standalone CLI tool (`bin/ami`) for usage without Laravel
- 💾 Connection pooling and management with `ConnectionManager` class
- 📞 Advanced call management service (`CallManager`)
- 📱 Bulk SMS service with retry logic and queue support (`BulkSmsService`)
- 🎧 Advanced event listener with structured logging (`AmiEventListener`)
- ⚡ Rate limiting middleware for API protection
- 🏗️ Laravel Facade for easier access (`Ami::class`)
- 🔄 Background job processing for bulk operations (`BulkSmsJob`)
- 📊 Comprehensive testing suite with performance and integration tests
- 🔧 Enhanced configuration file with environment variables support
- 📋 Advanced error handling and logging capabilities
- 🛡️ Security features including IP whitelisting and encryption options
- 🚀 Performance optimizations with caching and async processing

### Enhanced
- 📖 README documentation with:
  - Step-by-step installation guide
  - Comprehensive usage examples
  - Advanced configuration options
  - Troubleshooting section
  - Persian language support
  - Code examples for common scenarios
- 🔧 Configuration system with:
  - Environment variables support
  - Connection pooling settings
  - Security configurations
  - Performance tuning options
  - Development/debugging features

### Technical Improvements
- 🏗️ Better code organization with service classes
- 🔄 Asynchronous processing capabilities
- 📊 Connection health monitoring
- 🔒 Enhanced security features
- 📈 Performance monitoring and statistics
- 🧪 Comprehensive test coverage

## [1.0.0] - Previous Release

### Added
- Basic AMI connection functionality
- SMS sending via Chan Dongle
- USSD command support
- Event listening capabilities
- CLI interface
- Laravel service provider integration

### Features
- Connect to Asterisk/Issabel AMI
- Send and receive SMS messages
- Execute USSD commands
- Listen to AMI events
- Command-line interface for operations
- Laravel integration with Artisan commands

---

## Upgrade Guide

### From 1.0.x to 2.0.x (Future Release)

#### Breaking Changes
None currently planned - maintaining backward compatibility.

#### New Features Available

1. **Enhanced Configuration**
   ```bash
   # Publish new configuration file
   php artisan vendor:publish --tag=ami --force
   ```

2. **Connection Pooling**
   ```php
   // Enable in config/ami.php
   'connection' => [
       'enable_pooling' => true,
       'max_connections' => 5,
   ]
   ```

3. **Service Classes**
   ```php
   // Use new service classes
   use Shahkochaki\Ami\Services\CallManager;
   use Shahkochaki\Ami\Services\BulkSmsService;
   
   $callManager = new CallManager();
   $smsService = new BulkSmsService();
   ```

4. **Facade Usage**
   ```php
   // Add to config/app.php aliases
   'Ami' => Shahkochaki\Ami\Facades\Ami::class,
   
   // Use facade
   Ami::makeCall('1001', '1002');
   Ami::sendSms('09123456789', 'Hello!');
   ```

5. **Background Jobs**
   ```php
   // Queue bulk SMS processing
   use Shahkochaki\Ami\Jobs\BulkSmsJob;
   
   BulkSmsJob::dispatch($recipients, $message, $options);
   ```

#### Migration Steps

1. **Update Composer**
   ```bash
   composer update shahkochaki/ami
   ```

2. **Publish New Assets**
   ```bash
   php artisan vendor:publish --tag=ami --force
   ```

3. **Update Environment Variables**
   ```env
   # Add new variables to .env
   AMI_ENABLE_POOLING=true
   AMI_MAX_CONNECTIONS=5
   AMI_LOGGING_ENABLED=true
   AMI_LOG_CHANNEL=ami
   ```

4. **Register Event Listeners (Optional)**
   ```php
   // In a service provider
   use Shahkochaki\Ami\Listeners\AmiEventListener;
   
   public function boot()
   {
       $listener = new AmiEventListener();
       $listener->register();
   }
   ```

5. **Update Code (Optional)**
   ```php
   // Old way (still works)
   Artisan::call('ami:action', ['action' => 'Status']);
   
   // New way (recommended)
   $callManager = new CallManager();
   $callManager->getChannelStatus();
   
   // Or using facade
   Ami::getChannelStatus();
   ```

### Configuration Changes

#### Enhanced Config Structure
```php
// config/ami.php - New structure
return [
    'host' => env('AMI_HOST', '127.0.0.1'),
    'port' => env('AMI_PORT', 5038),
    'username' => env('AMI_USERNAME'),
    'secret' => env('AMI_SECRET'),
    
    'connection' => [
        'enable_pooling' => env('AMI_ENABLE_POOLING', true),
        'max_connections' => env('AMI_MAX_CONNECTIONS', 5),
        'timeout' => env('AMI_CONNECTION_TIMEOUT', 10),
    ],
    
    'logging' => [
        'enabled' => env('AMI_LOGGING_ENABLED', true),
        'channel' => env('AMI_LOG_CHANNEL', 'ami'),
    ],
    
    // ... more options
];
```

### New Environment Variables

Add these to your `.env` file:

```env
# Connection Settings
AMI_HOST=192.168.1.100
AMI_PORT=5038
AMI_USERNAME=your_ami_user
AMI_SECRET=your_ami_secret

# Connection Pool
AMI_ENABLE_POOLING=true
AMI_MAX_CONNECTIONS=5
AMI_CONNECTION_TIMEOUT=10
AMI_HEARTBEAT_INTERVAL=30

# SMS Settings
AMI_SMS_DEVICE=dongle0
AMI_SMS_PDU_THRESHOLD=160
AMI_SMS_MAX_RETRIES=3
AMI_SMS_DELAY=500

# Logging
AMI_LOGGING_ENABLED=true
AMI_LOG_CHANNEL=ami
AMI_LOG_LEVEL=info

# Security
AMI_RATE_LIMITING=false
AMI_MAX_REQUESTS_PER_MINUTE=60

# Performance
AMI_CACHE_RESPONSES=false
AMI_ASYNC_PROCESSING=true

# Development
AMI_DEBUG_MODE=false
AMI_MOCK_RESPONSES=false
```

### Benefits of Upgrading

1. **Better Performance**: Connection pooling and caching
2. **Enhanced Reliability**: Retry logic and error handling
3. **Easier Development**: Service classes and facades
4. **Better Monitoring**: Comprehensive logging and events
5. **Scalability**: Background job processing
6. **Security**: Rate limiting and IP restrictions
7. **Maintainability**: Better code organization

### Support

For help with upgrading:
- Check the documentation: [README.md](README.md)
- Review the test files for examples
- Open an issue on GitHub
- Contact: ali.shahkochaki7@gmail.com