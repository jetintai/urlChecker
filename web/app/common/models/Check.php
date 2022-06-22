<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "check".
 *
 * @property int $id
 * @property int $url_id
 * @property string|null $http_code
 * @property string|null $date_request
 * @property int|null $try_number
 *
 * @property Urls $url
 */
class Check extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'check';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url_id'], 'required'],
            [['url_id', 'try_number'], 'integer'],
            [['date_request'], 'safe'],
            [['http_code'], 'string', 'max' => 100],
            [['url_id'], 'exist', 'skipOnError' => true, 'targetClass' => Url::className(), 'targetAttribute' => ['url_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url_id' => 'Url ID',
            'http_code' => 'Http Code',
            'date_request' => 'Date Request',
            'try_number' => 'Try Number',
        ];
    }

    /**
     * Gets query for [[Url]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUrl()
    {
        return $this->hasOne(Url::className(), ['id' => 'url_id']);
    }
}
