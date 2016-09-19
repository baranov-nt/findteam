<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 15.09.2016
 * Time: 12:19
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
class UserForm extends Identity
{
    public $model_scenario;

    public $lang;

    public $type_user;
    public $type_company;
    public $profession_num;

    public $item_name;
    public $tariff_name;

    public $first_name;
    public $last_name;
    public $sex;
    public $day_birth;
    public $month_birth;
    public $year_birth;
    public $name;

    public $city;
    public $city_id;
    public $country_id;
    public $calling_code;
    public $phone_mask;

    public $password;
    public $confirm_password;

    public $new_record = false;

    public function init()
    {
        parent::init();
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'first_name', 'last_name', 'sex', 'day_birth', 'month_birth', 'year_birth',  'email', 'phone', 'city_id', 'country_id', 'model_scenario',
                'password'], 'required',
                'on' => 'user'],
            [['username', 'first_name', 'last_name', 'sex', 'day_birth', 'month_birth', 'year_birth',  'email', 'phone', 'city_id', 'country_id', 'model_scenario'], 'required',
                'on' => 'userUpdate'],
            [['username', 'name', 'email', 'phone', 'city_id', 'country_id', 'model_scenario', 'password'], 'required',
                'on' => 'company'],
            [['country_id', 'city_id', 'country_id', 'type_user', 'type_company', 'type_user'], 'integer'],
            [['calling_code', 'city', 'full_phone', 'name', 'tariff_name', 'item_name', 'model_scenario'], 'string'],
            [['auth_key', 'first_name', 'last_name'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 11],
            ['email', 'email'],
            ['phone', 'validatePhone'],
            ['city', 'validateCity'],
            [['password'], 'string', 'min' => 6, 'max' => 300],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message'=> \Yii::t('app', 'Пароли не совпадают.')],
            [['username', 'full_phone', 'email'], 'unique'],
            [['first_name', 'last_name', 'name', 'email', 'password'], 'trim'],
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
        $fullphone = $this->clearString($this->calling_code.$this->phone);
        $model = self::findOne(['full_phone' => $fullphone]);
        if ($this->isNewRecord && $model) {
            $this->addError('phone', Yii::t('app', 'Этот номер уже занят.'));
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
                //$this->password = \Yii::$app->security->generateRandomString(8);
                $this->status   = self::STATUS_WAIT;
                $this->setFullPhone($this->calling_code.$this->phone);
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generateEmailConfirmToken();
                if ($this->model_scenario == 'user') {
                    $this->item_name = 'user';
                } elseif ($this->model_scenario == 'company') {
                    $this->item_name = 'adminCompany';
                }
                return true;
            } else {
                $this->setFullPhone($this->calling_code.$this->phone);
                if ($this->password != '') {
                    $this->setPassword($this->password);
                }
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
            $auth = \Yii::$app->authManager;
            $role = $auth->getRole($this->item_name);
            $auth->assign($role, $this->id);
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

        if ($this->new_record) {
            $modelProfileCompany               = new ProfileCompany();
            if ($this->scenario == 'company') {
                $modelProfileCompany->tariff   = $this->tariff_name ? $this->tariff_name : null;
                $modelProfileCompany->status   = self::STATUS_COMPANY_ACTIVE;
                $modelProfileCompany->name     = $this->name;
                $modelProfileCompany->save();
            }
        }

        /* @var $modelProfileCompany ProfileCompany */

        $modelProfileUser              = $this->new_record ? new ProfileUser() : ProfileUser::findOne($this->id);
        $modelProfileUser->tariff      = $this->tariff_name ? $this->tariff_name : null;
        $modelProfileUser->first_name  = $this->first_name;
        $modelProfileUser->last_name   = $this->last_name;
        $modelProfileUser->sex         = $this->sex;
        $modelProfileUser->day_birth   = $this->day_birth;
        $modelProfileUser->month_birth = $this->month_birth;
        $modelProfileUser->year_birth  = $this->year_birth;
        $modelProfileUser->age         = date('Y') - $this->year_birth;
        $modelProfileUser->company_id  = isset($modelProfileCompany->id) ? $modelProfileCompany->id : null;

        $this->new_record ? $modelProfileUser->link('id0', $this) : $modelProfileUser->save();
        $model              = $this->new_record ? new Address() : Address::findOne(['user_id' => $this->id, 'main' => 1]);
        $model->type        = self::ADDRESS_LIVE;
        $model->city_id     = $this->city_id;
        $model->country_id  = $this->country_id;
        $model->user_id     = $this->id;
        $model->main        = self::ITEM_MAIN;
        $model->save();

        if ($this->model_scenario == 'company') {
            $model              = $this->new_record ? new Address() : Address::findOne(['user_id' => $modelProfileCompany->id, 'main' => 1]);
            $model->type        = self::ADDRESS_OFFICE;
            $model->city_id     = $this->city_id;
            $model->country_id  = $this->country_id;
            $model->company_id  = $modelProfileCompany->id;
            $model->main        = self::ITEM_MAIN;
            $model->save();
        }
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