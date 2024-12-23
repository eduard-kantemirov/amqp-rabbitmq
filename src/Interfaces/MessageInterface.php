<?php

namespace Xbyter\Amqp\Interfaces;

interface MessageInterface
{
    /**
     * Получить подключенние
     * @return string
     */
    /**
     * @return string
     */
    public function getConn(): string;


    /**
     * @return string
     */
    public function getPacker(): string;
}
