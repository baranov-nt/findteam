<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 30.06.2015
 * Time: 5:48
 */

namespace frontend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use common\components\UserOnlineBehavior;

class BehaviorsController extends Controller {

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                /*'denyCallback' => function ($rule, $action) {
                    throw new \Exception('Нет доступа.');
                },*/
                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['logout'],
                        'verbs' => ['POST'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['index', 'login', 'signup', 'set-scenario', 'activate-account', 'request-password-reset', 'reset-password',
                            'set-country', 'set-city', 'set-month', 'error'],
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['site'],
                        'actions' => ['main'],
                        'roles' => ['@']
                    ],
                ]
            ],
            'UserOnlineBehavior' => [
                'class' => UserOnlineBehavior::className()
            ]
        ];
    }
}