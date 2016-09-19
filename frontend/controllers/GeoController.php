<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 06.09.2016
 * Time: 14:51
 */

namespace frontend\controllers;

use common\models\GeoCity;
use common\models\GeoCountry;
use common\models\forms\ProfileUserForm;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class GeoController extends Controller
{
    public function actionSetCountry()
    {
        /* @var $model ProfileUserForm */
        $id = Yii::$app->request->post('id');
        $model = Yii::$app->request->post('model');
        $model = $id ? $model::findOne($id) : new $model;
        $model->scenario = Yii::$app->request->post('scenario');
        $model->load(Yii::$app->request->post());
        if (isset($model->phone)) {
            $model->phone = null;
        }
        if ($model->country_id == Yii::$app->geoData->country) {
            $model->city_id     = Yii::$app->geoData->city;
        } else {
            $model->city        = null;
            $model->city_id     = null;
        }
        return $this->render(Yii::$app->request->post('form'), ['model' => $model]);
    }

    public function actionSetCity($q)
    {
        /* @var $model ProfileUserForm */
        $country_id = Yii::$app->request->get()['id'];
        $modelCountry = GeoCountry::findOne($country_id);

        $results = [];

        if (Yii::$app->language == 'ru') {
            $model = GeoCity::find()
                ->joinWith('region')
                ->where(['like', 'geo_city.name_ru', $q])
                ->andWhere(['geo_region.country' => $modelCountry->iso2])
                ->all();

            foreach ($model as $one) {
                $results[] = [
                    'id'        => $one['id'],
                    'city'      => $one->name_ru,
                    'region'    => $one->region->name_ru,
                ];
            }
        } else {
            $model = GeoCity::find()
                ->joinWith('region')
                ->where(['like', 'geo_city.name_en', $q])
                ->andWhere(['geo_region.country' => $modelCountry->iso2])
                ->all();

            foreach ($model as $one) {
                $results[] = [
                    'id'        => $one['id'],
                    'city'      => $one->name_en,
                    'region'    => $one->region->name_en,
                ];
            }
        }
        echo Json::encode($results);
    }
}