<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 10.08.2016
 * Time: 11:12
 */

namespace common\models;

use common\models\forms\UserOnlineForm;
use yii\web\IdentityInterface;

/**
 * @property string $callingCode
 *
 * @property array $dayBirthList
 * @property array $monthBirthList
 * @property array $yearBirthList
 * @property string $phoneMask
 * @property array $sexList
 * @property array $userTypeList
 * @property array $userAccountTypeList
 *
 * @property integer $adminUserId
 *
 * @property string $roleDescription
 * @property string $roleName
 * @property array $rolesList
 * @property array $rolesOfUserList
 * @property array $rolesOfCompanyList
 * @property array $tariffesOfUserList
 * @property string $statusUser
 * @property array $statusList
 * @property string $tariffDescription
 * @property string $tariffName
 * @property array $tariffesList
 *
 * @property Address $mainAddressUser
 * @property AuthAssignment $roleAssignment
 * @property AuthAssignment $assignment
 * @property UserOnlineForm $userOnlineForm
 */
class Identity extends User implements IdentityInterface
{
    /* Тип элемента */
    const ITEM_MAIN     = 1;
    const ITEM_SECOND   = 0;

    /* Тип аккаунта */
    const ACCOUNT_USER      = 1;
    const ACCOUNT_COMPANY   = 2;

    /* Статусы пользователя */
    const STATUS_WAIT   = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCKED = 2;

    /* Статусы компании */
    const STATUS_COMPANY_WAIT   = 0;
    const STATUS_COMPANY_ACTIVE = 1;
    const STATUS_COMPANY_BLOCKED = 2;

    /* Тип пользователя */
    const TYPE_USER_USUAL   = 1;

    /* Тип компании */
    const TYPE_COMPANY_USUAL   = 1;

    /* Тип аккаунта */
    const OWNER_USER     = 0;
    const OWNER_COMPANY  = 1;

    /* Пол пользователя */
    const SEX_FEMALE    = 1;
    const SEX_MALE      = 2;

    /* Типы адресов */
    const ADDRESS_LIVE      = 0;
    const ADDRESS_POST      = 1;
    const ADDRESS_LEGAL     = 2;
    const ADDRESS_OFFICE    = 3;
    const ADDRESS_STORE     = 4;
    const ADDRESS_SHOWROOM  = 5;

    /* Получение данных */
    public function getAdminUserId()
    {
        $admin = '';
            foreach ($this->authAssignments as $one) {
                /* @var $one AuthAssignment */
                if ($one->item_name == 'adminCompany') {
                    $admin = $one->user->id;
                }
        }
        return $admin;
    }

    public function getDayBirthList()
    {
        $i = 1;
        $items = [];
        while($i <= 31) {
            $items[$i] = $i;
            $i++;
        }
        return $items;
    }

    public function getMonthBirthList()
    {
        return [
            1 => \Yii::t('app', 'январь'),
            2 => \Yii::t('app', 'февраль'),
            3 => \Yii::t('app', 'март'),
            4 => \Yii::t('app', 'апрель'),
            5 => \Yii::t('app', 'май'),
            6 => \Yii::t('app', 'июнь'),
            7 => \Yii::t('app', 'июль'),
            8 => \Yii::t('app', 'август'),
            9 => \Yii::t('app', 'сентябрь'),
            10 => \Yii::t('app', 'октябрь'),
            11 => \Yii::t('app', 'ноябрь'),
            12 => \Yii::t('app', 'декабрь')
        ];
    }

    public function getYearBirthList()
    {
        $year = date('Y') - 14;
        $i = 1;
        $items = [];
        while($i <= 100) {
            $items[$year - $i] = $year - $i;
            $i++;
        }
        return $items;
    }

    public function getPhoneMask()
    {
        $model = GeoCountry::findOne($this->country_id);
        $i = 1;
        $phoneMask = '';
        if($model->phone_number_digits_code) {
            while($i <= $model->phone_number_digits_code) {
                $phoneMask .= '9';
                $i++;
            }
        } else {
            while($i <= 11) {
                $phoneMask .= '9';
                $i++;
            }
        }
        return $phoneMask;
    }

    public static function getSexList()
    {
        return [
            self::SEX_FEMALE    => \Yii::t('app', 'Женский'),
            self::SEX_MALE      =>  \Yii::t('app', 'Мужской'),
        ];
    }

    public function getRoleName()
    {
        /* @var $model AuthAssignment */
        $model = AuthAssignment::find()
            ->joinWith('itemName')
            ->where(['user_id' => $this->id])
            ->andWhere(['type' => AuthItem::TYPE_ROLE])
            ->one();
        if ($model) {
            return $model->itemName->name;
        }
        return false;
    }

    public function getRoleDescription()
    {
        /* @var $model AuthAssignment */
        $model = AuthAssignment::find()
            ->joinWith('itemName')
            ->where(['user_id' => $this->id])
            ->andWhere(['type' => AuthItem::TYPE_ROLE])
            ->one();
        if ($model) {
            return $model->itemName->description;
        }
        return false;
    }

