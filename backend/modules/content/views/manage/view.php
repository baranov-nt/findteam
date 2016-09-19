<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \common\models\forms\ContentForm */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Контент'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-form-view">
    <section class="content">
        <div class="box box-success" style="margin-bottom: 50px;">
            <div class="box-header with-border">
        <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить элемент?'),
                'method' => 'post',
            ],
        ]) ?>
            </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'category',
            'description',
            'location',
            'message:ntext',
        ],
    ]) ?>

</div>
