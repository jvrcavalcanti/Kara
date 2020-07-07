<?php

namespace Kara;

use Exception;

abstract class Consumer
{
    use PCTrait;

    protected int $particion = 0;
    protected $offset = \RD_KAFKA_OFFSET_BEGINNING;

    abstract public function handle(Message $message);

    public function setConfig(int $particion = 0, $offset = \RD_KAFKA_OFFSET_BEGINNING)
    {
        $this->particion = $particion;
        $this->offset = $offset;
    }

    public function unserialize($data)
    {
        switch ($this->getSerializeType()) {
            case Serialize::TEXT:
                return $data;

            case Serialize::JSON:
                return json_decode($data);

            case Serialize::PHP:
                return unserialize($data);
        }
    }

    public function run()
    {
        $consumer = new \RdKafka\Consumer(Connection::getConfig());
        $consumer->addBrokers(Connection::getBrokers());

        $topic = $consumer->newTopic($this->getTopic());
        $topic->consumeStart($this->particion, $this->offset);

        Connection::getLoop()->addPeriodicTimer(0.1, function () use ($topic) {
            $msg = $topic->consume(0, 1000);
            
            if (null === $msg || $msg->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
                return;
            } elseif ($msg->err) {
                throw new Exception($msg->err);
            } else {
                $msg->payload = $this->unserialize($msg->payload);
                $this->handle(new Message($msg));
            }
        });
    }
}
