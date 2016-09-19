<?php

namespace backend\modules\setting\controllers;

use backend\controllers\BehaviorsController;
use common\models\forms\SettingForm;
use Yii;

/**
 * Default controller for the `setting` module
 */
class ManageController extends BehaviorsController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model = SettingForm::findOne(1);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('index', ['model' => $model]);
    }
}
