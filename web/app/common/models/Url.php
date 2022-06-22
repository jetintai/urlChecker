<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Url model
 *
 * @property integer $id
 * @property string $url
 * @property integer $frequency
 * @property integer $repeat_count
 */

class Url extends ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%urls}}';
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getChecks()
    {
        return $this->hasMany(Check::className(), ['url_id' => 'id']);
    }

}