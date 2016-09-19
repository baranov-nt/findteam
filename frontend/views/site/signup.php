<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\forms\UserForm */

use yii\helpers\Html;
use phpnt\bootstrapSelect\BootstrapSelectAsset;
use yii\widgets\Pjax;
use common\widgets\ICheck\ICheckAsset;
use yii\helpers\Url;
use phpnt\bootstrapNotify\BootstrapNotify;

if (Yii::$app->geoData->city == 0) {
    $city = Yii::$app->geoData->setData($timezone = 'Europe/Moscow', $city = 524901, $region = 524894, $country = 185);
}
$this->title = Html::encode(Yii::t('app', 'Регистрация'));
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <div class="row">
        <?php
        Pjax::begin([
            'id' => 'pjaxBlock',
            'enablePushState' => false,
        ]);
        BootstrapSelectAsset::register($this);
        ICheckAsset::register($this);
        ?>
        <?php

        ?>
        <?= BootstrapNotify::widget(); ?>
        <?php
        if ($model->scenario == 'user'):
            ?>
            <div class="col-md-6">
                <div class="ibox float-e-margins" style="padding-bottom: 60px;">
                    <div class="ibox-title">
                        <h1><?= Html::encode(Yii::t('app', 'Регистрация пользователя')) ?></h1>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <h2><?= Yii::t('app', 'Добро пожаловать на наш сайт!') ?></h2>
                        <h3>Вы представляете <span class="text-primary">организацию</span>? Тогда вам сюда!</h3>
                        <?= Html::button(Yii::t('app', 'Регистрация для юридических лиц'), [
                            'class' => 'btn btn-md btn-primary',
                            'onclick'   => '
                                $.pjax({
                                type: "POST",
                                url: "'.Url::to(['/site/set-scenario', 'scenario' => 'company']).'", 
                                data: jQuery("#form").serialize(),
                                container: "#pjaxBlock",
                                push: false,
                                scrollTo: false
                            });'
                        ]) ?>
                    </div>
                </div>
            </div>
            <?php
        else:
            ?>
            <div class="col-md-6">
                <div class="ibox float-e-margins" style="padding-bottom: 60px;">
                    <div class="ibox-title">
                        <h1><?= Html::encode(Yii::t('app', 'Регистрация организации')) ?></h1>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <p><?= Yii::t('app', 'Пожалуйста заполните следующие поля, чтобы зарегистрироваться.') ?></p>
                    </div>
                </div>
            </div>
            <?php
        endif;
        ?>
        <div class="col-md-6">
            <?= $this->render('_form-signup', ['model' => $model]) ?>
        </div>
        <?php
        Pjax::end();
        ?>
    </div>
</div>