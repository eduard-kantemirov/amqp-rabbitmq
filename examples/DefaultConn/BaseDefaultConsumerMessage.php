<?php

namespace Xbyter\Amqp\Examples\DefaultConn;


use Xbyter\Amqp\BaseConsumerMessage;
use Xbyter\Amqp\Enum\ConsumeResultEnum;

abstract class BaseDefaultConsumerMessage extends BaseConsumerMessage
{
    /** @var string Связь */
    protected string $conn = 'default';

    protected array $qos = [
        //Количество сообщений для предварительной выборки. Чем больше число, тем выше производительность,
        // но это может привести к тому, что сообщения будут накапливаться в одном процессе, оставляя другие процессы пустыми.
        'prefetch_count' => 10,
        'prefetch_size'  => null,
        'global'         => false,
    ];

    public function consume(string $body): string
    {
        try {
            $data = $this->unserialize($body);
            return $this->handle(...$data);
        } catch (\Throwable $e) {
            $this->log(sprintf('run queue error: %s', [$e->getMessage()]));
        }

        return ConsumeResultEnum::REJECT_DROP;
    }


    protected function log(string $content):void
    {
        //write log
    }
}
