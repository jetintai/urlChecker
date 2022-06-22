<?php

namespace common\components\jobs;

use common\models\Check;
use yii\base\BaseObject;
use yii\httpclient\Client;

/**
 * Class CheckUrlJob
 * @package common\components\jobs
 */
class CheckUrlJob extends BaseObject implements \yii\queue\RetryableJobInterface
{
    /**
     * @var
     */
    public $url_id;
    /**
     * @var id id for relative table url
     */
    public $url;
    /**
     * @var
     */
    public $frequency;
    /**
     * @var
     */
    public $repeat_count;
    /**
     * @var int stored value of attempts
     */
    public $attempt = 1;

    /**
     * delay before run new attempt
     */
    const JOB_ATTEMPT_DELAY = 60;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function execute($queue)
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($this->url)
            ->send();

        $this->pushAttemptRow($response->statusCode);

    }

    /**
     * @return int
     */
    public function getTtr()
    {
        return self::JOB_ATTEMPT_DELAY;
    }

    /**
     * @param $http_code
     */
    private function pushAttemptRow($http_code) {
        $check = new Check();
        $check->url_id = $this->url_id;
        $check->date_request = date_format(new \DateTime(), 'Y-m-d H:i:s');
        $check->http_code = $http_code;
        $check->try_number = $this->attempt;
        $check->save();
    }

    /**
     * @param int $attempt
     * @param \Exception|\Throwable $error
     * @return bool
     */
    public function canRetry($attempt, $error)
    {
        $this->attempt = $attempt;
        if ($attempt >= $this->repeat_count) {
            $this->pushAttemptRow('ERROR');
            return false;
        } else return true;
    }
}
