<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use phpnt\bootstrapSelect\BootstrapSelectAsset;

/* @var $this yii\web\View */
/* @var $model common\models\ProfileCompany */
/* @var $form yii\widgets\ActiveForm */

BootstrapSelectAsset::register($this);
?>
<div class="box box-success" style="margin-bottom: 50px;">
    <div class="box-header with-border"></div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body">
        <div class="col-md-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>

        <div class="col-md-12">
            <?php /*$model->tariff */?><!--
            --><?/*= $form->field($model, 'tariff')->dropDownList($model->tariffesOfCompanyList, [
                'class'         => 'form-control selectpicker',
                'data' => [
                    'style' => 'btn-primary',
                    'size' => 7,
                    'title' => Yii::t('app', 'Тариф')
                ]])->label(Yii::t('app', 'Тариф')) */?>
        </div>

        <?/*= $form->field($model, 'type')->textInput() */?>

        <?/*= $form->field($model, 'status')->textInput() */?>

        <?/*= $form->field($model, 'image_main')->textInput(['maxlength' => true]) */?>

        <?/*= $form->field($model, 'images')->textInput(['maxlength' => true]) */?>

        <?/*= $form->field($model, 'inn')->textInput(['maxlength' => true]) */?>

        <?/*= $form->field($model, 'ogrn')->textInput(['maxlength' => true]) */?>

        <?/*= $form->field($model, 'okpo')->textInput(['maxlength' => true]) */?>

        <?/*= $form->field($model, 'okved')->textInput(['maxlength' => true]) */?>

        <?/*= $form->field($model, 'okato')->textInput(['maxlength' => true]) */?>

        <?/*= $form->field($model, 'bik')->textInput(['maxlength' => true]) */?>

        <?/*= $form->field($model, 'kpp')->textInput(['maxlength' => true]) */?>

        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Создать компанию') : Yii::t('app', 'Изменить компанию'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
