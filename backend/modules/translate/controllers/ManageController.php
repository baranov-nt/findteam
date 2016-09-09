<?php
/**
 * Created by PhpStorm.
 * User: phpNT
 * Date: 05.12.2015
 * Time: 10:54
 */

namespace backend\modules\translate\controllers;

use backend\modules\translate\models\SourceMessage;
use backend\modules\translate\models\SourceMessageSearch;
use yii\web\NotFoundHttpException;
use backend\controllers\BehaviorsController;


class ManageController extends BehaviorsController
{
    public function actionIndex()
    {
        $searchModel = SourceMessageSearch::getInstance();
        $dataProvider = $searchModel->search(\Yii::$app->getRequest()->get());
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionRescan()
    {
        $result = \backend\modules\translate\models\SourceMessageSearch::getInstance()->extract();
        \Yii::$app->session->set(
            'message',
            [
                'type'      => 'info',
                'message'   => \Yii::t('app', 'Новых сообщений:') . ' ' . (isset($result['new']) ? $result['new'] : 0).'<br>'.\Yii::t('app', 'Удаленных сообщений:') . ' ' . (isset($result['deleted']) ? $result['deleted'] : 0)
            ]
        );
        return $this->redirect('index');
    }

    public function actionClearCache()
    {
        \Yii::$app->session->set(
            'message',
            [
                'type'      => 'info',
                'message'   => \Yii::t('app', 'Кеш успешно очищен.')
            ]
        );
        \Yii::$app->cache->redis->executeCommand('FLUSHDB');
        return $this->redirect('index');
    }

    public function actionSave($id)
    {
        if (!\Yii::$app->request->isPjax) {
            return $this->redirect(['/translate/manage/index']);
        }
        $modelSourceMessage = \backend\modules\translate\models\SourceMessage::findOne($id);
        $saveTranslation = false;

        if($modelSourceMessage) {
            $saveTranslation = $modelSourceMessage->saveMessages(\Yii::$app->request->post('Messages'));
        }

        if ($saveTranslation) {
            \Yii::$app->cache->redis->executeCommand('FLUSHDB');
            \Yii::$app->session->set(
                'message',
                [
                    'type'      => 'info',
                    'message'   => \Yii::t('app', 'Сообщение успешно сохранено.')
                ]
            );
        } else {
            \Yii::$app->session->set(
                'message',
                [
                    'type'      => 'danger',
                    'message'   => \Yii::t('app', 'Сообщение не сохранено.')
                ]
            );
        }

        return $this->render('_message-tabs', [
            'model' => $modelSourceMessage,
            'key' => $id,
        ]);
    }

    /**
     * @param array|integer $id
     * @return SourceMessage|SourceMessage[]
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $query = SourceMessage::find()->where('id = :id', [':id' => $id]);
        $models = is_array($id)
            ? $query->all()
            : $query->one();
        if (!empty($models)) {
            return $models;
        } else {
            throw new NotFoundHttpException(\Yii::t('app', 'Запрашиваемая страница не существует.'));
        }
    }
}
