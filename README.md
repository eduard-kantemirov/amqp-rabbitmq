# amqp-rabbitmq

# Пример
Эта библиотека инкапсулирует `php-amqplib` в слой, что упрощает ее использование в бизнесе. 
Написанный в нативном стиле, он может поддерживать различные фреймворки.

# Пример
Подробности смотрите в `examples/run.php`.

```php
/** Ниже приведены примеры. В зависимости от реальной ситуации можно использовать внедрение зависимостей, supervisor и другие методы оптимизации **/

$configs = require "examples/amqp.config.php";

$потребители = $configs['потребители'];
$connections = $configs['connections'];

//Создаем менеджер соединений
$connectionManage = \Examples\ConnectionManageBuilder::buildFromConnections($connections);

//Создаем обмен и очередь и связываем отношения между ними
$declarer = new \Xbyter\Amqp\Declarer($connectionManage);
foreach ($connections as $connName => $connConfig) {
    $declarer->setExchanges($connName, $connConfig['declarer']['exchanges'] ?? []);
    $declarer->setQueues($connName, $connConfig['declarer']['queues'] ?? []);
    $declarer->setBinds($connName, $connConfig['declarer']['binds'] ?? []);
}
$declarer->createAndBind();

//Опубликовать сообщение
$producer = new \Xbyter\Amqp\Producer($connectionManage);
$producer->publish(new \Examples\DefaultConn\Producers\DemoProducer('параметр сообщения 1', 'параметр сообщения 2', '...'));

//Использовать указанное сообщение потребителя
$consumer = new \Xbyter\Amqp\Consumer($connectionManage);
$consumer->consume(new \Examples\DefaultConn\Consumers\DemoConsumer());

//Запуск службы потребителя (рекомендуется использовать инструменты управления процессами, такие как супервизор)
$consumerServer = new \Examples\ConsumerServer($consumer);
$consumerServer->run($consumers);
```
