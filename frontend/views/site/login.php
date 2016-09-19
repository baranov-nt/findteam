<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\widgets\ICheck\ICheckAsset;
use yii\helpers\Url;

ICheckAsset::register($this);

$this->title = Yii::t('app', 'Войти');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="col-md-8 col-md-offset-2">
        <div class="ibox float-e-margins" style="padding-bottom: 60px;">
            <div class="ibox-title">
                <h5><?= Yii::t('app', 'Пожалуйста, заполните следующие поля, чтобы войти.') ?></h5>
                <div class="ibox-tools">

                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-6 b-r">
                        <h3 class="m-t-none m-b"><?= Yii::t('app', 'Текст заголовка формы') ?></h3>
                        <?php $form = ActiveForm::begin([
                            'id' => 'form',
                            'action' => Url::to(['/site/login']),
                            'fieldConfig' => [
                                'template' => '{label}<div class="input-group">{input}
                            <span class="input-group-addon"><i class="fa fa-{font-awesome}"></i></span>
                         </div><i>{hint}</i>{error}'
                            ]]); ?>
                        <?= $form->field($model, 'username', ['parts' => ['{font-awesome}' => 'user']])
                            ->textInput(['placeholder' => Yii::t('app', 'Введите логин, емайл или телефон')]) ?>

                        <?= $form->field($model, 'password', ['parts' => ['{font-awesome}' => 'lock']])
                            ->passwordInput(['placeholder' => Yii::t('app', 'Пароль')]) ?>

                        <div>
                            <?= Html::submitButton('<strong>'.Yii::t('app', 'Войти').'</strong>', ['class' => 'btn btn-sm btn-primary pull-right m-t-n-xsy', 'name' => 'login-button']) ?>
                            <?= $form->field($model, 'rememberMe')->checkbox([
                                'template' => '{input} {label}',
                                'class' => 'i-checks',
                                'style' => 'position: absolute; opacity: 0;'
                            ]) ?>
                            <?= Html::a(Yii::t('app', 'Забыли пароль?'), ['site/request-password-reset']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <div class="col-sm-6"><h4><?= Yii::t('app', 'Вы еще не участник?') ?></h4>
                        <p><?= Yii::t('app', 'Создайте свой аккаунт прямо сейчас:') ?></p>
                        <p class="text-center" style="margin-top: 70px;">
                            <?= Html::a('<i class="fa fa-sign-in big-icon"></i>', Url::to(['/site/signup'])) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>