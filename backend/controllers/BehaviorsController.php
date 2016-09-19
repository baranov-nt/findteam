<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 30.06.2015
 * Time: 5:48
 */

namespace backend\controllers;

use common\components\UserOnlineBehavior;
use yii\web\Controller;
use yii\filters\AccessControl;

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
                        'controllers' => ['site'],
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'controllers' => ['site'],
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['redactor'],
                    ],
                    [
                        'controllers' => ['cities/manage'],
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['redactor']
                    ],
                    [
                        'controllers' => ['company/manage'],
                        'actions' => ['index', 'view', 'create', 'update', 'multiactive', 'multiblock'],
                        'allow' => true,
                        'roles' => ['admin']
                    ],
                    [
                        'controllers' => ['countries/manage'],
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['redactor']
                    ],
                    [
                        'controllers' => ['setting/manage'],
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin']
                    ],
                    [
                        'controllers' => ['translate/manage'],
                        'actions' => ['index', 'rescan', 'clear-cache', 'save'],
                        'allow' => true,
                        'roles' => ['redactor']
                    ],
                    [
                        'controllers' => ['user/manage'],
                        'actions' => ['index', 'view', 'create', 'update', 'multiactive', 'multiblock'],
                        'allow' => true,
                        'roles' => ['admin']
                    ],
                ]
            ],
            'UserOnlineBehavior' => [
                'class' => UserOnlineBehavior::className()
            ]
       ];
    }
}