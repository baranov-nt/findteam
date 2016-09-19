<?php
/* @var $this yii\web\View */
/* @var $model common\models\GeoCity */

$this->title = Yii::t('app', 'Добавить город');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Управление городами'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="geo-city-create">
    <section class="content">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </section>
</div>
