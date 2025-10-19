# Changelog

All notable changes to the `shahkochaki/ami-laravel-asterisk-manager-interface` package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v2.2.1] - 2025-10-19

### 🚀 Major New Features

- 🖥️ **Complete System Management**: Full Asterisk/Issabel server control capabilities
- 🔧 **SystemManager Service**: Comprehensive service for server operations
- 📋 **CLI System Commands**: New `ami:system` command for system management
- 🏗️ **SystemManager Facade**: Easy Laravel facade access for system operations
- 📅 **Scheduled Operations**: Queue-based scheduled system operations with `SystemManagementJob`
- 📊 **Health Monitoring**: Advanced system health checking and monitoring
- 🔒 **Safe Operations**: Intelligent operations with active call detection

### 🆕 Added Components

- **SystemManager Service** (`src/Services/SystemManager.php`):
  - Server shutdown (graceful/immediate)
  - Server restart (graceful/immediate)  
  - Configuration reload (full/module-specific)
  - System status monitoring
  - Active channels monitoring
  - Resource usage monitoring
  - Scheduled operations
- **AmiSystemControl Command** (`src/Commands/AmiSystemControl.php`):
  - CLI interface for system management
  - Interactive confirmations for destructive operations
  - Detailed status reporting
- **SystemManagementJob** (`src/Jobs/SystemManagementJob.php`):
  - Queue-based scheduled operations
  - Health check automation
  - Retry logic and error handling
- **SystemManager Facade** (`src/Facades/SystemManager.php`):
  - Laravel facade for easy access
  - IDE-friendly method hints

### 📚 Documentation & Examples

- **Complete System Management Guide** (`docs/SYSTEM_MANAGEMENT.md`):
  - Comprehensive Persian documentation
  - Usage examples and best practices
  - Safety guidelines and troubleshooting
- **Practical Examples** (`examples/system_management_examples.php`):
  - Real-world usage scenarios
  - Health checking implementations
  - Error handling examples
- **Updated README**: Enhanced with system management features

### 🎯 Usage Examples

#### Service Usage
```php
use Shahkochaki\Ami\Services\SystemManager;

$systemManager = new SystemManager();
$systemManager->shutdownServer(true, 'Maintenance');
$systemManager->restartServer(false, 'Emergency');
$status = $systemManager->getServerStatus();
```

#### CLI Usage
```bash
php artisan ami:system shutdown --graceful
php artisan ami:system restart --force
php artisan ami:system status
php artisan ami:system reload --module=sip
```

#### Facade Usage
```php
SystemManager::shutdownServer(true, 'Scheduled maintenance');
SystemManager::emergencyRestart();
$channels = SystemManager::getActiveChannels();
```

#### Scheduled Operations
```php
SystemManagementJob::scheduleRestart(60, true, 'Nightly maintenance');
SystemManagementJob::scheduleShutdown(120, true, 'End of business hours');
```

### 🔧 Technical Enhancements

- **Service Provider Updates**: Registered new services and commands
- **Enhanced Keywords**: Added system management related keywords
- **Improved Architecture**: Clean separation of concerns
- **Error Handling**: Comprehensive error handling and logging
- **Event Integration**: System operation events for monitoring

### 🛡️ Safety Features

- **Active Call Detection**: Prevents accidental service interruption
- **Graceful Operations**: Option for graceful shutdown/restart
- **Confirmation Prompts**: Interactive confirmations for destructive operations
- **Health Checks**: Pre-operation system health validation
- **Resource Monitoring**: System resource usage tracking

### 📦 Package Structure Updates

```
src/
├── Commands/
│   └── AmiSystemControl.php      # NEW
├── Services/
│   └── SystemManager.php         # NEW  
├── Jobs/
│   └── SystemManagementJob.php   # NEW
├── Facades/
│   └── SystemManager.php         # NEW
docs/
└── SYSTEM_MANAGEMENT.md           # NEW
examples/
└── system_management_examples.php # NEW
```

### 🎨 Updated Documentation

- **README.md**: Complete rewrite with system management features
- **Feature Comparison Table**: Version comparison with new capabilities
- **Quick Start Guide**: Fast setup and testing instructions
- **Troubleshooting**: Enhanced troubleshooting for system operations

## [2.1.5] - 2025-10-18

### Fixed

- 🚨 **Additional Fatal Error Fix**: Fixed `FatalError: Declaration of Shahkochaki\Ami\Commands\Command::run() must be compatible with Symfony\Component\Console\Command\Command::run(): int`
- 🔧 **Complete Console Compatibility**: Updated `run()` method signature to include `: int` return type
- ⚡ **Full Symfony Console Support**: Both `execute()` and `run()` methods now fully compatible with modern Symfony Console versions

### Changed

- **Method Signature**: Updated `Command::run()` to return `int` instead of no declared return type
- **Complete Coverage**: All console command methods now properly typed for Symfony compatibility

