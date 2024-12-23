<?php

namespace Xbyter\Amqp\Enum;


class ConsumeResultEnum
{
    /** @var string Подтверждающее сообщение */
    public const ACK = 'ack';

    /** @var string сообщение об отклонении (то же, что и у функции reject, nack имеет дополнительный параметр batch, на данный момент batch не нужен) */
    //public const NACK = 'nack';

    /** @var string отклонить сообщение и повторно поставить его в очередь */
    public const REJECT_REQUEUE = 'reject_requeue';

    /** @var string отклонить и удалить сообщение */
    public const REJECT_DROP = 'reject_drop';
}
