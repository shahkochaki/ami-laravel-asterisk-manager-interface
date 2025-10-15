# Contributing to Shahkochaki AMI

راهنمای مشارکت در پروژه کتابخانه AMI شاهکچکی

We welcome contributions to the Shahkochaki AMI package! This document will help you get started.

## 🤝 How to Contribute

### 1. Fork and Clone

```bash
# Fork the repository on GitHub, then clone your fork
git clone https://github.com/YOUR_USERNAME/ami-laravel-asterisk-manager-interface.git
cd ami-laravel-asterisk-manager-interface

# Add the original repository as upstream
git remote add upstream https://github.com/shahkochaki/ami-laravel-asterisk-manager-interface.git
```

### 2. Set Up Development Environment

```bash
# Install dependencies
composer install

# Copy configuration for testing
cp config/ami.php config/ami-test.php

# Set up your test environment variables
cp .env.example .env.testing
```

### 3. Create a Feature Branch

```bash
# Create and switch to a new branch
git checkout -b feature/your-feature-name

# Or for bug fixes
git checkout -b fix/issue-description
```

## 📋 Development Guidelines

### Code Style

We follow PSR-12 coding standards:

```bash
# Check code style
composer phpcs

# Fix code style issues
composer phpcbf
```

### Testing

All new features must include tests:

```bash
# Run all tests
composer test

# Run specific test
./vendor/bin/phpunit tests/Feature/YourTest.php

# Run with coverage
composer test-coverage
```

### Documentation

