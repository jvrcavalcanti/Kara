<center>
    <h1>Kara</h1>
</center>

## Installing

```bash
composer require accolon/kara
```

## Overview

### Producers

```php
// TestProducer.php
use Kara\Producer;
use Kara\Serialize;

class TestProducer extends Producer
{
    protected string $topic = "test";
    // Types: JSON, PHP, TEXT
    protected int $type = Serialize::JSON;
}

$producer = new TestProducer();
$producer->send([
    "message" => "Hello!"
]);
```

### Consumers

```php
// TestConsumer.php
use Kara\Consumer;
use Kara\Message;

class TestConsumer extends Consumer
{
    protected string $topic = "test";

    public function handle(Message $message)
    {
        echo "Topic: {$this->topic} -> " . $message->payload . "\n";
    }
}

```

### Manager Consumer

```php
// index.php
use Kara\ManagerConsumer;

$manager = new ManagerConsumer();

$manager->addConsumer(new TestConsumer());

$manager->run();
```

### Run

Run consumers
```bash
php index.php
```