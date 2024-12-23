<?php

namespace Xbyter\Amqp;

use Xbyter\Amqp\Interfaces\MessageInterface;
use Xbyter\Amqp\Packer\SerializablePacker;

abstract class BaseMessage implements MessageInterface
{
    /** @var string Связь */
    protected string $conn = 'default';


    /** @var string Какой сериализатор использовать */
    protected string $packer = SerializablePacker::class;


    /**
     * Получить подключение
     * @return string
     */
    /**
     * @return string
     */
    public function getConn(): string
    {
        return $this->conn;
    }


    /**
     * @return string
     */
    public function getPacker(): string
    {
        return $this->packer;
    }
}
