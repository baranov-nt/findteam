<?php
/* @var $model \backend\modules\translate\models\SourceMessage */
use yii\helpers\Html;
use yii\helpers\Json;

$locations = isset($model->location) ? Json::decode($model->location) : [];
?>
<div class="source-message-content">
    <strong style="color: #006e00;"><?= $model->message ?></strong>
</div>
<?php
if (is_array($locations) && !empty($locations) ) {
    echo Html::ul(array_unique($locations), [
        'class' => 'trace',
        'item' => function ($location) {
            return "<li>{$location}</li>";
        },
    ]);
}