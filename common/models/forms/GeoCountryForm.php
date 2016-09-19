<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 12.09.2016
 * Time: 17:04
 */

namespace common\models\forms;


use common\models\GeoCountry;
use yii\helpers\ArrayHelper;

/**
 * @property array $countriesList
 * @property integer $countryUser
*/

class GeoCountryForm extends GeoCountry
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_ru', 'lat', 'lon', 'timezone', 'short_name', 'long_name'], 'required'],
            [['lat', 'lon'], 'number'],
            [['id', 'phone_number_digits_code', 'system_measure'], 'integer'],
            [['continent', 'iso2'], 'string', 'max' => 2],
            [['name_ru'], 'string', 'max' => 128],
            [['timezone'], 'string', 'max' => 30],
            [['short_name', 'long_name'], 'string', 'max' => 80],
            [['iso3', 'currency'], 'string', 'max' => 3],
            [['numcode'], 'string', 'max' => 6],
            [['un_member'], 'string', 'max' => 12],
            [['calling_code'], 'string', 'max' => 8],
            [['cctld'], 'string', 'max' => 5],
            [['active'], 'boolean']
        ];
    }


    public static function getCallingCode($country)
    {
        $model = self::findOne($country);
        return $model->calling_code;
    }

    public function getCountryUser()
    {
        return $this->mainAddressUser->country_id;
    }

    public static function getAllCountriesList()
    {
        if (\Yii::$app->language == 'ru') {
            $countries = GeoCountry::find()
                ->where(['is not', 'phone_number_digits_code', null])
                ->orderBy('name_ru')
                ->all();
            return ArrayHelper::map($countries, 'id', 'name_ru');
        }
        $countries = GeoCountry::find()
            ->all();
        return ArrayHelper::map($countries, 'id', 'short_name');
    }

    public static function getCountriesList()
    {
        if (\Yii::$app->setting->show_all_countries == null) {
            if (\Yii::$app->language == 'ru') {
                $countries = GeoCountry::find()
                    ->where(['is not', 'phone_number_digits_code', null])
                    ->andWhere(['active' => 1])
                    ->orderBy('name_ru')
                    ->all();
                return ArrayHelper::map($countries, 'id', 'name_ru');
            }
            $countries = GeoCountry::find()
                ->all();
            return ArrayHelper::map($countries, 'id', 'short_name');
        }
        if (\Yii::$app->language == 'ru') {
            $countries = GeoCountry::find()
                ->where(['is not', 'phone_number_digits_code', null])
                ->orderBy('name_ru')
                ->all();
            return ArrayHelper::map($countries, 'id', 'name_ru');
        }
        $countries = GeoCountry::find()
            ->all();
        return ArrayHelper::map($countries, 'id', 'short_name');
    }
}