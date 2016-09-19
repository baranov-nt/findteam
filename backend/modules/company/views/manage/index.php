<?php
/* @var $this yii\web\View */
/* @var $searchModel common\models\ProfileCompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use phpnt\bootstrapNotify\BootstrapNotify;

$this->title = Yii::t('app', 'Компании');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content">
    <div class="box">
        <div class="box-header">
            <div class="col-md-12">
                <?= Html::a(Yii::t('app', 'Создать компанию'), ['create'], ['class' => 'btn btn-success']) ?>
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
                        //['class' => 'yii\grid\SerialColumn'],
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {update}',
                        ],
                        'id',
                        'name',
                        [
                            'label' => Yii::t('app', 'Администратор'),
                            'format' => 'raw',
                            'value' => function ($model) {
                                /* @var $model \common\models\ProfileCompanyIdentity */
                                return $model->adminCompanyName;
                            },
                            'filter' => $searchModel->statusList,
                        ],
                        [
                            'label' => Yii::t('app', 'Статус'),
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->statusCompany;
                            },
                            'filter' => $searchModel->statusList,
                        ],

                        /*'id',
                        'type',
                        'status',

                        'description:ntext',*/
                        // 'image_main',
                        // 'images',
                        // 'inn',
                        // 'ogrn',
                        // 'okpo',
                        // 'okved',
                        // 'okato',
                        // 'bik',
                        // 'kpp',

                        //['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
            <div class="col-md-6">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= Yii::t('app', 'Выбранные') ?></h3>
                    </div>
                    <div class="box-body">
                        <?= Html::button('<i class="fa fa-check" aria-hidden="true"></i> '.Yii::t('app', 'Активировать'),
                            [
                                'class' => 'btn btn-success',
                                'onclick' => '
                var keys = $(".grid-view").yiiGridView("getSelectedRows");
                $.pjax({
                    type: "POST",
                    url: "' . Url::to(['/company/manage/multiactive']) . '",
                    data:{keys: keys},
                    container: "#pjaxGridBlock",
                    push: false,
                    scrollTo: false
                })'
                            ])?>
                        <?= Html::button('<i class="fa fa-ban" aria-hidden="true"></i> '.Yii::t('app', 'Заблокировать'),
                            [
                                'class' => 'btn btn-danger',
                                'onclick' => '
                var keys = $(".grid-view").yiiGridView("getSelectedRows");
                $.pjax({
                    type: "POST",
                    url: "' . Url::to(['/company/manage/multiblock']) . '",
                    data:{keys: keys},
                    container: "#pjaxGridBlock",
                    push: false,
                    scrollTo: false
                })'
                            ])?>
                    </div>
                </div>
            </div>
            <?php Pjax::end(); ?>
        </div>
    </div>
</section>