<?php

namespace Shahkochaki\Ami\Commands;

use Exception;
use Clue\React\Ami\Client;
use Illuminate\Support\Arr;
use Clue\React\Ami\Protocol\Response;

class AmiSystemControl extends AmiAbstract
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ami:system
                                {--host= : Asterisk ami host}
                                {--port= : Asterisk ami port}
                                {--username= : Asterisk ami username}
                                {--secret= : Asterisk ami secret key}
                                {operation : System operation (shutdown|restart|reload|status)}
                                {--graceful : Use graceful shutdown/restart}
                                {--module= : Specific module to reload}
                                {--force : Force operation without confirmation}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Control Asterisk/Issabel server system operations';

    protected $headers = [
        'Operation',
        'Status',
        'Message',
    ];

    public function client(Client $client)
    {
        parent::client($client);

        $operation = $this->argument('operation');
        $graceful = $this->option('graceful');
        $module = $this->option('module');
        $force = $this->option('force');

        // Confirmation for destructive operations
        if (!$force && in_array($operation, ['shutdown', 'restart']) && $this->runningInConsole()) {
            if (!$this->confirm("Are you sure you want to {$operation} the Asterisk server?")) {
                $this->info('Operation cancelled.');
                return;
            }
        }

        $request = $this->executeSystemOperation($operation, $graceful, $module);

        if ($request) {
            $this->dispatcher->fire('ami.system.operation.sent', [$this, $operation, $request]);

            $request->then(
                function (Response $response) use ($operation) {
                    $this->dispatcher->fire('ami.system.operation.completed', [$this, $operation, $response]);
                    if ($this->runningInConsole()) {
                        $this->displaySystemResponse($operation, $response);
                    }
                    $this->stop();
                },
                function (Exception $exception) use ($operation) {
                    $this->error("System operation '{$operation}' failed: " . $exception->getMessage());
                    throw $exception;
                }
            );
        } else {
            $this->error("Invalid operation: {$operation}");
            $this->showHelp();
        }
    }

    /**
     * Execute system operation
     *
     * @param string $operation
     * @param bool $graceful
     * @param string|null $module
     * @return mixed|null
     */
    protected function executeSystemOperation($operation, $graceful = false, $module = null)
    {
        switch ($operation) {
            case 'shutdown':
                return $this->shutdownServer($graceful);

            case 'restart':
                return $this->restartServer($graceful);

            case 'reload':
                return $this->reloadConfiguration($module);

            case 'status':
                return $this->getServerStatus();

            default:
                return null;
        }
    }

    /**
     * Shutdown the server
     *
     * @param bool $graceful
     * @return mixed
     */
    protected function shutdownServer($graceful)
    {
        $command = $graceful ? 'core stop gracefully' : 'core stop now';

        if ($this->runningInConsole()) {
            $this->info("Executing: {$command}");
        }

        return $this->request('Command', ['Command' => $command]);
    }

    /**
     * Restart the server
     *
     * @param bool $graceful
     * @return mixed
     */
    protected function restartServer($graceful)
    {
        $command = $graceful ? 'core restart gracefully' : 'core restart now';

        if ($this->runningInConsole()) {
            $this->info("Executing: {$command}");
        }

        return $this->request('Command', ['Command' => $command]);
    }

    /**
     * Reload configuration
     *
     * @param string|null $module
     * @return mixed
     */
    protected function reloadConfiguration($module)
    {
        $command = $module ? "module reload {$module}" : 'core reload';

        if ($this->runningInConsole()) {
            $this->info("Executing: {$command}");
        }

        return $this->request('Command', ['Command' => $command]);
    }

    /**
     * Get server status
     *
     * @return mixed
     */
    protected function getServerStatus()
    {
        if ($this->runningInConsole()) {
            $this->info("Getting server status...");
        }

        return $this->request('Command', ['Command' => 'core show version']);
    }

    /**
     * Display system response
     *
     * @param string $operation
     * @param Response $response
     * @return void
     */
    public function displaySystemResponse($operation, Response $response)
    {
        $status = $response->getFieldValue('Response') ?? 'Unknown';
        $message = $response->getFieldValue('Message') ?? 'No message';

        $this->table($this->headers, [
            [$operation, $status, $message]
        ]);

        // Additional details for status operation
        if ($operation === 'status') {
            $this->line('');
            $this->info('System Details:');
            foreach ($response->getFields() as $key => $value) {
                if (!in_array($key, ['Response', 'Message', 'ActionID'])) {
                    $this->line("  {$key}: {$value}");
                }
            }
        }
    }

    /**
     * Show help information
     *
     * @return void
     */
    protected function showHelp()
    {
        $this->line('Available operations:');
        $this->line('  shutdown  - Shutdown the Asterisk server');
        $this->line('  restart   - Restart the Asterisk server');
        $this->line('  reload    - Reload configuration');
        $this->line('  status    - Get server status');
        $this->line('');
        $this->line('Options:');
        $this->line('  --graceful   - Use graceful shutdown/restart');
        $this->line('  --module=    - Specific module to reload');
        $this->line('  --force      - Skip confirmation prompts');
        $this->line('');
        $this->line('Examples:');
        $this->line('  php artisan ami:system shutdown --graceful');
        $this->line('  php artisan ami:system restart --force');
        $this->line('  php artisan ami:system reload --module=sip');
        $this->line('  php artisan ami:system status');
    }
}
