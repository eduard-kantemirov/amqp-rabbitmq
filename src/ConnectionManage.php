<?php

namespace Xbyter\Amqp;

class ConnectionManage
{
    /** @var \Xbyter\Amqp\Connection[] Коллекция коннекторов AMQP */
    private array $connections = [];


    public function addConnection(string $conn, ConnectionConfig $config): Connection
    {
        $connection = new Connection($config);
        $this->connections[$conn] = $connection;
        return $connection;
    }


    /**
     * @param string $conn
     * @return \Xbyter\Amqp\Connection
     * @throws \Exception
     */
    public function getConnection(string $conn): Connection
    {
        if (!isset($this->connections[$conn])) {
            throw new \InvalidArgumentException(sprintf("connection [%s] not found", [$conn]));
        }
        return $this->connections[$conn];
    }

    /**
     * @return \Xbyter\Amqp\Connection[]
     */
    public function getConnections(): array
    {
        return $this->connections;
    }
}
