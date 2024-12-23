<?php

namespace Xbyter\Amqp\Interfaces;;

interface ProducerMessageInterface extends MessageInterface
{
    /**
     * Получить данные
     * @return array
     */
    public function getData(): array;


    /**
     * Получить сериализованное тело запроса
     * @return string
     */
    public function getBody(): string;

    /**
     * @return array
     */
    public function getProperties(): array;

    /**
     * @return string
     */
    public function getRoutingKey(): string;

    /**
     * @return string
     */
    public function getExchange(): string;
}
