<?php
require "../vendor/autoload.php";

$configs = require "amqp.config.php";

$consumers = $configs['consumers'];
$connections = $configs['connections'];

// Создание менеджера подключений
$connectionManage = \Xbyter\Amqp\Examples\ConnectionManageBuilder::buildFromConnections($connections);

// Создайте обмен и очередь и свяжите отношения между ними
$declarer = new \Xbyter\Amqp\Declarer($connectionManage);
foreach ($connections as $connName => $connConfig) {
    $declarer->setExchanges($connName, $connConfig['declarer']['exchanges'] ?? []);
    $declarer->setQueues($connName, $connConfig['declarer']['queues'] ?? []);
    $declarer->setBinds($connName, $connConfig['declarer']['binds'] ?? []);
}
$declarer->createAndBind();

// Опубликовать сообщение
$producer = new \Xbyter\Amqp\Producer($connectionManage);
$producer->publish(new \Xbyter\Amqp\Examples\DefaultConn\Producers\DemoProducer('消息参数1', '消息参数2'));

// Потреблять указанное потребительское сообщение
$consumer = new \Xbyter\Amqp\Consumer($connectionManage);
$consumer->consume(new \Xbyter\Amqp\Examples\DefaultConn\Consumers\DemoConsumer());

// Запуск обслуживания потребителей (рекомендуется использовать инструменты управления процессами, такие как супервизор)
$consumerServer = new \Xbyter\Amqp\Examples\ConsumerServer($consumer);
$consumerServer->run($consumers);
