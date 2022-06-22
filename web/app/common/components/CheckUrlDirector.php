<?php

namespace common\components\jobs;

use common\models\Url;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;

/**
 * Class CheckUrlDirector
 * @package common\components\jobs
 */
class CheckUrlDirector extends BaseObject implements \yii\queue\JobInterface
{

    /**
     * @var int counter index director message
     */
    public $runCounter = 1;
    /**
     * @var array stored jobIds for every url-rule channel
     */
    public $runTasks = array();
    /**
     *
     */
    const REDIS_MESSAGE_DELAY = 60;

    /**
     * Process url rules and make and control UrlJobs
     * @param \yii\queue\Queue $queue
     * @return bool
     * @throws Exception
     */
    public function execute($queue)
    {
        if (!is_array($this->runTasks))
            $this->runTasks = array();

        $jobs = array();
        try {
            $db_res = Url::find()->all();
            foreach ($db_res as $row) {
                $ruleParams = $row->getAttributes();
                if (!empty($this->runTasks)) {
                    $previusJob = array_search($ruleParams['id'], array_column($this->runTasks, 'url_id'));
                    $previusJob = $this->runTasks[$previusJob];
                }

                if (($this->runCounter % $ruleParams['frequency'] == 0)) {
                    if (empty($previusJob) || $queue->isDone($previusJob['job_id'])) {
                        $jobId = Yii::$app->checkQueue->push(new CheckUrlJob([
                            'url_id' => $ruleParams['id'],
                            'url' => $ruleParams['url'],
                            'frequency' => $ruleParams['frequency'],
                            'repeat_count' => $ruleParams['repeat_count'],
                        ]));
                        $jobs[] = [
                            'url_id' => $ruleParams['id'],
                            'job_id' => $jobId,
                        ];
                    } else $jobs[] = $previusJob;
                }
            }
        } catch (\Throwable $e) {
            //For debug
            throw new Exception("Director is error. - file $e->getFile() at line $e->getLine() -  $e->getMessage()");
        } finally {
            Yii::$app->directorQueue->delay(self::REDIS_MESSAGE_DELAY)->push(new CheckUrlDirector([
                'runCounter' => $this->runCounter + 1,
                'runTasks' => $jobs
            ]));
        }

        return true;
    }
}
