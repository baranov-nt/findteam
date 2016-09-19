<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model \common\models\forms\ContentForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="content-form-form">
    <div class="box box-success" style="margin-bottom: 50px;">
        <div class="box-header with-border"></div>
        <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">

            <div class="col-md-12">
                <?php $model->category = $model->isNewRecord ? 'content' : $model->category; ?>
                <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-md-12">
                <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-md-12">
                <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>
            </div>

            <?= $form->field($model, 'message')->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'full'
            ]) ?>

            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Создать контент') : Yii::t('app', 'Изменить контент'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
