<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use phpnt\animateCss\AnimateCssAsset;
use phpnt\bootstrapNotify\BootstrapNotify;

AnimateCssAsset::register($this);
/* @var $key integer */
/* @var $model \backend\modules\translate\models\SourceMessage  */
Pjax::begin([
    'id' => 'translationGrid-'.$key
]);
?>
<?= BootstrapNotify::widget() ?>
<?php
$form = ActiveForm::begin([
    'id' => 'translationsForm-'.$key,
    'options' => ['data-pjax' => true]
]);
$items = [];
foreach (Yii::$app->i18n->languages as $lang) {
    $message = '';
    if(isset($model->messages[$lang]['translation'])) {
        $message = $model->messages[$lang]['translation'];
    }
    //dd($model->messages['de']['translation']);
    $items[] = [
        'label' => '<b>'.strtoupper($lang).'</b>',
        'content' => Html::textarea('Messages['.$lang.']', $message, [
            'class' => 'translation-textarea form-control',
            'rows'  => 3,
        ]),
        'active' => ($lang == Yii::$app->language) ? true : false,
    ];
}
echo Tabs::widget([
    'encodeLabels' => false,
    'items' => $items,
]);
ActiveForm::end();
Pjax::end();