<?php
/* @var $this yii\web\View */
/* @var $searchModel \common\models\search\ProfileUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use phpnt\bootstrapNotify\BootstrapNotify;

$this->title = Yii::t('app', 'Пользователи');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content">
    <div class="box">
        <div class="box-header">
            <div class="col-md-12">
                <?= Html::a(Yii::t('app', 'Создать пользователя'), ['create'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <div class="box-body">
            <?php Pjax::begin(['id' => 'pjaxGridBlock']); ?>
            <div class="col-md-12 table-responsive">
                <?= BootstrapNotify::widget() ?>
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
                        //'alias',
                        'username',
                        'email:email',
                        //'phone',
                        'full_phone',
                        [
                            'attribute' => 'item_name',
                            'label' => Yii::t('app', 'Роль'),
                            'value' => function ($model, $key, $index, $column) {
                                // @var $model \common\models\Identity
                                return $model->roleDescription;
                            },
                            'filter' => $searchModel->rolesList,
                        ],
                        [
                            'attribute' => 'tariff_name',
                            'label' => Yii::t('app', 'Тариф'),
                            'value' => function ($model, $key, $index, $column) {
                                /* @var $model \common\models\Identity */
                                return $model->tariffDescription;
                            },
                            'filter' => $searchModel->tariffesList,
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->statusUser;
                            },
                            'filter' => $searchModel->statusList,
                        ],
                        [
                            'attribute' => 'online',
                            'label' => Yii::t('app', 'Онлайн'),
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                /* @var $model \common\models\Identity */
                                if (isset($model->userOnlineForm->onlineMark)) {
                                    return $model->userOnlineForm->onlineMark;
                                }
                                return null;
                            },
                            'filter' => false,
                        ],
                        [
                            'attribute' => 'company_id',
                            'label' => Yii::t('app', 'Компания'),
                            'value' => function ($model) {
                                /* @var $model \common\models\Identity */
                                if (isset($model->profileUser->company->name)) {
                                    return $model->profileUser->company->name;
                                }
                                return false;
                            },
                        ],

                        // 'description:ntext',
                        // 'status',
                        // 'image_main',
                        // 'images',
                        // 'password_hash',
                        // 'auth_key',
                        // 'password_reset_token',
                        // 'email_confirm_token:email',
                        // 'created_at',
                        // 'updated_at',

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
                                            url: "' . Url::to(['/user/manage/multiactive']) . '",
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
                                            url: "' . Url::to(['/user/manage/multiblock']) . '",
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
