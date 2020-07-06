<?php

namespace Kara;

class Connection
{
    private static \RdKafka\Conf $config;
    private static array $brokers;

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
}
