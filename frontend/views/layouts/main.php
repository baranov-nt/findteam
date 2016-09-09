<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $user \common\models\User */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use phpnt\bootstrapNotify\BootstrapNotify;
use phpnt\fontAwesome\FontAwesomeAsset;
use common\widgets\LanguageSelect\LanguageSelect;
use yii\helpers\Url;

AppAsset::register($this);
FontAwesomeAsset::register($this);
$user = Yii::$app->user->identity;
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
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('app', 'Регистрация'), 'url' => ['/site/signup']];
        $menuItems[] = ['label' => Yii::t('app', 'Войти'), 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                Yii::t('app', 'Выйти ({user})', ['user' => $user->username]),
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
    }

    $menuItems[] = [
        'label' => Yii::t('app', '<i class="fa fa-cog" aria-hidden="true"></i>'),
        'url' => Url::to('http://admin.boyar-nt.ru/'),
        'linkOptions' => [
            'target' => '_blank'
        ]
    ];

    echo LanguageSelect::widget();

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        'encodeLabels' => false
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => ['label' => Yii::t('app', 'Главная'), 'url' => Url::to(['/'])],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= BootstrapNotify::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
