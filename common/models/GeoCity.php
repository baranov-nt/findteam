<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "geo_city".
 *
 * @property integer $id
 * @property integer $region_id
 * @property string $name_ru
 * @property string $name_en
 * @property string $lat
 * @property string $lon
 * @property string $okato
 *
 * @property GeoRegion $region
 */
class GeoCity extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geo_city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['region_id'], 'integer'],
            [['name_ru', 'name_en', 'lat', 'lon'], 'required'],
            [['lat', 'lon'], 'number'],
            [['name_ru', 'name_en'], 'string', 'max' => 128],
            [['okato'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID города'),
            'region_id' => Yii::t('app', 'ID региона'),
            'name_ru' => Yii::t('app', 'Русское название'),
            'name_en' => Yii::t('app', 'Английское название'),
            'lat' => Yii::t('app', 'Широта'),
            'lon' => Yii::t('app', 'Долгота'),
            'okato' => Yii::t('app', 'ОКАТО'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(GeoRegion::className(), ['id' => 'region_id']);
    }
}
