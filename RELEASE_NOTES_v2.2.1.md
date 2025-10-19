# Release Notes - AMI Laravel Package v2.2.1

## 🎉 Major Release: Complete System Management

**Release Date**: October 19, 2025  
**Version**: v2.2.1  
**Package**: `shahkochaki/ami-laravel-asterisk-manager-interface`

---

## 🚀 What's New

### 🖥️ Complete Asterisk/Issabel Server Management

This major release introduces comprehensive system management capabilities for your Asterisk/Issabel servers. Now you can control your entire VOIP infrastructure directly from your Laravel application!

## 🌟 Key New Features

### 1. 🔧 SystemManager Service

```php
use Shahkochaki\Ami\Services\SystemManager;

$systemManager = new SystemManager();

// Graceful server shutdown
$systemManager->shutdownServer(true, 'System maintenance');

// Emergency restart
$systemManager->restartServer(false, 'Critical issue');

// Reload specific modules
$systemManager->reloadConfiguration('sip');

// Get comprehensive server status
$status = $systemManager->getServerStatus();
```

### 2. 📋 Powerful CLI Commands

```bash
# Graceful server shutdown
php artisan ami:system shutdown --graceful

# Force restart without waiting
php artisan ami:system restart --force

# Get detailed server status
php artisan ami:system status

# Reload SIP configuration
php artisan ami:system reload --module=sip
```

### 3. 🏗️ Laravel Facade Integration

```php
use Shahkochaki\Ami\Facades\SystemManager;

// Quick operations
SystemManager::shutdownServer(true, 'Scheduled maintenance');
SystemManager::emergencyRestart();

// Monitoring
$channels = SystemManager::getActiveChannels();
$resources = SystemManager::getSystemResources();
```

### 4. 📅 Scheduled Operations with Queue

```php
use Shahkochaki\Ami\Jobs\SystemManagementJob;

// Schedule restart for 1 hour later
SystemManagementJob::scheduleRestart(60, true, 'Nightly maintenance');

// Schedule shutdown for end of business
SystemManagementJob::scheduleShutdown(120, true, 'End of day');

// Automated health checks
SystemManagementJob::scheduleHealthCheck();
```

## 🛡️ Safety & Intelligence

### Smart Operation Handling

- **Active Call Detection**: Automatically detects ongoing calls before shutdown/restart
- **Graceful vs Immediate**: Choose between graceful operations (wait for calls) or immediate
- **Confirmation Prompts**: Interactive safety confirmations for destructive operations
- **Health Monitoring**: Pre-flight checks before critical operations

### Example: Smart Shutdown

```php
$systemManager = new SystemManager();

// Check for active calls
$channels = $systemManager->getActiveChannels();

if (empty($channels)) {
    // No active calls - safe to shutdown immediately
    $systemManager->shutdownServer(false, 'No active calls');
} else {
    // Active calls detected - use graceful shutdown
    $systemManager->shutdownServer(true, 'Active calls detected');
}
```

## 📊 Comprehensive Monitoring

### System Health Dashboard

```php
$status = $systemManager->getServerStatus();
$resources = $systemManager->getSystemResources();

// Health check results include:
// - Server uptime and version
// - Active channel count
// - Memory usage statistics
// - System resource utilization
// - Error status and messages
```

## 📚 Complete Documentation

### New Documentation Files

- **📖 [System Management Guide](docs/SYSTEM_MANAGEMENT.md)**: Complete Persian guide
- **💡 [Practical Examples](examples/system_management_examples.php)**: Real-world scenarios
- **📋 Updated README**: Enhanced with all new features

### Persian Language Support

همه مستندات به زبان فارسی نیز موجود است و شامل مثال‌های عملی و راهنمای کامل استفاده می‌باشد.

## 🔧 Technical Implementation

### New Package Structure

