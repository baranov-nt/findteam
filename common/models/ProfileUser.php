<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "profile_user".
 *
 * @property integer $id
 * @property integer $type
 * @property string $tariff
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property integer $sex
 * @property integer $day_birth
 * @property integer $month_birth
 * @property integer $year_birth
 * @property integer $age
 * @property integer $social_status
 * @property integer $children
 * @property integer $children_count
 * @property string $inn
 * @property integer $company_id
 *
 * @property ProfileCompany $company
 * @property User $id0
 * @property SpecUser[] $specUsers
 */
class ProfileUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'sex', 'day_birth', 'month_birth', 'year_birth', 'age', 'social_status', 'children', 'children_count', 'company_id'], 'integer'],
            [['tariff'], 'string', 'max' => 20],
            [['first_name', 'last_name', 'middle_name'], 'string', 'max' => 32],
            [['inn'], 'string', 'max' => 18],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProfileCompany::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID пользователя'),
            'type' => Yii::t('app', 'Тип пользователя'),
            'tariff' => Yii::t('app', 'Тариф'),
            'first_name' => Yii::t('app', 'Имя'),
            'last_name' => Yii::t('app', 'Фамилия'),
            'middle_name' => Yii::t('app', 'Отчество'),
            'sex' => Yii::t('app', 'Пол'),
            'day_birth' => Yii::t('app', 'День рождения'),
            'month_birth' => Yii::t('app', 'Месяц рождения'),
            'year_birth' => Yii::t('app', 'Год рождения'),
            'age' => Yii::t('app', 'Возраст'),
            'social_status' => Yii::t('app', 'Социальный статус'),
            'children' => Yii::t('app', 'Дети'),
            'children_count' => Yii::t('app', 'Количество детей'),
            'inn' => Yii::t('app', 'ИНН'),
            'company_id' => Yii::t('app', 'Компания'),
        ];
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
    public function getId0()
    {
        return $this->hasOne(User::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecUsers()
    {
        return $this->hasMany(SpecUser::className(), ['user_id' => 'id']);
    }
}
