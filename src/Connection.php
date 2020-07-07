<?php

namespace Kara;

use React\EventLoop\LoopInterface;

class Connection
{
    private static LoopInterface $loop;
    private static \RdKafka\Conf $config;
    private static array $brokers;

    public static function getLoop()
    {
        return self::$loop;
    }

    public static function setLoop(LoopInterface $l)
    {
        self::$loop = $l;
    }

    public static function setBrokers(string ...$brokers)
    {
        self::$brokers = $brokers;
    }

    public static function getBrokers()
    {
        return implode(",", self::$brokers);
    }

    public static function setConfig(string $logLevel = LOG_DEBUG, ?string $debug = null)
    {
        self::$config = new \RdKafka\Conf();
        self::$config->set("log_level", $logLevel);

        if ($debug) {
            self::$config->set("debug", $debug);
        }
    }

    public static function getConfig()
    {
        return self::$config;
    }

    public static function createConsumer(string $topic, int $type)
    {
        return new class($topic, $type) extends Consumer {
            protected string $topic;
            protected int $type;

            public function __construct(string $topic, int $type)
            {
                $this->topic = $topic;
                $this->type = $type;
            }
        };
    }
}