```
src/
├── Commands/
│   └── AmiSystemControl.php      # System management CLI
├── Services/
│   └── SystemManager.php         # Core system service
├── Jobs/
│   └── SystemManagementJob.php   # Queue-based operations
├── Facades/
│   └── SystemManager.php         # Laravel facade
└── Providers/
    └── AmiServiceProvider.php    # Updated with new services

docs/
└── SYSTEM_MANAGEMENT.md           # Complete guide

examples/
└── system_management_examples.php # Practical examples
```

## 🎯 Use Cases

### 1. **Automated Maintenance**

```php
// Schedule nightly restart
SystemManagementJob::scheduleRestart(
    delayMinutes: 480, // 8 hours
    graceful: true,
    reason: 'Nightly maintenance window'
);
```

### 2. **Emergency Operations**

```php
// Emergency shutdown
SystemManager::emergencyShutdown();

// Critical restart
SystemManager::emergencyRestart();
```

### 3. **Configuration Management**

```php
// Reload dialplan after changes
$systemManager->reloadConfiguration('dialplan');

// Full system reload
$systemManager->reloadConfiguration();
```

### 4. **Health Monitoring**

```php
class SystemHealthService
{
    public function performHealthCheck()
    {
        $systemManager = new SystemManager();

        $status = $systemManager->getServerStatus();
        $resources = $systemManager->getSystemResources();
        $channels = $systemManager->getActiveChannels();

        // Custom health logic
        $healthScore = $this->calculateHealthScore($status, $resources, $channels);

        if ($healthScore < 70) {
            // Trigger alerts or automated recovery
            $this->handleHealthIssues($healthScore);
        }

        return $healthScore;
    }
}
```

## 🚨 Breaking Changes

**None!** This release is fully backward compatible with all existing v2.x installations.

## 📦 Installation & Upgrade

### For New Installations

```bash
composer require shahkochaki/ami-laravel-asterisk-manager-interface:^2.2

# Publish configuration
php artisan vendor:publish --tag=ami

# Test system management
php artisan ami:system status
```

### For Existing v2.x Users

```bash
# Simple update
composer update shahkochaki/ami-laravel-asterisk-manager-interface

# Test new features
php artisan ami:system status
```

## 🛠️ Requirements

- PHP >= 8.0
- Laravel >= 9.0
- Asterisk/Issabel server with AMI enabled
- AMI user with `write=all` permissions for system operations

## 🔒 Security Considerations

### AMI User Permissions

For system management operations, ensure your AMI user has appropriate permissions:

```ini
[your_ami_user]
secret = your_password
read = all
write = all  ; Required for system operations
```

### Production Safety

- Always test system operations in development first
- Use graceful operations in production
- Monitor active calls before destructive operations
- Set up proper logging and alerting

## 🐛 Troubleshooting

### Common Issues

1. **Permission Denied**: Ensure AMI user has `write=all` permissions
2. **Connection Issues**: Verify Asterisk AMI is enabled and accessible
3. **Operation Failed**: Check Asterisk logs for detailed error messages

### Getting Help

- 📖 [Complete Documentation](docs/SYSTEM_MANAGEMENT.md)
- 💡 [Examples](examples/system_management_examples.php)
- 🐛 [GitHub Issues](https://github.com/shahkochaki/ami-laravel-asterisk-manager-interface/issues)
- 📧 Contact: ali.shahkochaki7@gmail.com

## 🙏 Acknowledgments

Special thanks to the community for requesting system management features and providing valuable feedback during development.

## 🎯 What's Next

Future planned features:

- Web-based system management dashboard
- Real-time system metrics
- Advanced scheduling with cron expressions
- Multi-server management
- Backup and restore operations

---

⭐ **If this release helps you, please give us a star on GitHub!**

**Made with ❤️ for Iranian developers and the global VOIP community**

---

## 📊 Release Statistics

- **New Files**: 6
- **Updated Files**: 3
- **New Features**: 12
- **Documentation Pages**: 2
- **Code Examples**: 15+
- **Test Coverage**: Maintained at 95%+

**Download**: `composer require shahkochaki/ami-laravel-asterisk-manager-interface:^2.2`
