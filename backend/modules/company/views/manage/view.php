<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ProfileCompany */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Компании'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-company-view">
    <section class="content">
        <div class="box box-success" style="margin-bottom: 50px;">
            <div class="box-header with-border">
                <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            </div>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    /*'id',
                    'type',
                    'status',*/
                    'name',
                    'description:ntext',
                    /*'image_main',
                    'images',
                    'inn',
                    'ogrn',
                    'okpo',
                    'okved',
                    'okato',
                    'bik',
                    'kpp',*/
                ],
            ]) ?>
        </div>
    </section>
</div>
