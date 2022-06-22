<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Url;

/**
 * Url form
 */
class UrlForm extends Model {

    /**
     * @var string
     */
    public $url;
    /**
     * @var
     */
    public $repeat_count;
    /**
     * @var
     */
    public $frequency;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['url', 'required'],
            ['frequency', 'required'],
            ['repeat_count', 'required'],
            ['repeat_count', 'integer', 'integerOnly'=>true, 'min' => 0, 'max' => 10],
        ];
    }

    /**
     * @return bool
     */
    public function addUrl() {
        $url = new Url();
        $url->url = $this->url;
        $url->frequency = $this->frequency;
        $url->repeat_count = $this->repeat_count;

        return $url->save();
    }
}