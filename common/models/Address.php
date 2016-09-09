<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property integer $type
 * @property string $image_main
 * @property string $images
 * @property string $index
 * @property string $address
 * @property integer $city_id
 * @property integer $country_id
 * @property integer $user_id
 * @property integer $company_id
 * @property integer $main
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property GeoCity $city
 * @property ProfileCompany $company
 * @property GeoCountry $country
 * @property User $user
 * @property Bank[] $banks
 * @property Contact $contact
 * @property Schedule $schedule
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type', 'city_id', 'country_id', 'user_id', 'company_id', 'main', 'created_at', 'updated_at'], 'integer'],
            [['image_main', 'images'], 'string', 'max' => 20],
            [['index', 'address'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCity::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProfileCompany::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeoCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID адреса'),
            'type' => Yii::t('app', 'Тип адреса'),
            'image_main' => Yii::t('app', 'Метка изображения'),
            'images' => Yii::t('app', 'Метка изображения доп фото'),
            'index' => Yii::t('app', 'Индекс'),
            'address' => Yii::t('app', 'Адрес'),
            'city_id' => Yii::t('app', 'Город'),
            'country_id' => Yii::t('app', 'Страна'),
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
    public function getCity()
    {
        return $this->hasOne(GeoCity::className(), ['id' => 'city_id']);
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
    public function getCountry()
    {
        return $this->hasOne(GeoCountry::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanks()
    {
        return $this->hasMany(Bank::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedule()
    {
        return $this->hasOne(Schedule::className(), ['address_id' => 'id']);
    }
}
