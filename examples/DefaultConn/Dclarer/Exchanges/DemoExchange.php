<?php

namespace Xbyter\Amqp\Examples\DefaultConn\Dclarer\Exchanges;

use Xbyter\Amqp\Declarer\ExchangeDeclarer;
use Xbyter\Amqp\Enum\ExchangeTypeEnum;


class DemoExchange extends ExchangeDeclarer
{
    protected string $type = ExchangeTypeEnum::TOPIC;

    /** @var string Выключатель */
    public const EXCHANGE = 'demo.topic';
}
