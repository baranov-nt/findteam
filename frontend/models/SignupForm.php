<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 06.08.2016
 * Time: 18:56
 */

namespace frontend\models;

use common\models\Address;
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
class SignupForm extends Identity
{
    public $user_type = self::OWNER_USER;

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
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'first_name', 'last_name', 'email', 'phone', 'password', 'country_id', 'city', 'sex'], 'required', 'on' => 'userSignup'],
            [['username', 'name', 'email', 'phone', 'password', 'country_id', 'city'], 'required', 'on' => 'companySignup'],
            [['country_id', 'city_id', 'day_birth', 'month_birth', 'year_birth', 'age', 'user_type'], 'integer'],
            [['calling_code', 'city', 'username', 'full_phone', 'name'], 'string'],
            ['email', 'email'],
            ['phone', 'validatePhone'],
            ['city', 'validateCity'],
            [['password'], 'string', 'min' => 6, 'max' => 300],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message'=> \Yii::t('app', 'Пароли не совпадают.')],
            [['username', 'phone', 'email'], 'unique'],
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
            'user_type'         => Yii::t('app', 'Регистрация'),
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
                $this->status   = self::STATUS_WAIT;
                $this->setFullPhone($this->calling_code.$this->phone);
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generateEmailConfirmToken();
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
        if ($this->user_type == self::OWNER_USER) {
            $auth = \Yii::$app->authManager;
            $role = $auth->getRole('user');
            $auth->assign($role, $this->id);

            $modelUser              = new ProfileUser();
            $modelUser->first_name  = $this->first_name;
            $modelUser->last_name   = $this->last_name;
            $modelUser->sex         = $this->sex;
            $modelUser->day_birth   = $this->day_birth;
            $modelUser->month_birth = $this->month_birth;
            $modelUser->year_birth  = $this->year_birth;
            $modelUser->age         = date('Y') - $this->year_birth;
            $modelUser->link('id0', $this);

            $model              = new Address();
            $model->type        = self::ADDRESS_LIVE;
            $model->city_id     = $this->city_id;
            $model->country_id  = $this->country_id;
            $model->save();

            $modelUser->link('addresses', $model);

        } elseif($this->user_type == self::OWNER_COMPANY) {
            $auth = \Yii::$app->authManager;
            $role = $auth->getRole('headCompany');
            $auth->assign($role, $this->id);

            $modelCompany           = new ProfileCompany();
            $modelCompany->type     = self::TYPE_COMPANY_USUAL;
            $modelCompany->status   = self::STATUS_COMPANY_WAIT;
            $modelCompany->name     = $this->name;
            $modelCompany->save();

            $model              = new ProfileUser();
            $model->company_id  = $modelCompany->id;
            $model->link('id0', $this);

            $model              = new Address();
            $model->type        = self::ADDRESS_LIVE;
            $model->city_id     = $this->city_id;
            $model->country_id  = $this->country_id;
            $model->save();

            $modelCompany->link('addresses', $model);
        }
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