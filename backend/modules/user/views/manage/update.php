<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Identity */

$this->title = Yii::t('app', 'Изменить пользователя: ') . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменить');
?>
<div class="identity-update">
    <section class="content">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </section>
</div>
