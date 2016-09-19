<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 09.09.2016
 * Time: 14:13
 */
use yii\bootstrap\Carousel;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->registerCss(".landing-page .header-back.one {
    background: url('../images/background.jpg') 50% 0 no-repeat;
}");
$this->registerCss(".landing-page .header-back.two {
    background: url('../images/runner.jpg') 50% 0 no-repeat;
}");
?>
<?= Carousel::widget([
    'items' => [
        [
            'content' => '<div class="container">
                <div class="carousel-caption">
                    <h1>Найди свое занятие</h1>
                    <h3 style="font-size: 20px;"><strong>С нами Вы сможете найти компанию людей,<br> которые готовы поддержать вашу идею.</strong></h3>
                    <h3 style="font-size: 20px;"><strong>Мы - это не только сервис для поиска занятий,<br> мы - это объединение людей с одной целью!</strong></h3>
                    <p>
                        <a class="btn btn-lg btn-primary" href="#" role="button">Найти</a>
                        <a class="caption-link" href="#" role="button">Поиск события</a>
                    </p>
                </div>
                <div class="carousel-image wow zoomIn animated" style="visibility: visible;">
                    <img src="/images/football.jpg" style="border-radius: 10px;" alt="laptop">
                </div>
            </div>
            <div class="header-back one"></div>',
            /*'caption' => '<div class="wrap"><div class="container"><h1>We create meaningful <br> interfaces that inspire.</h1>
                    <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
                    <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p></div></div>',*/
            //'options' => ['class' => 'container'],
            //'itemOproins' => ['class' => 'container'],
        ],
        [
            'content' => '<div class="container">
                <div class="carousel-caption">
                    <h1>Собери свою команду.</h1>
                    <h3 style="font-size: 20px;"><strong>Регистрируйся и собирай свою команду прямо сейчас!</strong></h3>
                    <p>'.Html::a('Регистрация', Url::to(['/site/signup']), ['class' => 'btn btn-lg btn-primary']).'</p>
                </div>
            </div>
            <!-- Set background for slide in css -->
            <div class="header-back two"></div>',
            /*'caption' => '<h1>We create meaningful <br> interfaces that inspire.</h1>
                    <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
                    <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>',*/
            'options' => [],
        ],
    ],
    'options' => [
        'id' => 'inSlider',
        //'data-interval' => 0,
    ],
    'controls' => [
        '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Previous</span>',
        '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Next</span>'
    ],     // Стрелочки вперед - назад
    'showIndicators' => true,                   // отображать индикаторы (кругляшки)

]);
?>
