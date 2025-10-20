<?php

namespace Shahkochaki\Ami;

use Clue\React\Ami\Client;
use Clue\React\Ami\ActionSender;
use Illuminate\Support\Arr;
use React\EventLoop\LoopInterface;
use React\Socket\Connector;

class Factory
{
    /**
     * @var \React\EventLoop\LoopInterface
     */
    protected $loop;

    /**
     * @var \React\Socket\Connector
     */
    protected $connector;

    /**
     * @param \React\EventLoop\LoopInterface $loop
     * @param \React\Socket\Connector $connector
     */
    public function __construct(LoopInterface $loop, Connector $connector)
    {
        $this->connector = $connector;
        $this->loop = $loop;
    }

    /**
     * Create client.
     *
     * @param array $options
     *
     * @return \React\Promise\Promise
     */
    public function create(array $options = [])
    {
        foreach (['host', 'port', 'username', 'secret'] as $key) {
            $options[$key] = Arr::get($options, $key, null);
        }

        // Use new React Socket API: connect() instead of create()
        $uri = $options['host'] . ':' . $options['port'];
        $promise = $this->connector->connect($uri)->then(function ($stream) {
            return new Client($stream, new \Shahkochaki\Ami\Parser());
        });

        if (!is_null($options['username'])) {
            $promise = $promise->then(function (Client $client) use ($options) {
                $sender = new ActionSender($client);

                return $sender->login($options['username'], $options['secret'])->then(
                    function () use ($client) {
                        return $client;
                    },
                    function ($error) use ($client) {
                        $client->close();
                        throw $error;
                    }
                );
            }, function ($error) {
                throw $error;
            });
        }

        return $promise;
    }
}