- Update README.md if adding new features
- Add PHPDoc comments to all public methods
- Update CHANGELOG.md following [Keep a Changelog](https://keepachangelog.com/) format

## 🔧 Types of Contributions

### 1. Bug Fixes

- Check existing issues first
- Create a test that reproduces the bug
- Fix the bug
- Ensure all tests pass
- Update documentation if needed

### 2. New Features

- Discuss the feature in an issue first
- Follow existing code patterns
- Add comprehensive tests
- Update documentation
- Add examples to README if applicable

### 3. Documentation Improvements

- Fix typos or unclear explanations
- Add examples
- Improve code comments
- Translate documentation (Persian/English)

### 4. Performance Improvements

- Benchmark before and after
- Add performance tests
- Document the improvement

## 📝 Coding Standards

### PHP Code Style

```php
<?php

namespace Shahkochaki\Ami\YourNamespace;

use Illuminate\Support\Facades\Log;

/**
 * Class description
 *
 * Detailed description of what this class does
 */
class YourClass
{
    /**
     * @var string Description of property
     */
    protected $property;

    /**
     * Method description
     *
     * @param string $parameter Description of parameter
     * @return array Description of return value
     * @throws \Exception When something goes wrong
     */
    public function yourMethod($parameter)
    {
        // Implementation
        return [];
    }
}
```

### Naming Conventions

- **Classes**: PascalCase (`CallManager`)
- **Methods**: camelCase (`makeCall`)
- **Properties**: camelCase (`$connectionOptions`)
- **Constants**: UPPER_SNAKE_CASE (`MAX_RETRIES`)
- **Files**: PascalCase for classes, kebab-case for configs

### Comments and Documentation

```php
/**
 * Send bulk SMS to multiple recipients
 *
 * This method handles sending SMS messages to multiple recipients with
 * retry logic, rate limiting, and error handling.
 *
 * @param Collection|array $recipients Array of phone numbers
 * @param string $message SMS message content
 * @param array $options Additional options (device, pdu, etc.)
 * @return array Results array with success/failure status
 * @throws \InvalidArgumentException When recipients array is empty
 * @throws \Exception When SMS service is unavailable
 *
 * @example
 * $results = $service->sendBulkSms(['09123456789'], 'Hello World!');
 * foreach ($results as $number => $result) {
 *     echo "SMS to {$number}: " . $result['status'];
 * }
 */
public function sendBulkSms($recipients, string $message, array $options = [])
{
    // Implementation
}
```

## 🧪 Testing Guidelines

### Test Structure

```php
<?php

namespace Shahkochaki\Ami\Tests\Feature;

use Shahkochaki\Ami\Tests\TestCase;

class YourFeatureTest extends TestCase
{
    /** @test */
    public function it_can_perform_your_feature()
    {
        // Arrange
        $input = 'test data';

        // Act
        $result = $this->yourService->performAction($input);

        // Assert
        $this->assertEquals('expected', $result);
    }

    /** @test */
    public function it_handles_errors_gracefully()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->yourService->performAction(null);
    }
}
```

### Test Categories

1. **Unit Tests**: Test individual methods/classes
2. **Feature Tests**: Test complete workflows
3. **Integration Tests**: Test external system interactions
4. **Performance Tests**: Test performance requirements

### Mocking External Services

```php
// Mock AMI connections for testing
public function setUp(): void
{
    parent::setUp();

    $this->mockAmiConnection();
}

private function mockAmiConnection()
{
    // Mock implementation
}
```

## 📦 Adding New Features

### 1. Feature Planning

Before implementing a new feature:

1. Create an issue describing the feature
2. Discuss the approach with maintainers
3. Plan the API design
4. Consider backward compatibility

### 2. Implementation Checklist

- [ ] Feature implementation
- [ ] Unit tests
- [ ] Integration tests
- [ ] Documentation updates
- [ ] Example code
- [ ] Error handling
- [ ] Logging (if applicable)
- [ ] Configuration options (if applicable)
- [ ] Persian translation (if applicable)

### 3. Common Feature Types

#### Adding a New AMI Action

```php
// 1. Add method to CallManager or create new service
public function yourNewAction($param1, $param2)
{
    return $this->executeAction('YourAction', [
        'Param1' => $param1,
        'Param2' => $param2,
    ]);
}

// 2. Add tests
/** @test */
public function it_can_execute_your_new_action()
{
    $result = $this->callManager->yourNewAction('value1', 'value2');
    $this->assertNotNull($result);
}

// 3. Add to README examples section
```

#### Adding Event Handling

```php
// 1. Add to AmiEventListener
public function handleYourEvent($event)
{
    $this->log('info', 'Your event occurred', $event);
    Event::fire('ami.your_event.occurred', $event);
}

// 2. Register in configuration
'events' => [
    'YourEvent' => [
        // Custom handlers
    ],
]
```

## 🐛 Bug Reports

### Good Bug Report Includes:

1. **Description**: Clear description of the issue
2. **Steps to Reproduce**: Step-by-step instructions
3. **Expected Behavior**: What should happen
4. **Actual Behavior**: What actually happens
5. **Environment**: PHP version, Laravel version, OS
6. **Configuration**: Relevant config settings
7. **Logs**: Error logs or debug output

### Bug Report Template:

````markdown
## Bug Description

Brief description of the bug

## Steps to Reproduce

1. Step 1
2. Step 2
3. Step 3

## Expected Behavior

What you expected to happen

## Actual Behavior

What actually happened

## Environment

- PHP Version: 8.1
- Laravel Version: 9.0
- AMI Package Version: 1.0.0
- OS: Ubuntu 20.04
- Asterisk Version: 18.0

## Configuration

```php
// Relevant configuration settings
```
````

## Logs

```
Error logs or debug output
```

## Additional Context

Any other relevant information

````

## 🔄 Pull Request Process

### 1. Before Submitting

```bash
# Ensure your branch is up to date
git fetch upstream
git rebase upstream/main

# Run all checks
composer test
composer phpcs
````

### 2. Pull Request Checklist

- [ ] Tests pass
- [ ] Code follows style guidelines
- [ ] Documentation updated
- [ ] CHANGELOG.md updated
- [ ] No breaking changes (or clearly documented)
- [ ] Examples provided (if applicable)

### 3. Pull Request Template

```markdown
## Description

Brief description of changes

## Type of Change

- [ ] Bug fix
- [ ] New feature
- [ ] Documentation update
- [ ] Performance improvement
- [ ] Refactoring

## Changes Made

- Change 1
- Change 2
- Change 3

## Testing

- [ ] Unit tests added/updated
- [ ] Integration tests added/updated
- [ ] Manual testing completed

## Documentation

- [ ] README updated
- [ ] Code comments added
- [ ] Examples provided

## Breaking Changes

None / List any breaking changes

## Related Issues

Fixes #123
```

## 📚 Resources

### Learning AMI Development

- [Asterisk Manager Interface Documentation](https://wiki.asterisk.org/wiki/display/AST/The+Asterisk+Manager+TCP+IP+API)
- [Chan Dongle Documentation](https://github.com/bg111/asterisk-chan-dongle)
- [ReactPHP Documentation](https://reactphp.org/)

### Laravel Integration

- [Laravel Package Development](https://laravel.com/docs/packages)
- [Laravel Service Providers](https://laravel.com/docs/providers)
- [Laravel Events](https://laravel.com/docs/events)

### Tools

- [PHPStan](https://phpstan.org/) - Static analysis
- [PHP CS Fixer](https://cs.symfony.com/) - Code style fixer
- [PHPUnit](https://phpunit.de/) - Testing framework

## 💬 Communication

### Getting Help

- 📧 Email: ali.shahkochaki7@gmail.com
- 🐛 GitHub Issues: For bugs and feature requests
- 💬 Discussions: For questions and general discussion

### Iranian Developers

این پروژه به خصوص برای توسعه‌دهندگان ایرانی ساخته شده است. لطفاً در صورت نیاز به راهنمایی به فارسی، در Issues اعلام کنید.

We especially welcome contributions from Iranian developers. Feel free to communicate in Persian if needed.

## 🙏 Recognition

### Contributors

All contributors will be recognized in:

- README.md Contributors section
- GitHub Contributors page
- Release notes

### Types of Recognition

- **Code Contributors**: Features, bug fixes, improvements
- **Documentation Contributors**: README, guides, translations
- **Community Contributors**: Issue reports, discussions, support
- **Translators**: Persian/English translations

Thank you for contributing to make this package better! 🎉

---

**Made with ❤️ for the PHP and Iranian developer community**
