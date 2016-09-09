<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProfileCompanySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="profile-company-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'image_main') ?>

    <?php // echo $form->field($model, 'images') ?>

    <?php // echo $form->field($model, 'inn') ?>

    <?php // echo $form->field($model, 'ogrn') ?>

    <?php // echo $form->field($model, 'okpo') ?>

    <?php // echo $form->field($model, 'okved') ?>

    <?php // echo $form->field($model, 'okato') ?>

    <?php // echo $form->field($model, 'bik') ?>

    <?php // echo $form->field($model, 'kpp') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
