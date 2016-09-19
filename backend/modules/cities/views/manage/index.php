<?php
/* @var $this yii\web\View */
/* @var $searchModel common\models\GeoCitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;

$this->title = Yii::t('app', 'Управление городами');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="geo-city-index">
    <section class="content">
        <div class="box">
            <div class="box-header">
                <div class="col-md-12">
                    <?= Html::a(Yii::t('app', 'Добавить город'), ['create'], ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <div class="box-body">
                <?php Pjax::begin(['id' => 'pjaxGridBlock']); ?>
                <?= BootstrapNotify::widget() ?>
                <div class="col-md-12 table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'name_ru',
                            'name_en',
                            [
                                'attribute' => 'timezone',
                                'value' => function ($model) {
                                    /* @var $model \common\models\GeoCityForm */
                                    return $model->region->timezone;
                                },
                                'filter' => false,
                            ],
                            'lat',
                            'lon',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{delete}',
                            ],
                        ],
                    ]); ?>
                </div>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </section>
</div>
