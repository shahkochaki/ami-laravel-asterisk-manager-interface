# Changelog

All notable changes to the `shahkochaki/ami-laravel-asterisk-manager-interface` package will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v2.2.6] - 2025-11-10

### 🔧 Complete Docker Compatibility Fix

- **Fixed All Docker Build Issues**: Eliminated composer patch application failures completely
- **Enhanced Composer Configuration**: Added explicit patch configuration and plugin management
- **Added Comprehensive Docker Guide**: Complete documentation for Docker deployments
- **Optimized Performance**: Enhanced autoloader and cache handling for containers

### 🐛 Critical Bug Fixes

- **Resolved Patch Conflicts**: Added `patches: {}` to prevent patch application errors
- **Fixed Composer Plugin Issues**: Disabled problematic plugins that cause build failures
- **Enhanced Docker Support**: Complete compatibility with all major container platforms
- **Improved Build Performance**: Optimized composer install process for Docker environments

### 📚 Documentation Enhancements

- **Added DOCKER_GUIDE.md**: Comprehensive Docker deployment guide with examples
- **Enhanced Installation Guide**: Docker-specific installation instructions
- **Updated Troubleshooting**: Complete solutions for all Docker-related issues
- **Added Best Practices**: Production-ready Docker configurations

### 🔧 Technical Improvements

- **Composer Config Optimization**: Added explicit plugin and autoloader configurations
- **Build Process Enhancement**: Streamlined dependency resolution for containers
- **Cache Management**: Improved cache handling in Docker environments
- **Performance Optimization**: Faster package loading and initialization

## [v2.2.5] - 2025-11-10

### 🚀 Docker Compatibility & Console Command Fixes

- **Fixed Console Command Execution**: Resolved OutputStyle class loading issues in production environments
- **Enhanced Docker Support**: Fixed Composer patch application failures during Docker builds
- **Improved Command Class Architecture**: Enhanced namespace resolution and constructor handling
- **Added .dockerignore Template**: Provided template to prevent unnecessary files in Docker builds

### 🐛 Bug Fixes

- **Fixed Command.php OutputStyle Resolution**: Updated to use fully qualified class name for proper loading
- **Resolved Docker Build Issues**: Fixed "No available patcher" errors during composer install
- **Enhanced Error Handling**: Improved exception handling in console command execution
- **Fixed Type Declarations**: Refined method signatures and return types for better compatibility

### 🔧 Technical Improvements

- **Docker Optimization**: Added recommended Docker build configurations
- **Enhanced Documentation**: Updated README with Docker best practices and troubleshooting
- **Improved Code Quality**: Better error messages and debugging capabilities
- **Production Stability**: Enhanced deployment reliability in containerized environments

### 📚 Documentation

- **Updated Version Compatibility Table**: Added comprehensive version support matrix
- **Enhanced Docker Section**: Added deployment best practices for containerized environments
- **Improved Troubleshooting Guide**: Added solutions for common Docker deployment issues

## [v2.2.4] - 2025-10-20

### 🐛 React Socket API Compatibility Fix

- **Fixed React\Socket API Compatibility**: Updated `Factory::create()` method to use new React Socket API
- **Replaced Deprecated create() Method**: Changed `$connector->create()` to `$connector->connect()`
- **Updated Connection Format**: Modified host:port connection format for React Socket v1.x compatibility
- **Removed Deprecated Imports**: Cleaned up `React\Stream\Stream` import and updated type hints

### 🔧 Technical Improvements

- **Fixed Factory::create()**: Updated to use `$connector->connect($host . ':' . $port)` instead of deprecated `create($host, $port)`
- **Stream Type Compatibility**: Removed deprecated `React\Stream\Stream` type hints for modern React compatibility
- **Enhanced Error Handling**: Maintained existing promise-based error handling with updated API

### 📦 Dependencies

- **React Socket v1.x**: Full compatibility with latest React Socket package
- **Backward Compatible**: No breaking changes to public API

## [v2.2.3] - 2025-10-20

### 🐛 Type Compatibility Fixes

- **Fixed OutputStyle Type Compatibility**: Added proper `: bool` return type declarations to `OutputStyle` methods
- **Laravel 12 & PHP 8+ Compatibility**: Resolved type declaration compatibility issues with modern Laravel versions
- **Enhanced Laravel Support**: Added Laravel 11.x support to dependencies
- **Symfony Console Compatibility**: Fixed method signature compatibility with Symfony Console 6.x+

### 🔧 Technical Improvements

- **Fixed OutputStyle::isQuiet()**: Added `: bool` return type
- **Fixed OutputStyle::isVerbose()**: Added `: bool` return type
- **Fixed OutputStyle::isVeryVerbose()**: Added `: bool` return type
- **Fixed OutputStyle::isDebug()**: Added `: bool` return type
- **Updated Dependencies**: Added Laravel 11.x support in composer.json

### 📦 Updated Support Matrix

| Component       | Supported Versions    |
| --------------- | --------------------- |
| PHP             | 8.0+                  |
| Laravel         | 9.x, 10.x, 11.x, 12.x |
| Symfony Console | 5.x, 6.x              |

### 💡 Breaking Changes

None - This is a backward compatible fix for type compatibility.

## [v2.2.2] - 2025-10-20

### 🐛 Critical Bug Fixes

- **Fixed "Target class [ami] does not exist" Error**: Added proper `AmiService` registration in Service Provider
- **Added Main AMI Service**: Created `AmiService` class as the central service for all AMI operations
- **Fixed Service Container Binding**: Properly bound `'ami'` alias to `AmiService` class
- **Enhanced Error Handling**: Improved service resolution and dependency injection
- **Added Comprehensive Troubleshooting**: Created detailed troubleshooting guide and examples

### 🆕 Added Components

- **AmiService** (`src/Services/AmiService.php`): Central service for all AMI operations
- **Troubleshooting Guide** (`docs/TROUBLESHOOTING.md`): Comprehensive error resolution guide
- **Troubleshooting Examples** (`examples/troubleshooting_examples.php`): Practical solution examples

### 🔧 Enhanced Features

- **Better Service Resolution**: Multiple ways to access AMI services
- **Improved Documentation**: Added troubleshooting resources to README
- **Service Container Integration**: Proper Laravel service container integration
- **Dependency Injection Support**: Full support for DI in controllers and services

### 💡 Usage Examples

```php
// Direct instantiation (always works)
$ami = new \Shahkochaki\Ami\Services\AmiService($config);

// Service container (after this fix)
$ami = app('ami');

// Dependency injection (after this fix)
public function __construct(AmiService $ami) { ... }
```

### 📚 New Documentation

- [Troubleshooting Guide](docs/TROUBLESHOOTING.md) - Complete error resolution guide
- [Troubleshooting Examples](examples/troubleshooting_examples.php) - Practical examples
- Updated README with troubleshooting resources

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
