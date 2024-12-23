<?php

namespace Xbyter\Amqp\Examples\DefaultConn;


use Xbyter\Amqp\BaseProducerMessage;

abstract class BaseDefaultProducerMessage extends BaseProducerMessage
{
    /** @var string Связь */
    protected string $conn = 'default';
}
