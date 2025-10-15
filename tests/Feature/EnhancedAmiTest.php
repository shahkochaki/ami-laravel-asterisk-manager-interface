<?php

namespace Shahkochaki\Ami\Tests\Feature;

use Shahkochaki\Ami\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class EnhancedAmiTest extends TestCase
{
    /**
     * Test basic AMI connection
     */
    public function testAmiConnection()
    {
        $this->artisan('ami:action', [
            'action' => 'Ping',
            '--host' => 'localhost',
            '--port' => 5038,
            '--username' => 'test',
            '--secret' => 'test'
        ])->assertExitCode(0);
    }

    /**
     * Test call management
     */
    public function testCallManagement()
    {
        // Test call origination
        $this->artisan('ami:action', [
            'action' => 'Originate',
            '--arguments' => [
                'Channel' => 'SIP/1001',
                'Context' => 'default',
                'Exten' => '1002',
                'Priority' => '1'
            ]
        ])->assertExitCode(0);

        // Test channel status
        $this->artisan('ami:action', [
            'action' => 'Status'
        ])->assertExitCode(0);
    }

    /**
     * Test SMS functionality
     */
    public function testSmsManagement()
    {
        // Test single SMS
        $this->artisan('ami:dongle:sms', [
            'number' => '09123456789',
            'message' => 'Test message',
            'device' => 'dongle0'
        ])->assertExitCode(0);

        // Test PDU mode
        $longMessage = str_repeat('This is a long message. ', 10);
        $this->artisan('ami:dongle:sms', [
            'number' => '09123456789',
            'message' => $longMessage,
            'device' => 'dongle0',
            '--pdu' => true
        ])->assertExitCode(0);
    }

    /**
     * Test USSD functionality
     */
    public function testUssdManagement()
    {
        $this->artisan('ami:dongle:ussd', [
            'device' => 'dongle0',
            'ussd' => '*141#'
        ])->assertExitCode(0);
    }

    /**
     * Test event listening
     */
    public function testEventListening()
    {
        // This would be more complex in a real test
        $this->artisan('ami:listen', [
            '--timeout' => 5 // Short timeout for testing
        ])->assertExitCode(0);
    }

    /**
     * Test CLI interface
     */
    public function testCliInterface()
    {
        $this->artisan('ami:cli', [
            'command' => 'core show channels',
            '--autoclose' => true
        ])->assertExitCode(0);
    }

    /**
     * Test error handling
     */
    public function testErrorHandling()
    {
        // Test with invalid credentials
        $this->artisan('ami:action', [
            'action' => 'Ping',
            '--host' => 'localhost',
            '--port' => 5038,
            '--username' => 'invalid',
            '--secret' => 'invalid'
        ])->assertExitCode(1);

        // Test with invalid device
        $this->artisan('ami:dongle:sms', [
            'number' => '09123456789',
            'message' => 'Test',
            'device' => 'invalid_device'
        ])->assertExitCode(1);
    }

    /**
     * Test configuration loading
     */
    public function testConfigurationLoading()
    {
        $config = config('ami');
        
        $this->assertArrayHasKey('host', $config);
        $this->assertArrayHasKey('port', $config);
        $this->assertArrayHasKey('dongle', $config);
        $this->assertArrayHasKey('events', $config);
    }

    /**
     * Test service providers
     */
    public function testServiceProviders()
    {
        $this->assertTrue(
            app()->bound('command.ami.listen')
        );
        
        $this->assertTrue(
            app()->bound('command.ami.action')
        );
        
        $this->assertTrue(
            app()->bound('command.ami.dongle.sms')
        );
        
        $this->assertTrue(
            app()->bound('command.ami.dongle.ussd')
        );
    }

    /**
     * Test factory creation
     */
    public function testFactoryCreation()
    {
        $factory = app('ami.factory');
        $this->assertInstanceOf(\Shahkochaki\Ami\Factory::class, $factory);
    }

    /**
     * Test event loop
     */
    public function testEventLoop()
    {
        $loop = app('ami.event_loop');
        $this->assertInstanceOf(\React\EventLoop\LoopInterface::class, $loop);
    }

    /**
     * Test connector
     */
    public function testConnector()
    {
        $connector = app('ami.connector');
        $this->assertInstanceOf(\React\SocketClient\ConnectorInterface::class, $connector);
    }
}

/**
 * Performance Tests
 */
class PerformanceTest extends TestCase
{
    /**
     * Test bulk SMS performance
     */
    public function testBulkSmsPerformance()
    {
        $recipients = [];
        for ($i = 0; $i < 10; $i++) {
            $recipients[] = '0912345678' . $i;
        }

        $startTime = microtime(true);
        
        // This would normally use the BulkSmsService
        foreach ($recipients as $recipient) {
            $this->artisan('ami:dongle:sms', [
                'number' => $recipient,
                'message' => 'Performance test message',
                'device' => 'dongle0'
            ]);
        }
        
        $endTime = microtime(true);
        $duration = $endTime - $startTime;
        
        // Assert that it completes within reasonable time
        $this->assertLessThan(60, $duration, 'Bulk SMS should complete within 60 seconds');
    }

    /**
     * Test connection pooling performance
     */
    public function testConnectionPoolingPerformance()
    {
        $startTime = microtime(true);
        
        // Multiple quick requests
        for ($i = 0; $i < 5; $i++) {
            $this->artisan('ami:action', [
                'action' => 'Ping'
            ]);
        }
        
        $endTime = microtime(true);
        $duration = $endTime - $startTime;
        
        // With connection pooling, this should be faster
        $this->assertLessThan(10, $duration, 'Multiple requests should complete quickly with pooling');
    }
}

/**
 * Integration Tests
 */
class IntegrationTest extends TestCase
{
    /**
     * Test full call workflow
     */
    public function testFullCallWorkflow()
    {
        // Originate call
        $this->artisan('ami:action', [
            'action' => 'Originate',
            '--arguments' => [
                'Channel' => 'SIP/1001',
                'Context' => 'default',
                'Exten' => '1002',
                'Priority' => '1'
            ]
        ])->assertExitCode(0);

        // Check status
        $this->artisan('ami:action', [
            'action' => 'Status'
        ])->assertExitCode(0);

        // Hangup (would need channel info in real test)
        $this->artisan('ami:action', [
            'action' => 'Hangup',
            '--arguments' => [
                'Channel' => 'SIP/1001-00000001'
            ]
        ])->assertExitCode(0);
    }

    /**
     * Test SMS with delivery confirmation
     */
    public function testSmsWithDeliveryConfirmation()
    {
        // Send SMS
        $result = $this->artisan('ami:dongle:sms', [
            'number' => '09123456789',
            'message' => 'Test message with confirmation',
            'device' => 'dongle0'
        ]);

        $this->assertEquals(0, $result);

        // Check delivery status (would need message ID in real implementation)
        $this->artisan('ami:action', [
            'action' => 'DongleSMSStatus',
            '--arguments' => [
                'Device' => 'dongle0'
            ]
        ])->assertExitCode(0);
    }
}