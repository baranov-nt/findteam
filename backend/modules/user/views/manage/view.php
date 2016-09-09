<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Identity */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="identity-view">
    <div class="profile-company-view">
        <section class="content">
            <div class="box box-success" style="margin-bottom: 50px;">
                <div class="box-header with-border"><?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?></div>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        //'alias',
                        'username',
                        'email:email',
                        'full_phone',
                        'description:ntext',
                        /*'status',
                        'image_main',
                        'images',
                        'password_hash',
                        'auth_key',
                        'password_reset_token',
                        'email_confirm_token:email',
                        'created_at',
                        'updated_at',*/
                    ],
                ]) ?>
            </div>
        </section>
    </div>
</div>