<?php

namespace console\controllers;

use common\components\jobs\CheckUrlJob;
use yii\base\BaseObject;
use yii\console\Controller;
use yii\queue\Queue;
use common\components\jobs\CheckUrlDirector;
use Yii;

/**
 * Console controller for shedule tasks
 */
class DirectorController extends Controller
{
    /**
     * create a starting director message
     * @return string
     */

    public function actionPrepare()
    {
        $directorQueue = Yii::$app->directorQueue;
        if ($directorQueue->redis && $directorQueue->redis->hlen("$directorQueue->channel.messages") == 0) {
            $directorQueue->push(new CheckUrlDirector([
                'runCounter' => 0,
                'runTasks' => array()
            ]));
        }

        return 'OK';
    }
}