    public function getRolesList()
    {
        $roles = [];

        foreach (AuthItem::getRoles() as $one)
        {
            /* @var $one AuthItem */
            $roles[$one->name] = $one->description;
        }
        return $roles;
    }

    public static function getRolesOfUserList()
    {
        $roles = [];

        foreach (AuthItem::getUserRoles() as $one)
        {
            /* @var $one AuthItem */
            $roles[$one->name] = $one->description;
        }
        return $roles;
    }

    public static function getRolesOfCompanyList()
    {
        $roles = [];

        foreach (AuthItem::getCompanyRoles() as $one)
        {
            /* @var $one AuthItem */
            $roles[$one->name] = $one->description;
        }
        return $roles;
    }

    public function getTariffDescription()
    {
        /* @var $model AuthAssignment */
        $model = AuthAssignment::find()
            ->joinWith('itemName')
            ->where(['user_id' => $this->id])
            ->andWhere(['type' => AuthItem::TYPE_PERMISSION])
            ->one();
        if ($model) {
            return $model->itemName->description;
        }
        return false;
    }

    public function getTariffName()
    {
        /* @var $model AuthAssignment */
        $model = AuthAssignment::find()
            ->joinWith('itemName')
            ->where(['user_id' => $this->id])
            ->andWhere(['type' => AuthItem::TYPE_PERMISSION])
            ->one();
        if ($model) {
            return $model->itemName->name;
        }
        return '';
    }

    public function getTariffesList()
    {
        $roles = [];

        foreach (AuthItem::getTariffes() as $one)
        {
            /* @var $one AuthItem */
            $roles[$one->name] = $one->description;
        }
        return $roles;
    }

    public static function getTariffesOfUserList()
    {
        $roles = [];

        foreach (AuthItem::getUserTariffes() as $one)
        {
            /* @var $one AuthItem */
            $roles[$one->name] = $one->description;
        }
        return $roles;
    }

    public function getStatusUser()
    {
        switch ($this->status) {
            case self::STATUS_BLOCKED:
                return '<span class="label label-danger">
                            <i class="fa fa-ban" aria-hidden="true"></i> '.$this->getStatusList()[self::STATUS_BLOCKED].'</span>';
                break;
            case self::STATUS_WAIT:
                return '<span class="label label-warning">
                            <i class="glyphicon glyphicon-hourglass"></i> '.$this->getStatusList()[self::STATUS_WAIT].'</span>';
                break;
            case self::STATUS_ACTIVE:
                return '<span class="label label-success">
                            <i class="glyphicon glyphicon-ok"></i> '.$this->getStatusList()[self::STATUS_ACTIVE].'</span>';
                break;
        }
        return false;
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_BLOCKED => \Yii::t('app', 'Заблокирован'),
            self::STATUS_ACTIVE => \Yii::t('app', 'Активен'),
            self::STATUS_WAIT =>  \Yii::t('app', 'Не активен'),
        ];
    }

    public static function getUserRolesList()
    {
        return [
            self::OWNER_USER     => \Yii::t('app', 'Пользователь'),
            self::OWNER_COMPANY  => \Yii::t('app', 'Компания'),
        ];
    }

    public static function getUserTypeList()
    {
        return [
            self::TYPE_USER_USUAL   => \Yii::t('app', 'Пользователь'),
        ];
    }

    public static function getUserAccountTypeList()
    {
        return [
            self::ACCOUNT_USER      => \Yii::t('app', 'Пользователь'),
            self::ACCOUNT_COMPANY   => \Yii::t('app', 'Компания'),
        ];
    }

    /* Служебные методы */
    /* ------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    public function clearString($string)
    {
        return str_replace(['\\', '_', '-', ' ', '(', ')'], '', $string);
    }

    public static function encript($data)
    {
        $key_size =  strlen($data);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, \Yii::$app->params['secret_key'],
            $data, MCRYPT_MODE_CBC, $iv);
        $ciphertext = $iv . $ciphertext;
        return $ciphertext_base64 = base64_encode($ciphertext);
    }

    public static function decript($data)
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $ciphertext_dec = base64_decode($data);
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);
        $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, \Yii::$app->params['secret_key'],
            $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
        return str_replace("\0", "", $plaintext_dec);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public static function findByPhone($phone)
    {
        $phone = self::clearString($phone);
        return static::findOne(['full_phone' => $phone]);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::find()
            ->where(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT])
            ->one();
    }

    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = \Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = \Yii::$app->security->generateRandomString() . '_' . time();
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function setFullPhone($phone)
    {
        $this->full_phone = $this->clearString($phone);
    }

    public function setPassword($password)
    {
        $this->password_hash = \Yii::$app->security->generatePasswordHash($password);
        $this->password_encrypted   = self::encript($password);
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /* Дополнительные связи */
    public function getRoleAssignment()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id'])
            ->joinWith('itemName')->where(['type' => AuthItem::TYPE_ROLE]);
    }

    public function getAssignment()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }

    public function getMainAddressUser() {
        return $this->hasOne(Address::className(), ['user_id' => 'id'])->where(['main' => true]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserOnlineForm()
    {
        return $this->hasOne(UserOnlineForm::className(), ['user_id' => 'id']);
    }

    /* Классы идентификации */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
}