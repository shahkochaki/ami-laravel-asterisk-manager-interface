# Release Notes - Version 2.1.3

## 🚨 **Critical Dependency Fix**

**Release Date:** October 16, 2025  
**Version:** 2.1.3  
**Priority:** 🔴 URGENT  
**Focus:** Fix abandoned dependency causing installation failures

---

## ⚠️ **Critical Issue Resolved**

### 🔧 **Abandoned Package Fixed**

- **Problem:** `react/socket-client` was abandoned and max version was `v0.7.0`
- **Our Requirement:** Package required `^0.8` which doesn't exist
- **Impact:** Composer install/update failed completely
- **Solution:** Migrated to maintained `react/socket@^1.0`

### 📦 **Dependencies Updated**

- ❌ **Removed:** `react/socket-client@^0.8` (abandoned, non-existent version)
- ✅ **Added:** `react/socket@^1.0` (actively maintained)

### 🔄 **Code Changes**

- Updated `AmiServiceProvider` to use new React Socket API
- Migrated `Factory` and `EnhancedFactory` classes
- Replaced `ConnectorInterface` with `Connector` class
- Simplified connector initialization (removed manual DNS resolver)

---

## 🚀 **Installation Now Works**

```bash
composer require shahkochaki/ami-laravel-asterisk-manager-interface:^2.1.3
```

---

## 🔄 **Migration**

**Automatic** - No code changes required in your application:

```bash
composer update shahkochaki/ami-laravel-asterisk-manager-interface
```

---

## 🎯 **What This Fixes**

### Before (v2.1.2):

```
Could not find package react/socket-client with version ^0.8
```

### After (v2.1.3):

```
✅ Installation successful with react/socket@^1.0
```

---

## 🔧 **Technical Details**

- **React Socket:** Modern, maintained package with better performance
- **API Compatibility:** Fully backward compatible for AMI operations
- **Laravel Support:** Still supports Laravel 9.x, 10.x, 12.x
- **PHP Support:** Still requires PHP 8.0+

---

## 🚨 **Upgrade Immediately**

Previous versions (2.1.2 and below) **cannot be installed** due to the abandoned dependency.

---

**This is a critical hotfix - please update immediately!** 🔥

_Team Shahkochaki AMI_
