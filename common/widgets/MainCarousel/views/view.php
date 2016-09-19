<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 09.09.2016
 * Time: 14:13
 */
use yii\bootstrap\Carousel;

$this->registerCss(".landing-page .header-back.one {
    background: url('../img/landing/header_one.jpg') 50% 0 no-repeat;
}");
$this->registerCss(".landing-page .header-back.two {
    background: url('../img/landing/header_two.jpg') 50% 0 no-repeat;
}");
?>
<?= Carousel::widget([
    'items' => [
        [
            'content' => '<div class="container">
                <div class="carousel-caption">
                    <h1>We craft<br>
                        brands, web apps,<br>
                        and user interfaces<br>
                        we are IN+ studio</h1>
                    <p>Lorem Ipsum is simply dummy text of the printing.</p>
                    <p>
                        <a class="btn btn-lg btn-primary" href="#" role="button">READ MORE</a>
                        <a class="caption-link" href="#" role="button">Inspinia Theme</a>
                    </p>
                </div>
                <div class="carousel-image wow zoomIn animated" style="visibility: visible;">
                    <img src="/img/landing/laptop.png" alt="laptop">
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
                    <h1>We create meaningful <br> interfaces that inspire.</h1>
                    <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam.</p>
                    <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
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
