<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use phpnt\bootstrapSelect\BootstrapSelectAsset;
use common\models\forms\GeoCountryForm;

/* @var $this yii\web\View */
/* @var $model \common\models\forms\GeoCountryForm */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="geo-city-form">
    <div class="box box-success">
        <div class="box-header with-border"></div>
        <?php
        BootstrapSelectAsset::register($this);
        ?>
        <?php
        $form = ActiveForm::begin([
            'id' => 'form',
            'action' => Url::to(['/countries/manage/create']),
            'options' => ['data-pjax' => true]]);

        ?>
        <div class="box-body">

            <div class="col-md-12">
                <?= $form->field($model, 'id', [
                    'template' => '{label}{input}{error}'])
                    ->dropDownList(GeoCountryForm::getAllCountriesList(), [
                        'class'  => 'form-control selectpicker',
                        'data' => [
                            'style' => 'btn-primary',
                            'live-search' => true,
                            'size' => 7,
                            'title' => Yii::t('app', 'Выберите страну'),
                        ]])->label(Yii::t('app', 'Страна')) ?>
            </div>

            <?= $form->field($model, 'active', [
                'template' => '{input}'])->hiddenInput(['value' => 1])->label(false); ?>

            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Добавить страну'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>

        </div>
        <?php
        ActiveForm::end();
        ?>
    </div>
</div>