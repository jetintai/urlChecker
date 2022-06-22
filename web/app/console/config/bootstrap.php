<?php

use common\components\jobs\CheckUrlDirector;
use yii\base\Event;
use yii\queue\Queue;

Event::on(Queue::className(), Queue::EVENT_AFTER_ERROR, function ($event) {
    $queue = $event->sender;
    //$queue->delay(CheckUrlDirector::REDIS_MESSAGE_DELAY)->push($event->job);
});