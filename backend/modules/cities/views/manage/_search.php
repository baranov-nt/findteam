<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $modelGeoCity common\models\GeoCitySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="geo-city-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($modelGeoCity, 'id') ?>

    <?= $form->field($modelGeoCity, 'region_id') ?>

    <?= $form->field($modelGeoCity, 'name_ru') ?>

    <?= $form->field($modelGeoCity, 'name_en') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
