<?php
/* @var $content string */

use yii\bootstrap\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Breadcrumbs::widget([
            'homeLink' => ['label' => Yii::t('app', 'Главная'), 'url' => Url::to(['/'])],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </section>
    <?= $content ?>
</div>