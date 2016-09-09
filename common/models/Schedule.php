<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "schedule".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $day
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $address_id
 * @property integer $user_id
 * @property integer $company_id
 * @property integer $main
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Address $address
 * @property ProfileCompany $company
 * @property User $user
 */
class Schedule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'day'], 'required'],
            [['type', 'day', 'start_time', 'end_time', 'address_id', 'user_id', 'company_id', 'main', 'created_at', 'updated_at'], 'integer'],
            [['address_id'], 'unique'],
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Address::className(), 'targetAttribute' => ['address_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProfileCompany::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID расписания'),
            'type' => Yii::t('app', 'Тип расписания'),
            'day' => Yii::t('app', 'Рабочий день'),
            'start_time' => Yii::t('app', 'Время открытия'),
            'end_time' => Yii::t('app', 'Время закрытия'),
            'address_id' => Yii::t('app', 'Адрес'),
            'user_id' => Yii::t('app', 'ID пользователя'),
            'company_id' => Yii::t('app', 'ID rомпании'),
            'main' => Yii::t('app', 'Основной'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(ProfileCompany::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
