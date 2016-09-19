<?php

namespace backend\modules\company\controllers;

use common\models\forms\UserForm;
use common\models\Identity;
use common\models\ProfileCompanyIdentity;
use Yii;
use common\models\ProfileCompany;
use common\models\search\ProfileCompanySearch;
use backend\controllers\BehaviorsController;
use yii\web\NotFoundHttpException;

/**
 * ManageController implements the CRUD actions for ProfileCompany model.
 */
class ManageController extends BehaviorsController
{
    /**
     * Lists all ProfileCompany models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProfileCompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProfileCompany model.
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
     * Creates a new ProfileCompany model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserForm(['scenario' => 'company']);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->profileUser->company_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProfileCompany model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

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
                    /** @var ProfileCompany $model */
                    $model = ProfileCompany::findOne($id);
                    $model->status = Identity::STATUS_COMPANY_ACTIVE;
                    $model->save();
                }
            }
            \Yii::$app->session->set(
                'message',
                [
                    'type'      => 'info',
                    'icon'      => 'glyphicon glyphicon-info-sign',
                    'message'   => ' '.\Yii::t('app', 'Выбранные компании успешно активированы.'),
                ]
            );
        }

        $searchModel = new ProfileCompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
                    /** @var $model ProfileCompany */
                    $model = ProfileCompany::findOne($id);
                    $model->status = Identity::STATUS_COMPANY_BLOCKED;
                    $model->save();
                }
            }
            \Yii::$app->session->set(
                'message',
                [
                    'type'      => 'info',
                    'icon'      => 'glyphicon glyphicon-info-sign',
                    'message'   => ' '.\Yii::t('app', 'Выбранные компании успешно блокированы.'),
                ]
            );
        }

        $searchModel = new ProfileCompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Finds the ProfileCompany model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProfileCompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProfileCompanyIdentity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
