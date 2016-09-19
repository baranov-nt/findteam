<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ContentForm */

$this->title = Yii::t('app', 'Изменить контент: ') . $model->description;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Контент'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->description, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменить');
?>
<div class="content-form-update">
    <section class="content">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </section>
</div>
