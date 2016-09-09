<?php
/* @var $this yii\web\View */
/* @var $model common\models\ProfileCompany */

$this->title = Yii::t('app', 'Изменить компанию: ') . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Компании'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменить');
?>
<div class="profile-company-update">
    <section class="content">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </section>
</div>
