<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bank".
 *
 * @property integer $id
 * @property integer $owner
 * @property string $name
 * @property string $phone
 * @property string $inn
 * @property string $ogrn
 * @property string $okpo
 * @property string $okved
 * @property string $okato
 * @property string $checking_account
 * @property string $correspondent_account
 * @property string $bik
 * @property string $kpp
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
class Bank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner'], 'required'],
            [['owner', 'address_id', 'user_id', 'company_id', 'main', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
            [['inn', 'okato'], 'string', 'max' => 18],
            [['ogrn'], 'string', 'max' => 20],
            [['okpo'], 'string', 'max' => 15],
            [['okved'], 'string', 'max' => 12],
            [['checking_account', 'correspondent_account'], 'string', 'max' => 22],
            [['bik', 'kpp'], 'string', 'max' => 10],
            [['name'], 'unique'],
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
            'id' => Yii::t('app', 'ID банка'),
            'owner' => Yii::t('app', 'Владелец'),
            'name' => Yii::t('app', 'Название банка'),
            'phone' => Yii::t('app', 'Телефон'),
            'inn' => Yii::t('app', 'ИНН'),
            'ogrn' => Yii::t('app', 'ОГРН'),
            'okpo' => Yii::t('app', 'ОКПО'),
            'okved' => Yii::t('app', 'ОКВЭД'),
            'okato' => Yii::t('app', 'ОКАТО'),
            'checking_account' => Yii::t('app', 'Расчетный счет'),
            'correspondent_account' => Yii::t('app', 'Корреспондентский счет'),
            'bik' => Yii::t('app', 'БИК'),
            'kpp' => Yii::t('app', 'КПП'),
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
