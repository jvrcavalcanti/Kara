<?php

namespace Kara;

use Exception;

abstract class Consumer
{
    use PCTrait;

    protected int $particion = 0;
    protected $offset = \RD_KAFKA_OFFSET_BEGINNING;

    abstract public function handle(\RdKafka\Message $message);

    public function setConfig(int $particion = 0, $offset = \RD_KAFKA_OFFSET_BEGINNING)
    {
        $this->particion = $particion;
        $this->offset = $offset;
    }

    public function run()
    {
        $consumer = new \RdKafka\Consumer(Connection::getConfig());
        $consumer->addBrokers(Connection::getBrokers());

        $topic = $consumer->newTopic($this->getTopic());
        $topic->consumeStart($this->particion, $this->offset);


        while (true) {
            $msg = $topic->consume(0, 1000);
            
            if (null === $msg || $msg->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
                continue;
                // break;
            } elseif ($msg->err) {
                throw new Exception($msg->err);
            } else {
                $this->handle($msg);
            }
        }
    }
}