## [2.1.4] - 2025-10-18

### Fixed

- 🚨 **Critical Fatal Error Fix**: Fixed `FatalError: Declaration of Shahkochaki\Ami\Commands\Command::execute() must be compatible with Symfony\Component\Console\Command\Command::execute(): int`
- 🔧 **Console Command Compatibility**: Updated `execute()` method signature to include `: int` return type for Symfony Console v4.4+ compatibility
- ⚡ **Queue Worker Stability**: Fixed crashes when running AMI commands through Laravel queue workers
- 🎯 **Exit Code Handling**: Properly normalized command return values to integer exit codes (0 for success, 1 for failure)

### Changed

- **Method Signature**: Updated `Command::execute()` to return `int` instead of `mixed`
- **Return Value Normalization**: Added logic to convert handler results to proper exit codes

## [2.1.3] - 2025-10-16

### Fixed

- 🔧 **Critical Dependency Fix**: Replaced abandoned `react/socket-client` with `react/socket`
- ⚠️ **Package Security**: Fixed dependency on abandoned package that caused installation failures
- 🔄 **Updated Implementation**: Migrated from `react/socket-client@^0.8` (non-existent) to `react/socket@^1.0`
- 📦 **API Migration**: Updated all socket connector implementations to use new React Socket API

### Changed

- Replaced `React\SocketClient\Connector` with `React\Socket\Connector`
- Removed `React\SocketClient\ConnectorInterface` usage
- Updated Factory and EnhancedFactory classes for new socket API

## [2.1.2] - 2025-10-16

### Added

- 🚀 **Laravel 12 Support**: Added compatibility with Laravel 12.x
- 📝 **Updated Documentation**: Updated package description and keywords for Laravel 12

### Changed

- 🔄 **Laravel Versions**: Now supports Laravel 9.x, 10.x, and 12.x
- 📦 **Package Keywords**: Added laravel10 and laravel12 keywords for better discoverability

## [2.1.1] - 2025-10-15

### Changed

- 🗑️ **Optimized Dependencies**: Removed unnecessary packages to reduce installation size
- ❌ **Removed Packages**: `react/stream`, `react/socket`, `illuminate/config`, `illuminate/container`
- 🔄 **Updated Packages**: Replaced `react/socket` with `react/socket-client` for better compatibility
- 📦 **Simplified Scripts**: Removed potentially problematic post-install and post-update commands
- 🎯 **Laravel Support**: Focused on Laravel 9-10 for better stability (removed Laravel 11 support temporarily)
- 📏 **Package Size**: Reduced dependencies by 20% for faster installation

### Removed

- Removed `version` field from composer.json as recommended by Packagist
- Removed `post-install-cmd` and `post-update-cmd` scripts

## [2.0.1] - 2025-10-15

### Added

- 🚀 **Modern PHP Support**: Updated to PHP 8.0+ and Laravel 9.0+
- 📚 **Enhanced Documentation**: Comprehensive bilingual documentation (English/Persian)
- 🔧 **Standalone CLI**: Added `bin/ami` for usage without Laravel
- 💾 **Connection Management**: Advanced connection pooling and management
- 📞 **Call Management Service**: Enhanced call control with `CallManager` class
- 📱 **Bulk SMS Service**: Advanced SMS service with retry logic and queue support
- 🎧 **Event Management**: Structured event listening with `AmiEventListener`
- ⚡ **Rate Limiting**: API protection with rate limiting middleware
- 🏗️ **Laravel Facade**: Easy access via `Ami::class` facade
- 🔄 **Background Jobs**: Queue support for bulk operations
- 📊 **Testing Suite**: Comprehensive testing with performance and integration tests
- 🔧 **Enhanced Config**: Advanced configuration with environment variables
- 🛡️ **Security Features**: IP whitelisting and encryption options
- 🚀 **Performance**: Caching and async processing optimizations

### Changed

- ⬆️ **PHP Requirement**: Updated from 5.6+ to 8.0+
- ⬆️ **Laravel Requirement**: Updated from 5.1+ to 9.0+
- 📦 **Dependencies**: Updated all dependencies to modern versions
- 🔄 **Auto-Discovery**: Laravel service provider auto-discovery support
- 📖 **Documentation**: Complete rewrite with better structure and examples

### Enhanced

- 📖 **Documentation**:
  - Step-by-step installation guide
  - Comprehensive usage examples
  - Advanced configuration options
  - Troubleshooting section
  - Upgrade guide from v1.x
  - Persian language support
- 🔧 **Configuration**:
  - Environment variables support
  - Connection pooling settings
  - Security configurations
  - Performance tuning options

### Technical Improvements

- 🏗️ Better code organization with service classes
- 🔄 Asynchronous processing capabilities
- 📊 Connection health monitoring
- 🔒 Enhanced security features
- 📈 Performance monitoring and statistics
- 🧪 Comprehensive test coverage

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
