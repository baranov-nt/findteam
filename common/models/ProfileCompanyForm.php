<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 06.09.2016
 * Time: 15:14
 */

namespace common\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 *
 */
class ProfileCompanyForm extends Identity
{
    public $type;
    public $inn;
    public $ogrn;
    public $okpo;
    public $okved;
    public $okato;
    public $bik;
    public $kpp;

    public $account_type;
    public $item_name;
    public $tariff_name;

    public $country_id;
    public $city;
    public $city_id;
    public $calling_code;
    public $phone_mask;

    public $name;

    public $password;
    public $confirm_password;

    public $new_record = false;

    public function init()
    {
        parent::init();
        if ($this->country_id == null && Yii::$app->geoData->country) {
            $this->country_id = Yii::$app->geoData->country;
        }
        if ($this->country_id != null) {
            $this->calling_code = $this->callingCode;
            $this->phone_mask   = $this->phoneMask;
            $this->city         = $this->getCityName(Yii::$app->geoData->city);
            $this->city_id      = Yii::$app->geoData->city;
        }
        $this->account_type = self::ACCOUNT_COMPANY;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'email', 'phone', 'country_id', 'city', 'password'], 'required', 'on' => 'create'],
            [['country_id', 'city_id','account_type'], 'integer'],
            [['calling_code', 'city', 'username', 'full_phone', 'name', 'tariff_name', 'item_name'], 'string'],
            [['username', 'auth_key'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 11],
            ['email', 'email'],
            ['phone', 'validatePhone'],
            ['city', 'validateCity'],
            [['password'], 'string', 'min' => 6, 'max' => 300],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message'=> \Yii::t('app', 'Пароли не совпадают.')],
            [['username', 'phone', 'email'], 'unique'],
            ['new_record', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        $labels = Identity::attributeLabels();
        $model =  new ProfileUser();
        $labels += $model->attributeLabels();
        $model =  new ProfileCompany();
        $labels += $model->attributeLabels();
        $model =  new GeoCity();
        $labels += $model->attributeLabels();
        $model =  new GeoRegion();
        $labels += $model->attributeLabels();
        $model =  new GeoCountry();
        $labels += $model->attributeLabels();
        $model =  new Address();
        $labels += $model->attributeLabels();
        $labels += [
            'account_type'         => Yii::t('app', 'Регистрация'),
            'city'              => Yii::t('app', 'Город'),
            'password'          => Yii::t('app', 'Пароль'),
            'confirm_password'  => Yii::t('app', 'Повторите пароль'),
        ];
        return $labels;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    public function validatePhone() {
        $phone = $this->clearString($this->phone);
        $model = GeoCountry::findOne($this->country_id);
        if ($model->phone_number_digits_code != strlen($phone)) {
            $this->addError('phone', Yii::t('app', 'Не верный номер телефона'));
        } else {
            $this->phone = $phone;
        }
    }

    public function validateCity() {
        if ($this->city_id == null)
            $this->addError('city', Yii::t('app', 'Город не найден'));
        $model = GeoCity::findOne($this->city_id);
        if (!$model)
            $this->addError('city', Yii::t('app', 'Город не найден'));
        if (Yii::$app->language == 'ru') {
            if ($model->name_ru != $this->city)
                $this->addError('city', Yii::t('app', 'Город не найден'));
        } else {
            if ($model->name_en != $this->city) {
                $this->addError('city', Yii::t('app', 'Город не найден'));
            }
        }
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->new_record = true;
                $this->status   = self::STATUS_WAIT;
                $this->setFullPhone($this->calling_code.$this->phone);
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generateEmailConfirmToken();
                return true;
            } else {
                $this->setFullPhone($this->calling_code.$this->phone);
                $this->setPassword($this->password);
                return true;
            }
        }
        return false;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->new_record) {
            if ($this->item_name) {
                $auth = \Yii::$app->authManager;
                $role = $this->item_name;
                $auth->assign($role, $this->id);
            } else {
                $auth = \Yii::$app->authManager;
                $role = $auth->getRole('adminCompany');
                $auth->assign($role, $this->id);
            }
        } else {
            $model = AuthAssignment::findOne(['user_id' => $this->id]);
            $model->item_name = $this->item_name;
            $model->save();
        }

        $modelCompany           = new ProfileCompany();
        $modelCompany->type     = self::TYPE_COMPANY_USUAL;
        $modelCompany->tariff   = $this->tariff_name ? $this->tariff_name : null;
        $modelCompany->status   = self::STATUS_COMPANY_WAIT;
        $modelCompany->name     = $this->name;
        $modelCompany->save();

        $modelUser              = $this->new_record ? new ProfileUser() : ProfileUser::findOne($this->id);
        $modelUser->type        = self::TYPE_USER_USUAL;
        $modelUser->company_id  = $modelCompany->id;
        $this->new_record ? $modelUser->link('id0', $this) : $modelUser->save();

        $model              = $this->new_record ? new Address() : Address::findOne(['user_id' => $this->id, 'main' => 1]);
        $model->type        = self::ADDRESS_OFFICE;
        $model->city_id     = $this->city_id;
        $model->country_id  = $this->country_id;
        $model->company_id  = $modelCompany->id;
        $model->main        = self::ITEM_MAIN;
        $model->save();

        $model              = $this->new_record ? new Address() : Address::findOne(['user_id' => $this->id, 'main' => 1]);
        $model->type        = self::ADDRESS_LIVE;
        $model->city_id     = $this->city_id;
        $model->country_id  = $this->country_id;
        $model->user_id     = $modelUser->id;
        $model->main        = self::ITEM_MAIN;
        $model->save();
    }

    public function sendActivationEmail($model)
    {
        return \Yii::$app->mailer->compose('activationEmail', ['user' => $model])
            ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::t('app', '{app_name} (отправлено роботом).', ['app_name' => \Yii::$app->name])])
            ->setTo($this->email)
            ->setSubject(\Yii::t('app', 'Активация для {app_name}.', ['app_name' => \Yii::$app->name]))
            ->send();
    }
}