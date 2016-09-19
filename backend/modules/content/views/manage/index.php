<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use phpnt\bootstrapNotify\BootstrapNotify;

/* @var $this yii\web\View */
/* @var $searchModel \common\models\search\ContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Контент');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content">
    <div class="box">
        <div class="box-header">
            <div class="col-md-12">
                <?= Html::a(Yii::t('app', 'Добавить контент'), ['create'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <div class="box-body">
            <div class="col-md-12 table-responsive">
                <?php Pjax::begin(['id' => 'pjaxGridBlock']); ?>
                <?= BootstrapNotify::widget() ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'category',
                        'description',
                        'location',
                        'message:ntext',
                        [
                            'attribute' => 'user_id',
                            'label' => Yii::t('app', 'Пользователь'),
                            'value' => function ($model, $key, $index, $column) {
                                /* @var $model \common\models\forms\ContentForm */
                                return $model->user->email;
                            },
                            'filter' => false
                        ],
                        // 'user_id',
                        // 'created_at',
                        // 'updated_at',

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</section>

