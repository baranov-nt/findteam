<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "profile_company".
 *
 * @property integer $id
 * @property integer $type
 * @property string $tariff
 * @property integer $status
 * @property string $name
 * @property string $description
 * @property string $image_main
 * @property string $images
 * @property string $inn
 * @property string $ogrn
 * @property string $okpo
 * @property string $okved
 * @property string $okato
 * @property string $bik
 * @property string $kpp
 *
 * @property Address[] $addresses
 * @property Bank[] $banks
 * @property Contact[] $contacts
 * @property ProfileUser[] $profileUsers
 * @property Schedule[] $schedules
 */
class ProfileCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'status'], 'integer'],
            [['status'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['image_main', 'images', 'ogrn', 'tariff'], 'string', 'max' => 20],
            [['inn', 'okato'], 'string', 'max' => 18],
            [['okpo'], 'string', 'max' => 15],
            [['okved'], 'string', 'max' => 12],
            [['bik', 'kpp'], 'string', 'max' => 10],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID компании'),
            'type' => Yii::t('app', 'Тип компании'),
            'tariff' => Yii::t('app', 'Тариф'),
            'status' => Yii::t('app', 'Статус'),
            'name' => Yii::t('app', 'Название компании'),
            'description' => Yii::t('app', 'Описание'),
            'image_main' => Yii::t('app', 'Метка изображения'),
            'images' => Yii::t('app', 'Метка изображения доп фото'),
            'inn' => Yii::t('app', 'ИНН'),
            'ogrn' => Yii::t('app', 'ОГРН'),
            'okpo' => Yii::t('app', 'ОКПО'),
            'okved' => Yii::t('app', 'ОКВЭД'),
            'okato' => Yii::t('app', 'ОКАТО'),
            'bik' => Yii::t('app', 'БИК'),
            'kpp' => Yii::t('app', 'КПП'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanks()
    {
        return $this->hasMany(Bank::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfileUsers()
    {
        return $this->hasMany(ProfileUser::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedules()
    {
        return $this->hasMany(Schedule::className(), ['company_id' => 'id']);
    }
}
