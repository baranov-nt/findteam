<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 22.07.2015
 * Time: 7:09
 */

namespace common\components;

use common\models\UserOnline;
use Yii;
use yii\base\Behavior;
use yii\web\Controller;

class UserOnlineBehavior extends Behavior {

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction'
        ];
    }

    public function beforeAction()
    {
        if (!Yii::$app->user->isGuest) {
            $model = UserOnline::findOne(Yii::$app->user->id);
            if (!$model) {
                $model = new UserOnline();
                $model->user_id = Yii::$app->user->id;
                $model->online  = time();
                $model->save();
            } else {
                $model->updateAttributes(['online' => time()]);
            }
        }
    }
}