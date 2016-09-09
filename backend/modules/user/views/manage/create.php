<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Identity */

$this->title = Yii::t('app', 'Создать пользователя');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="identity-create">
    <section class="content">
        <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </section>
</div>
