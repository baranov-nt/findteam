<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ProfileCompany */

$this->title = Yii::t('app', 'Создать компанию');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Компании'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-company-create">
    <section class="content">
        <?= $this->render('_company-form', [
            'model' => $model,
        ]) ?>
    </section>
</div>
