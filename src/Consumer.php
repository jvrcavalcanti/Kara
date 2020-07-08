<?php

namespace Kara;

use Exception;

abstract class Consumer
{
    use PCTrait;

    protected int $particion = 0;
    protected $offset = \RD_KAFKA_OFFSET_BEGINNING;

    abstract public function handle($message);
    abstract public function unserialize(Message $message);

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

        Connection::getLoop()->addPeriodicTimer(0.1, function () use ($topic) {
            $msg = $topic->consume(0, 100);
            
            if (null === $msg || $msg->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
                return;
            } elseif ($msg->err) {
                throw new MessageErrorException($msg->errstr());
            } else {
                $msg = new Message($msg);
                $this->handle($this->unserialize($msg));
            }
        });
    }
}
