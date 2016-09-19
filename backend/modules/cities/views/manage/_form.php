<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use phpnt\bootstrapSelect\BootstrapSelectAsset;
use yii\widgets\Pjax;
use dosamigos\typeahead\TypeAhead;
use dosamigos\typeahead\Bloodhound;
use common\models\Identity;
use common\models\forms\GeoCountryForm;

/* @var $this yii\web\View */
/* @var $model \common\models\forms\GeoCityForm */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="geo-city-form">
    <div class="box box-success">
        <div class="box-header with-border"></div>
        <?php
        Pjax::begin([
            'id' => 'pjaxBlock',
            'enablePushState' => false,
        ]);
        BootstrapSelectAsset::register($this);
        ?>
        <?php
        $form = ActiveForm::begin([
            'id' => 'form',
            'action' => Url::to(['/cities/manage/create']),
            'options' => ['data-pjax' => true]]);

        ?>
        <div class="box-body">

            <div class="col-md-12">
                <?= $form->field($model, 'country_id', [
                    'template' => '{label}{input}{error}'])
                    ->dropDownList(GeoCountryForm::getCountriesList(), [
                        'class'  => 'form-control selectpicker',
                        'data' => [
                            'style' => 'btn-primary',
                            'live-search' => true,
                            'size' => 7,
                            'title' => Yii::t('app', 'Выберите страну'),
                        ],
                        'onchange' => '
            $.pjax({
                type: "POST",
                url: "'.Url::to(['/geo/set-country']).'",
                data: jQuery("#form").serialize(),
                container: "#pjaxBlock",
                push: false,
                scrollTo: false
            })']) ?>
            </div>
            <div class="col-md-12">
                <?php
                $engine = new Bloodhound([
                    'name' => 'countriesEngine',
                    'clientOptions' => [
                        'datumTokenizer' => new \yii\web\JsExpression("Bloodhound.tokenizers.obj.whitespace('name')"),
                        'queryTokenizer' => new \yii\web\JsExpression("Bloodhound.tokenizers.whitespace"),
                        'remote' => [
                            'url' => Url::to(['/geo/set-city', 'id'=> $model->country_id, 'q'=>'QRY']),
                            'wildcard' => 'QRY'
                        ]
                    ]
                ]);
                ?>
                <?= $form->field($model, 'city')->widget(
                    TypeAhead::className(),
                    [
                        'options' => ['class' => 'form-control'],
                        'engines' => [ $engine ],
                        'clientOptions' => [
                            'highlight' => true,
                            'minLength' => 2,
                        ],
                        'clientEvents' => [
                            'typeahead:selected' => new \yii\web\JsExpression(
                                'function(obj, datum, name) { 
                        $("#city-id").val(datum.id);
                    }'
                            ),
                        ],
                        'dataSets' => [
                            [
                                'name' => 'city',
                                'displayKey' => 'city',
                                'source' => $engine->getAdapterScript(),
                                'templates' => [
                                    'suggestion' => new \yii\web\JsExpression("function(data){ return '<div class=\"col-xs-12 item-container\"><div class=\"item-header\">' + data.city + '</div><div class=\"item-hint\">' + data.region + '</div></div>'; }"),
                                ],
                            ]
                        ]
                    ]
                );?>
            </div>

                <?= $form->field($model, 'city_id', [
                    'template' => '{input}'])->hiddenInput(['id' => 'city-id'])->label(false); ?>

            <?= $form->field($model, 'active', [
                'template' => '{input}'])->hiddenInput(['value' => 1])->label(false); ?>

            <?= Html::hiddenInput('model', 'common\models\forms\GeoCityForm') ?>
            <?= Html::hiddenInput('scenario', $model->scenario) ?>
            <?= Html::hiddenInput('form', '@backend/modules/cities/views/manage/_form') ?>

            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Добавить город'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>

        </div>
        <?php
        ActiveForm::end();
        Pjax::end();
        ?>
    </div>
</div>