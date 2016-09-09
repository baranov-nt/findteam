<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\ProfileUserForm */

use yii\helpers\Html;
use phpnt\awesomeBootstrapCheckbox\AwesomeBootstrapCheckboxAsset;
use phpnt\bootstrapSelect\BootstrapSelectAsset;
use yii\widgets\Pjax;
use common\models\Identity;

$this->title = Yii::t('app', 'Регистрация');
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->geoData->city == 0) {
    $city = Yii::$app->geoData->setData($timezone = 'Europe/Moscow', $city = 524901, $region = 524894, $country = 185);
}
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Пожалуйста заполните следующие поля, чтобы зарегистрироваться.') ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php
            Pjax::begin([
                'id' => 'pjaxBlock',
                'enablePushState' => false,
            ]);
            AwesomeBootstrapCheckboxAsset::register($this);
            BootstrapSelectAsset::register($this);
            ?>
            <?php
            if ($model->account_type == Identity::ACCOUNT_USER):
            ?>
            <?= $this->render('_user-form', ['model' => $model]) ?>
            <?php
            else:
            ?>
                <?= $this->render('_company-form', ['model' => $model]) ?>
            <?php
            endif;
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>