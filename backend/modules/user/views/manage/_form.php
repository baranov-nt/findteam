<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.09.2016
 * Time: 17:54
 */
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\forms\UserForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\helpers\Url;
use yii\widgets\MaskedInput;
use dosamigos\typeahead\Bloodhound;
use dosamigos\typeahead\TypeAhead;
use yii\widgets\Pjax;
use phpnt\awesomeBootstrapCheckbox\AwesomeBootstrapCheckboxAsset;
use phpnt\bootstrapSelect\BootstrapSelectAsset;
use common\models\forms\GeoCountryForm;
use common\models\forms\GeoCityForm;
?>
<div class="identity-form">
    <div class="box box-success" style="margin-bottom: 50px;">
        <div class="box-header with-border"></div>
        <?php
        Pjax::begin([
            'id' => 'pjaxBlock',
            'enablePushState' => false,
        ]);
        AwesomeBootstrapCheckboxAsset::register($this);
        BootstrapSelectAsset::register($this);
        ?>
        <?php $form = ActiveForm::begin([
            'action' => $model->isNewRecord ? Url::to(['/user/manage/create']) : Url::to(['/user/manage/update', 'id' => $model->id]),
            'id' => 'form']) ?>
        <div class="box-body">
            <div class="col-md-12">
                <?= $form->field($model, 'username')->textInput(['placeholder' => 'Логин']) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'first_name')->textInput(['placeholder' => 'Имя']) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'last_name')->textInput(['placeholder' => 'Фамилия']) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'sex')
                    ->radioList(
                        $model->sexList,
                        [
                            'class' => 'radio radio-primary',
                            'item' => function ($index, $label, $name, $checked, $value){
                                return '<div class="col-md-12"><input type="radio" id="check-h-'.$index.'" name="'.$name.'" value="'.$value.'" '.($checked ? 'checked' : '').'>
                            <label for="check-h-'.$index.'">'.$label.'</label></div>';
                            }
                        ])->hint(Yii::t('app', 'Укажите пол')); ?>
            </div>
            <div class="col-md-12">
                <h5><strong><?= Yii::t('app', 'Дата рождения') ?></strong></h5>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'day_birth')->dropDownList($model->dayBirthList, [
                    'class'         => 'form-control selectpicker',
                    'data' => [
                        'style' => 'btn-primary',
                        'size' => 7,
                        'title' => Yii::t('app', 'День')
                    ]])->label(false) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'month_birth')->dropDownList($model->monthBirthList, [
                    'class'         => 'form-control selectpicker',
                    'data' => [
                        'style' => 'btn-primary',
                        'size' => 7,
                        'title' => Yii::t('app', 'Месяц')
                    ]])->label(false) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'year_birth')->dropDownList($model->yearBirthList, [
                    'class'         => 'form-control selectpicker',
                    'data' => [
                        'style' => 'btn-primary',
                        'size' => 7,
                        'title' => Yii::t('app', 'Год')
                    ]])->label(false) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'email')->textInput(['placeholder' => 'Электронная почта'])  ?>
            </div>

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

            <?php
/*            if ($model->country_id):
                */?>
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
            <div class="col-md-12">
                <?php
                if ($model->city_id) {
                    $model->city = GeoCityForm::getCityName($model->city_id);
                }
                ?>
                <?= $form->field($model, 'city', ['template' => '{label}{input}<i>{hint}</i>{error}'])->widget(
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
                <?= $form->field($model, 'city_id', ['template' => '{input}'])->hiddenInput(['id' => 'city-id'])->label(false); ?>
                <?php
/*            else:
                */?><!--
                <div class="col-md-12">
                    <?/*= $form->field($model, 'city', ['template' => '{label}{input}<i>{hint}</i>{error}'])
                        ->textInput(['class' => 'form-control disabled', 'disabled' => true]) */?>
                </div>-->
                <?php
/*            endif;
            */?>

            <!--<div class="col-md-12">
                <?/*= $form->field($model, 'city_id')->dropDownList(GeoCityForm::getCitiesList(), [
                    'class'         => 'form-control selectpicker',
                    'data' => [
                        'style' => 'btn-primary',
                        'size' => 7,
                        'title' => Yii::t('app', 'Город')
                    ]])->label(false) */?>
            </div>-->

            <div class="col-md-12">
                <?php
                if ($model->country_id):
                    ?>
                    <?= $form->field($model, 'phone', ['template' => '{label}
                            <div class="input-group">
                                <span class="input-group-addon">+'.GeoCountryForm::getCallingCode($model->country_id).'</span>{input}
                             </div>
                      <i>{hint}</i>{error}'])->widget(MaskedInput::className(),[
                    'name' => 'phone',
                    'mask' => $model->phoneMask])
                    ->hint(Yii::t('app', 'Телефон')) ?>
                    <?php
                else:
                    ?>
                    <?= $form->field($model, 'phone', ['template' => '{label}
                            <div class="input-group">
                                <span class="input-group-addon">+'.$model->calling_code.'</span>{input}
                             </div>
                      <i>{hint}</i>{error}'])->textInput(['class' => 'form-control disabled', 'disabled' => true])
                    ->hint(Yii::t('app', 'Телефон')) ?>
                    <?php
                endif;
                ?>
            </div>

            <div class="col-md-12">
                <?= $form->field($model, 'item_name')->dropDownList(isset($model->profileUser->company_id) ? $model->rolesOfCompanyList : $model->rolesOfUserList, [
                    'class'         => 'form-control selectpicker',
                    'data' => [
                        'style' => 'btn-primary',
                        'size' => 7,
                        'title' => Yii::t('app', 'Роль')
                    ]]) ?>
            </div>

            <?php
            if (!isset($model->profileUser->company_id) || $model->profileUser->company_id != null):
                ?>
                <div class="col-md-12">
                    <?= $form->field($model, 'tariff_name')->dropDownList($model->tariffesOfUserList, [
                        'class'         => 'form-control selectpicker',
                        'data' => [
                            'style' => 'btn-primary',
                            'size' => 7,
                            'title' => Yii::t('app', 'Тариф')
                        ]]) ?>
                </div>
                <?php
            endif;
            ?>

            <div class="col-md-12">
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app', 'Пароль')]) ?>
            </div>

            <div class="col-md-12">
                <?= $form->field($model, 'confirm_password')->passwordInput(['placeholder' => Yii::t('app', 'Подтвердите пароль')]) ?>
            </div>

            <?= Html::hiddenInput('model', 'common\models\forms\UserForm') ?>
            <?= Html::hiddenInput('scenario', $model->scenario) ?>
            <?= Html::hiddenInput('form', '@backend/modules/user/views/manage/_form') ?>
            <?= Html::hiddenInput('id', $model->id) ?>

            <?= $form->field($model, 'model_scenario', ['template' => '{input}'])->hiddenInput(['value' => $model->scenario])->label(false) ?>

            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Создать пользователя') : Yii::t('app', 'Изменить пользователя'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <?php Pjax::end(); ?>
    </div>
</div>

