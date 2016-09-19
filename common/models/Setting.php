<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting".
 *
 * @property integer $id
 * @property integer $show_all_cities
 * @property integer $show_all_countries
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['show_all_cities', 'show_all_countries'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'show_all_cities' => Yii::t('app', 'Использовать все города'),
            'show_all_countries' => Yii::t('app', 'Использовать все страны'),
        ];
    }
}
