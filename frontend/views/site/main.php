<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 10.09.2016
 * Time: 16:17
 */
use yii\bootstrap\Html;

$this->title = Html::encode(Yii::t('app', 'Главная страница'));
?>
<div class="site-main">
    <?= Yii::t('testic', '<p>Проверка номер 3</p>'); ?>
</div>
