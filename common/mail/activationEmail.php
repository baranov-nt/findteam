<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.08.2015
 * Time: 11:42
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \common\models\Identity */
?>
<h1><?= Yii::$app->name ?></h1>
<?= Html::a(Yii::t('app', 'Для активации вашего аккаунта перейдите по этой ссылке.'),
    Yii::$app->urlManager->createAbsoluteUrl(
        [
            '/site/activate-account',
            'key' => $user->email_confirm_token
        ]
    ));
?>
