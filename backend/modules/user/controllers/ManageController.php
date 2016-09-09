<?php

namespace backend\modules\user\controllers;

use common\models\GeoCity;
use common\models\GeoCountry;
use common\models\ProfileUserForm;
use common\models\ProfileUserSearch;
use common\models\User;
use Yii;
use common\models\Identity;
use backend\controllers\BehaviorsController;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 * ManageController implements the CRUD actions for Identity model.
 */
class ManageController extends BehaviorsController
{
    /**
     * Lists all Identity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProfileUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Identity model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Identity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProfileUserForm(['scenario' => 'create']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Identity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /* @var $model ProfileUserForm */
        $model = $this->findModel($id);
        if ($model->profileUser->company_id) {
            $model->scenario = 'updateCompany';
        } else {
            $model->scenario = 'update';
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionMultiactive()
    {
        $models = \Yii::$app->request->post('keys');
        if ($models) {
            foreach ($models as $id) {
                if ($id != \Yii::$app->user->id) {
                    /** @var Identity $model */
                    $model = User::findOne($id);
                    $model->status = Identity::STATUS_ACTIVE;
                    $model->save();
                }
            }
            \Yii::$app->session->set(
                'message',
                [
                    'type'      => 'info',
                    'icon'      => 'glyphicon glyphicon-info-sign',
                    'message'   => ' '.\Yii::t('app', 'Выбранные пользователи успешно активированы.'),
                ]
            );
        }
        $searchModel = new ProfileUserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionMultiblock()
    {
        $keys = \Yii::$app->request->post('keys');
        if ($keys) {
            foreach ($keys as $id) {
                if ($id != \Yii::$app->user->id) {
                    /** @var $model Identity */
                    $model = User::findOne($id);
                    $model->status = Identity::STATUS_BLOCKED;
                    if(!$model->save()) {
                        dd($model->errors);
                    }
                }
            }
            \Yii::$app->session->set(
                'message',
                [
                    'type'      => 'info',
                    'icon'      => 'glyphicon glyphicon-info-sign',
                    'message'   => ' '.\Yii::t('app', 'Выбранные пользователи успешно блокированы.'),
                ]
            );
        }

        $searchModel = new ProfileUserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Identity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Identity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProfileUserForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
