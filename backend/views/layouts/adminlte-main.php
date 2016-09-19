<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.07.2016
 * Time: 11:32
 */
use backend\assets\AppAsset;
use phpnt\adminLTE\AdminLteAsset;
use yii\helpers\Html;
use phpnt\fontAwesome\FontAwesomeAsset;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $user \common\models\Identity */

AppAsset::register($this);
FontAwesomeAsset::register($this);
$adminLteAsset = AdminLteAsset::register($this);

if (!Yii::$app->user->isGuest) {
    $user = Yii::$app->user->identity;
    $username   = $user->username;
//$avatar     = $user->image ? Yii::$app->params['frontendUrl'].'/'.$user->image : Yii::$app->params['frontendUrl'].'/attach/images/no-avatar.png';
    $avatar     = Yii::$app->urlManager->baseUrl.'/images/no-avatar.png';
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">
    <?php if (!Yii::$app->user->isGuest): ?>
    <?= $this->render('adminlte-header',
        [
            'adminLteAsset' => $adminLteAsset,
            'user'          => $user,
            'username'      => $username,
            'avatar'        => $avatar
        ]); ?>
    <?= $this->render('adminlte-left',
        [
            'adminLteAsset' => $adminLteAsset,
            'user'          => $user,
            'username'      => $username,
            'avatar'        => $avatar
        ]); ?>
    <?php endif; ?>
    <?= $this->render('adminlte-content',
        [
            'adminLteAsset' => $adminLteAsset,
            'content'       => $content,
        ]); ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
