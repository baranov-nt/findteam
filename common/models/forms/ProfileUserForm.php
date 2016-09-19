<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 06.08.2016
 * Time: 18:56
 */

namespace common\models\forms;

use common\models\Address;
use common\models\AuthAssignment;
use common\models\GeoCity;
use common\models\GeoCountry;
use common\models\GeoRegion;
use common\models\Identity;
use common\models\ProfileCompany;
use common\models\ProfileUser;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 *
 */
class ProfileUserForm extends Identity
{
    public $type;
    public $account_type;
    public $item_name;
    public $tariff_name;

    public $first_name;
    public $last_name;
    public $sex;
    public $day_birth;
    public $month_birth;
    public $year_birth;
    public $age;
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

        $cache = \Yii::$app->cache;
        $setting = $cache->get('setting');

        $model = GeoCountry::findOne(Yii::$app->geoData->country);
        if (($setting->show_all_countries == '1') || (isset($model) && $setting->show_all_countries == '0' && $model->active == 1)) {
            if ($this->country_id == null && Yii::$app->geoData->country) {
                $this->country_id = Yii::$app->geoData->country;
            }
            if ($this->country_id != null) {
                $this->calling_code = GeoCountryForm::getCallingCode($this->country_id);
                $this->city_id      = Yii::$app->geoData->city;
            }
        }
        $this->account_type = self::ACCOUNT_USER;
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'first_name', 'last_name', 'email', 'phone', 'country_id', 'city', 'sex', 'password', 'day_birth', 'month_birth', 'year_birth', 'type'],
                'required', 'on' => 'create'],
            [['username', 'email', 'phone', 'country_id', 'city', 'sex', 'type'], 'required', 'on' => 'update'],
            [['username', 'email', 'phone', 'country_id', 'city'], 'required', 'on' => 'updateCompany'],
            [['country_id', 'city_id', 'day_birth', 'month_birth', 'year_birth', 'age', 'account_type', 'sex', 'type'], 'integer'],
            [['calling_code', 'city', 'username', 'full_phone', 'name', 'tariff_name', 'item_name'], 'string'],
            [['username', 'auth_key', 'first_name', 'last_name'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 11],
            ['email', 'email'],
            ['phone', 'validatePhone'],
            ['city', 'validateCity'],
            [['password'], 'string', 'min' => 6, 'max' => 300],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message'=> \Yii::t('app', 'Пароли не совпадают.')],
            [['username', 'phone', 'email'], 'unique'],
            ['new_record', 'boolean'],
            [['username', 'first_name', 'last_name', 'email', 'password'], 'trim'],
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
            'account_type'      => Yii::t('app', 'Регистрация'),
            'item_name'         => Yii::t('app', 'Рoль'),
            'city'              => Yii::t('app', 'Город'),
            'tariff_name'       => Yii::t('app', 'Тариф'),
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
        $this->calling_code = $model->calling_code;
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
                $role = $auth->getRole($this->item_name);
                $auth->assign($role, $this->id);
            } else {
                $auth = \Yii::$app->authManager;
                $role = $auth->getRole('user');
                $auth->assign($role, $this->id);
            }
        } else {
            if ($this->item_name) {
                AuthAssignment::deleteAll(['item_name' => $this->roleName, 'user_id' => $this->id]);
            }
            $auth = \Yii::$app->authManager;
            $role = $auth->getRole($this->item_name);
            $auth->assign($role, $this->id);
        }

        if ($this->tariff_name) {
            AuthAssignment::deleteAll(['item_name' => $this->tariffName, 'user_id' => $this->id]);
            $auth = \Yii::$app->authManager;
            $role = $auth->getPermission($this->tariff_name);
            $auth->assign($role, $this->id);
        }

        $modelUser              = $this->new_record ? new ProfileUser() : ProfileUser::findOne($this->id);
        $modelUser->type        = $this->type;
        $modelUser->tariff      = $this->tariff_name ? $this->tariff_name : null;
        $modelUser->first_name  = $this->first_name;
        $modelUser->last_name   = $this->last_name;
        $modelUser->sex         = $this->sex;
        $modelUser->day_birth   = $this->day_birth;
        $modelUser->month_birth = $this->month_birth;
        $modelUser->year_birth  = $this->year_birth;
        $modelUser->age         = date('Y') - $this->year_birth;
        $this->new_record ? $modelUser->link('id0', $this) : $modelUser->save();

        $model              = $this->new_record ? new Address() : Address::findOne(['user_id' => $this->id, 'main' => 1]);
        $model->type        = self::ADDRESS_LIVE;
        $model->city_id     = $this->city_id;
        $model->country_id  = $this->country_id;
        $model->user_id     = $this->id;
        $model->main        = self::ITEM_MAIN;
        $model->save();
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function afterFind()
    {
        $this->first_name = $this->profileUser->first_name;
        $this->last_name = $this->profileUser->last_name;
        $this->day_birth = $this->profileUser->day_birth;
        $this->month_birth = $this->profileUser->month_birth;
        $this->year_birth = $this->profileUser->year_birth;
        $this->sex = $this->profileUser->sex;
        $this->country_id = $this->mainAddressUser->country_id;
        $this->city = GeoCityForm::getCityName($this->mainAddressUser);
        $this->city_id = $this->mainAddressUser->city_id;
        $this->calling_code = $this->mainAddressUser->country->calling_code;
        $this->item_name = $this->roleName;
        $this->tariff_name = $this->tariffName;
        return true;
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