<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 09.09.2016
 * Time: 12:58
 */

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $user \common\models\User */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\InspiniaLandingAsset;
use common\widgets\LanguageSelect\LanguageSelect;
use yii\helpers\Url;
use phpnt\fontAwesome\FontAwesomeAsset;
use phpnt\animateCss\AnimateCssAsset;
use common\widgets\MainCarousel\MainCarousel;
use phpnt\bootstrapNotify\BootstrapNotify;

InspiniaLandingAsset::register($this);
FontAwesomeAsset::register($this);
AnimateCssAsset::register($this);
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
    <style><?= (Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index') ? '' : 'body {background-color: #2f4050 !important;}' ?></style>
</head>
<body id="page-top" class="<?= 'landing-page' ?>">
<?php $this->beginBody() ?>
<div class="navbar-wrapper">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'brandOptions' => [
            'class' => 'page-scroll',
        ],
        'containerOptions' => [
            'id' => 'navbar',
            'class' => 'navbar-collapse collapse'
        ],
        'options' => [
            'id' => 'main-menu',
            'class' => 'navbar navbar-default navbar-fixed-top navbar-index',
        ],
    ]);

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('app', 'Главная'), 'url' => ['/site/index']];
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

    /*$menuItems[] = [
        'label' => Yii::t('app', '<i class="fa fa-cog" aria-hidden="true"></i>'),
        'url' => Url::to('http://admin.boyar-nt.ru/'),
        'linkOptions' => [
            'target' => '_blank'
        ]
    ];*/

    echo LanguageSelect::widget();

    echo Nav::widget([
        'id' => 'navbar',
        'options' => ['class' => 'nav navbar-nav navbar-right'],
        'items' => $menuItems,
        'encodeLabels' => false
    ]);
    NavBar::end();
    ?>
</div>

<?php
if (Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index'):
    ?>
    <?= MainCarousel::widget() ?>
    <?php
endif;
?>


<div class="wrapper wrapper-content">
    <div class="row wrapper border-bottom page-heading gray-bg" style="margin-top: 40px;">
        <div class="container">
            <div class="col-lg-12">
                <?php
                if (Yii::$app->controller->id != 'site' || Yii::$app->controller->action->id != 'index'):
                    ?>
                    <h2><?= $this->title ?></h2>
                    <?php
                endif;
                ?>
                <?= BootstrapNotify::widget(); ?>
                <?= Breadcrumbs::widget([
                    'homeLink' => ['label' => Yii::t('app', 'Главная'), 'url' => Url::to(['/'])],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'options' => [
                        'class' => 'breadcrumb gray-bg'
                    ]
                ]) ?>
            </div>
        </div>
    </div>
    <div class="row wrapper gray-bg" style="padding-top: 40px;">
        <div class="container">
            <?= $content ?>
        </div>
    </div>
</div>

<section id="contact" class="gray-section contact">
    <div class="container">
        <div class="row m-b-lg">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Contact Us</h1>
                <p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod.</p>
            </div>
        </div>
        <div class="row m-b-lg">
            <div class="col-lg-3 col-lg-offset-3">
                <address>
                    <strong><span class="navy">Company name, Inc.</span></strong><br>
                    795 Folsom Ave, Suite 600<br>
                    San Francisco, CA 94107<br>
                    <abbr title="Phone">P:</abbr> (123) 456-7890
                </address>
            </div>
            <div class="col-lg-4">
                <p class="text-color">
                    Consectetur adipisicing elit. Aut eaque, totam corporis laboriosam veritatis quis ad perspiciatis, totam corporis laboriosam veritatis, consectetur adipisicing elit quos non quis ad perspiciatis, totam corporis ea,
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <a href="mailto:test@email.com" class="btn btn-primary">Send us mail</a>
                <p class="m-t-sm">
                    Or follow us on social platform
                </p>
                <ul class="list-inline social-icon">
                    <li><a href="#"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li><a href="#"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li><a href="#"><i class="fa fa-linkedin"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center m-t-lg m-b-lg">
                <p><strong>© 2015 Company Name</strong><br> consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.</p>
            </div>
        </div>
    </div>
</section>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
