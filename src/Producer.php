<?php

namespace Kara;

abstract class Producer
{
    use PCTrait;

    public function serialize($data): string
    {
        switch ($this->getSerializeType()) {
            case Serialize::TEXT:
                return $data;
            
            case Serialize::JSON:
                return json_encode($data);

            case Serialize::PHP:
                return serialize($data);
        }
    }

    protected function randomKey()
    {
        return md5(uniqid(microtime(true)), true);
    }

    public function send($message)
    {
        $producer = new \RdKafka\Producer(Connection::getConfig());
        $producer->addBrokers(Connection::getBrokers());

        $topic = $producer->newTopic($this->getTopic());

        $topic->produce(
            RD_KAFKA_PARTITION_UA,
            0,
            $this->serialize($message),
            $this->randomKey()
        );

        $producer->poll(0);
        $producer->flush(100);
    }
}
