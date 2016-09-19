<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\forms\SettingForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use phpnt\bootstrapNotify\BootstrapNotify;
use common\widgets\Switchery\SwitcheryAsset;

$this->title = Yii::t('app', 'Настройка');
$this->params['breadcrumbs'][] = $this->title;

SwitcheryAsset::register($this);
?>
<?= BootstrapNotify::widget(); ?>
<div class="setting-default-index">
    <section class="content">
        <div class="box box-success" style="margin-bottom: 50px;">
            <div class="box-header with-border"></div>
            <?php $form = ActiveForm::begin(['fieldConfig' => [
                'template' => '<div class="col-md-4">{label}</div><div class="col-md-4">{input}</div>
                                    <div class="col-md-12">{hint}</div><div class="col-md-12">{error}</div>'
            ]]); ?>
            <div class="box-body">
                <?= $form->field($model, 'show_all_cities')
                    ->checkbox([
                        'class' => 'js-switch',
                        'template' => '<div class="col-xs-10">{label}</div><div class="col-xs-2 text-right">{input}</div><div class="col-md-12">{error}</div>',
                    ]) ?>

                <?= $form->field($model, 'show_all_countries')
                    ->checkbox([
                        'class' => 'js-switch',
                        'template' => '<div class="col-xs-10">{label}</div><div class="col-xs-2 text-right">{input}</div><div class="col-md-12">{error}</div>',
                    ]) ?>

                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </section>
</div>
