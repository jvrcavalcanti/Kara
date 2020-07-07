<?php

namespace Kara;

final class ManagerConsumer
{
    /** @var \Kara\Consumer[] $consumers */
    private array $consumers = [];

    public function addConsumer(Consumer $consumer)
    {
        $this->consumers[] = $consumer;
    }

    public function addConsumers(array $consumers)
    {
        $this->consumers = [
            ...$this->consumers,
            ...$consumers
        ];
    }

    public function run()
    {
        foreach ($this->consumers as $consumer) {
            $consumer->run();
        }

        Connection::getLoop()->run();
    }
}
