<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\forms\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Войти');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="col-md-6 col-md-offset-3">
        <section class="content">
            <div class="box box-success" style="margin-bottom: 50px;">
                <div class="box-header with-border"></div>
                <?php $form = ActiveForm::begin(['fieldConfig' => [
                    'template' => '{label}<div class="input-group">{input}
                            <span class="input-group-addon"><i class="fa fa-{font-awesome}"></i></span>
                         </div><i>{hint}</i>{error}'
                ]]); ?>
                <div class="box-body">

                    <div class="col-md-12">
                        <?= $form->field($model, 'username', ['parts' => ['{font-awesome}' => 'user']])
                            ->textInput(['placeholder' => Yii::t('app', 'Введите логин, емайл или телефон')]) ?>
                    </div>

                    <div class="col-md-12">
                        <?= $form->field($model, 'password', ['parts' => ['{font-awesome}' => 'lock']])
                            ->passwordInput(['placeholder' => Yii::t('app', 'Пароль')]) ?>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <?= Html::submitButton(Yii::t('app', 'Войти'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>
                    </div>

                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </section>
    </div>
</div>