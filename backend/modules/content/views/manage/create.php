<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ContentForm */

$this->title = Yii::t('app', 'Добавить контент');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Контент'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-form-create">
    <section class="content">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </section>
</div>
