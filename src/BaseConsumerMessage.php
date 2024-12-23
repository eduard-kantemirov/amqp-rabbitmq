<?php

namespace Xbyter\Amqp;

use Xbyter\Amqp\Enum\ConsumeResultEnum;
use Xbyter\Amqp\Interfaces\ConsumerMessageInterface;

abstract class BaseConsumerMessage extends BaseMessage implements ConsumerMessageInterface
{
    /**
     * @var string
     */
    public const QUEUE = '';

    protected array $qos = [
        // Количество сообщений для предварительной выборки. Чем больше число, тем выше производительность,
        // но это может привести к накоплению сообщений в одном процессе, оставляя другие процессы пустыми.
        'prefetch_count' => 1,
        'prefetch_size'  => null,
        'global'         => false,
    ];


    /**
     * @return string
     */
    public function getQueue(): string
    {
        return static::QUEUE;
    }

    /**
     * @return array|int[]
     */
    public function getQos(): array
    {
        return $this->qos;
    }

    public function consume(string $body): string
    {
        $this->unserialize($body);
        return ConsumeResultEnum::ACK;
    }


    /**
     * Десериализация данных
     * @param string $body
     * @return mixed
     */
    protected function unserialize(string $body)
    {
        /** @var \Xbyter\Amqp\Interfaces\PackerInterface $packer */
        $packer = $this->getPacker();
        $packer = new $packer();
        return $packer->unpack($body);
    }
}
