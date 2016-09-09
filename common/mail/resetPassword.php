<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.08.2015
 * Time: 15:38
 *
 */
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \common\models\Identity */
?>
<div class="password-reset">
    <p><?= Yii::t('app', 'Здравствуйте') ?></p>

    <p><?= Html::a(Yii::t('app', 'Перейдите по этой ссылке для сброса пароля.'),
            Yii::$app->urlManager->createAbsoluteUrl(
                [
                    '/site/reset-password',
                    'token' => $user->password_reset_token
                ]
            )); ?>
    </p>
</div>
