<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = Yii::t('app', 'Сброс пароля');
?>
<div class="site-reset-password">
    <div class="col-md-8 col-md-offset-2">
        <div class="ibox float-e-margins" style="padding-bottom: 60px;">
            <div class="ibox-title">
                <h5><?= Yii::t('app', 'Пожалуйста введите новый пароль') ?></h5>
                <div class="ibox-tools">

                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <?php $form = ActiveForm::begin([
                        'id' => 'form',
                        'fieldConfig' => [
                            'template' => '{label}<div class="input-group">{input}
                            <span class="input-group-addon"><i class="fa fa-{font-awesome}"></i></span>
                         </div><i>{hint}</i>{error}'
                        ]]); ?>

                    <div class="col-md-12">
                        <?= $form->field($model, 'password', ['parts' => ['{font-awesome}' => 'lock']])
                            ->passwordInput(['placeholder' => Yii::t('app', 'Введите новый пароль'), 'autofocus' => true]) ?>
                    </div>

                    <div class="col-md-12">
                        <?= $form->field($model, 'confirm_password', ['parts' => ['{font-awesome}' => 'lock']])
                            ->passwordInput(['placeholder' => Yii::t('app', 'Подтвердите пароль')]) ?>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>