<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $alias
 * @property string $username
 * @property string $email
 * @property string $phone
 * @property string $full_phone
 * @property string $description
 * @property integer $status
 * @property string $image_main
 * @property string $images
 * @property string $password_hash
 * @property string $password_encrypted
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $email_confirm_token
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Address[] $addresses
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 * @property Bank[] $banks
 * @property Contact[] $contacts
 * @property Content[] $contents
 * @property ProfileUser $profileUser
 * @property Schedule[] $schedules
 * @property UserOnline $userOnline
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['status'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['alias', 'password_hash', 'password_encrypted', 'password_reset_token', 'email_confirm_token'], 'string', 'max' => 255],
            [['username', 'auth_key'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 11],
            [['full_phone'], 'string', 'max' => 15],
            [['image_main', 'images'], 'string', 'max' => 20],
            [['alias'], 'unique'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['full_phone'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID пользователя'),
            'alias' => Yii::t('app', 'Уникальная ссылка'),
            'username' => Yii::t('app', 'Логин'),
            'email' => Yii::t('app', 'Электронная почта'),
            'phone' => Yii::t('app', 'Телефон'),
            'full_phone' => Yii::t('app', 'Полный номер'),
            'description' => Yii::t('app', 'Описание'),
            'status' => Yii::t('app', 'Статус'),
            'image_main' => Yii::t('app', 'Метка изображения'),
            'images' => Yii::t('app', 'Метка изображения доп фото'),
            'password_hash' => Yii::t('app', 'Пароль'),
            'password_encrypted' => Yii::t('app', 'Зашифрованный пароль'),
            'auth_key' => Yii::t('app', 'Ключ авторизации'),
            'password_reset_token' => Yii::t('app', 'Ключ сброса пароля'),
            'email_confirm_token' => Yii::t('app', 'Ключ подтверждения эл. адреса'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanks()
    {
        return $this->hasMany(Bank::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContents()
    {
        return $this->hasMany(Content::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfileUser()
    {
        return $this->hasOne(ProfileUser::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchedules()
    {
        return $this->hasMany(Schedule::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOnline()
    {
        return $this->hasOne(UserOnline::className(), ['user_id' => 'id']);
    }
}
