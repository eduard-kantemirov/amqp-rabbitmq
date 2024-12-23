<?php
namespace Xbyter\Amqp\Examples;

use Xbyter\Amqp\Consumer;

class ConsumerServer
{

    private Consumer $consumer;

    /** @var int Максимальный объем памяти (МБ). При превышении этого значения очередь будет автоматически перезапущена после завершения задачи */
    private int $maxMemory = 512;

    /** @var int sleep time, когда ни одна задача не выполняется */
    private int $sleep = 3;

    /** @var bool Выходить ли. Если задача завершена, плавно выйти после завершения текущей задачи. */
    private bool $shouldQuit = false;


    public function __construct(Consumer $consumer)
    {
        $this->consumer = $consumer;
    }

    public function run(array $consumerMessageClasses)
    {
        //Проверяем, убит ли процесс. Если он убит, ждем завершения текущей задачи, прежде чем плавно выйти.
        if (extension_loaded('pcntl')) {
            $this->listenForSignals();
        }

        //Задачи, которым необходимо заснуть, задачи без данных будут заснуть или некоторое время ждать перед запуском
        $sleepTasks = [];
        while (true) {
            $hasData = false;
            foreach ($consumerMessageClasses as $consumerMessageClass) {
                /** @var \Xbyter\Amqp\Interfaces\ConsumerMessageInterface $consumerMessage */
                $consumerMessage = new $consumerMessageClass();
                $queueName = $consumerMessage->getQueue();

                //Задачи без данных будут находиться в спящем режиме или некоторое время ждать перед повторным запуском.
                if (isset($sleepTasks[$queueName]) && time() - $sleepTasks[$queueName] < $this->sleep) {
                    continue;
                }

                $channel = $this->consumer->buildConsumeChannel($consumerMessage);
                if ($channel->is_consuming()) {
                    $channel->wait(null, true);

                    if ($channel->hasMessage()) {
                        $hasData = true;
                        $sleepTasks[$queueName] = null;
                    } else {
                        //Если задача выполнена, подождите некоторое время, прежде чем запускать ее снова.
                        $sleepTasks[$queueName] = time();
                    }
                }

                //Выйти из очереди, если выполнены условия, превышен объем памяти/ручная остановка/процесс завершен
                $this->exitQueueIfNecessary($queueName);
            }

            //Если нет исполняемых задач, перейти в спящий режим на $sleep секунд
            if (!$hasData) {
                sleep($this->sleep);
            }
        }
    }


    /**
     * Следите за семафором процесса. Если процесс завершен kill, он плавно завершится после завершения текущей задачи.
     */
    protected function listenForSignals()
    {
        pcntl_async_signals(true);

        //Процесс уничтожения войдет
        pcntl_signal(SIGTERM, function () {
            $this->shouldQuit = true;
        });

        //ctrl + c прерывает процесс и входит
        pcntl_signal(SIGINT, function () {
            $this->shouldQuit = true;
        });
    }


    /**
     * Выйти из очереди, если выполнены условия, превышен объем памяти/ручная остановка/процесс завершен
     *
     * @param string $queueName
     */
    protected function exitQueueIfNecessary(string $queueName): void
    {
        // Если вы убьете его с помощью kill, он плавно выйдет после завершения текущей задачи.
        if ($this->shouldQuit) {
            $this->logInfo("Amqp process terminated: $queueName");
            exit(0);
        }

        // Перезапустить после того, как текущее использование памяти превысит указанный объем памяти
        $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
        if ($memory >= $this->maxMemory) {
            $this->logInfo("Amqp $queueName current memory: $memory MB exceeds {$this->maxMemory}MB terminated");
            exit(0);
        }
    }


    protected function logInfo(string $content): void
    {

    }
}
