<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Войти');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Пожалуйста заполните следующие поля, чтобы войти.') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'username')->textInput(['placeholder' => Yii::t('app', 'Введите логин, емайл или телефон')]) ?>

            <?= $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app', 'Пароль')]) ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => '<div class="col-xs-12">{input} {label}</div>'
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Войти'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
            <div class="form-group">
                <?= Html::a(Yii::t('app', 'Забыли пароль?'), ['site/request-password-reset']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
