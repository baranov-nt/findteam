<?php

use phpnt\bootstrapNotify\BootstrapNotify;
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Главная');
?>
<?= BootstrapNotify::widget() ?>
<div class="site-index">
    <div class="col-lg-6">
        <canvas id="doughnutChart" width="78" height="78" style="width: 78px; height: 78px;"></canvas>
        <h5>Maxtor</h5>
    </div>
    <div class="col-lg-6">
        <canvas id="polarChart" width="80" height="80" style="width: 80px; height: 80px;"></canvas>
        <h5>Kolter</h5>
    </div>
</div>