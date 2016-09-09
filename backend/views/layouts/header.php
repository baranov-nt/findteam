<?php
use common\models\Identity;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use common\widgets\LanguageSelect\LanguageSelect;

/* @var $this \yii\web\View */
/* @var $user Identity */
/* @var $username string */
/* @var $avatar string */

?>
<!-- Main Header -->
<header class="main-header">
    <!-- Logo -->
    <?= Html::a('<span class="logo-mini"><span class="glyphicon glyphicon-cog"></span></span><span class="logo-lg">Панель управления</span>', Url::to(['index']), ['class' => 'logo']) ?>
    <?php
    NavBar::begin([
        'brandLabel'    => '<span class="sr-only">Toggle navigation</span>',
        'brandUrl'      => '#',
        'brandOptions'  => [
            'class'         => 'sidebar-toggle',
            'data-toggle'   => 'offcanvas',
            'role'          => 'button',
        ],
        'containerOptions'  => [
            'class' => 'navbar-custom-menu'
        ],
        'renderInnerContainer'  => false,
        'options' => [
            'class' => 'navbar navbar-static-top',
        ],
    ]);



    $menuItems[] = [
        'label' => '<img src="'.$avatar.'" class="user-image" alt="User Image"><span class="hidden-xs">'.$username.'</span>',
        'items' => [
            [
                'label' => '<img src="'.$avatar.'" class="img-circle" alt="'.Yii::t('app', 'Изображение пользователя').'">
                <p>
                  '.$username.'
                  <small>'.Yii::t('app', 'Зарегистрирован').' '.Yii::$app->formatter->asDate($user->created_at).'</small>
                </p>',
                'options'  => [
                    'class' => 'user-header'
                ]
            ],
            [
                'label' => '<div class="pull-left">
                      '.Html::a(Yii::t('app', 'Мой профиль'), '#', ['class' => 'btn btn-default btn-flat']).'    
                </div>
                <div class="pull-right">
                  '. Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        Yii::t('app', 'Выйти'),
                        ['class' => 'btn btn-default btn-flat']
                    )
                    . Html::endForm()
                    .'
                </div>',
                'options' => [
                    'class' => 'user-footer'
                ],
            ],
        ],
        'linkOptions' => [
            'class' => 'dropdown-toggle'
        ],
        'options' => [
            'class' => 'dropdown user user-menu'
        ],
        'itemsOptions'  => [
            'class' => 'user-body'
        ]
    ];

    //echo LanguageSelect::widget();

    echo Nav::widget([
        'options' => [
            'class' => 'nav navbar-nav',
        ],
        'items' => $menuItems,
        'encodeLabels' => false
    ]);
    NavBar::end();
    ?>
</header>
