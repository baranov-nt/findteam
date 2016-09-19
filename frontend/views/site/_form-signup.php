<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 15.09.2016
 * Time: 10:05
 */
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\forms\ProfileUserForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\helpers\Url;
use yii\widgets\MaskedInput;
use common\models\forms\GeoCityForm;
use common\models\forms\GeoCountryForm;
use dosamigos\typeahead\Bloodhound;
use dosamigos\typeahead\TypeAhead;
?>
<div class="ibox float-e-margins m-b">
    <div class="ibox-title">
        <h5><?= Yii::t('app', 'Пожалуйста, выберите тип аккаунта и зарегистрируйтесь.') ?></h5>
        <div class="ibox-tools">

        </div>
    </div>
    <div class="ibox-content">
        <div class="btn-group" style="width: 100%; margin-bottom: 20px;">
            <?= Html::button(Yii::t('app', 'Регистрация пользователя'), [
                'class' => $model->scenario == 'user' ? 'btn btn-md btn-primary' : 'btn btn-md btn-default',
                'style' => 'width:50%',
                'onclick'   => '
                    $.pjax({
                    type: "POST",
                    url: "'.Url::to(['/site/set-scenario', 'scenario' => 'user']).'", 
                    data: jQuery("#form").serialize(),
                    container: "#pjaxBlock",
                    push: false,
                    scrollTo: false
                });'
            ]) ?>
            <?= Html::button(Yii::t('app', 'Регистрация организации'), [
                'class' => $model->scenario == 'company' ? 'btn btn-md btn-primary' : 'btn btn-md btn-default',
                'style' => 'width:50%',
                'onclick'   => '
                    $.pjax({
                    type: "POST",
                    url: "'.Url::to(['/site/set-scenario', 'scenario' => 'company']).'", 
                    data: jQuery("#form").serialize(),
                    container: "#pjaxBlock",
                    push: false,
                    scrollTo: false
                });'
            ]) ?>
        </div>
        <?php $form = ActiveForm::begin([
            'id' => 'form',
            'action' => Url::to(['/site/signup']),
            'options' => ['data-pjax' => true],
            'fieldConfig' => [
                'template' => '{label}<div class="input-group">{input}
                            <span class="input-group-addon"><i class="fa fa-{font-awesome}"></i></span>
                         </div><i>{hint}</i>{error}'
            ]]); ?>
        <div class="row">

            <!--<div class="col-md-12">
            <?/*= $form->field($model, 'type', ['template' => '{label}{input}'])
                ->dropDownList(TypeUserForm::getUserTypes(), [
                    'class'         => 'form-control selectpicker',
                    'data' => [
                        'style' => 'btn-primary',
                        'size' => 7,
                        'title' => Yii::t('app', 'Тип пользователя')
                    ]])->label(false) */?>
        </div>-->

            <div class="col-md-12">
                <?= $form->field($model, 'username', ['parts' => ['{font-awesome}' => 'user']])
                    ->textInput(['placeholder' => Yii::t('app', 'Введите логин')]) ?>
            </div>
            <?php
            if ($model->scenario == 'user'):
                ?>
            <div class="col-md-12">
                <?= $form->field($model, 'first_name', ['parts' => ['{font-awesome}' => 'user']])
                    ->textInput(['placeholder' => Yii::t('app', 'Введите имя')]) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'last_name', ['parts' => ['{font-awesome}' => 'user']])
                    ->textInput(['placeholder' => Yii::t('app', 'Введите фамилию')]) ?>
            </div>
            <div class="col-md-12">
                <?= $form->field($model, 'sex')
                    ->radioList(
                        $model->sexList,
                        [
                            'item' => function ($index, $label, $name, $checked, $value){
                                return '<label class="radio-inline i-checks">
                            <div class="iradio_square-green">
                                <input class="radio-inline-input" type="radio" id="check-h-'.$index.'" name="'.$name.'" value="'.$value.'" '.($checked ? 'checked' : '').'>
                            </div> '.$label.'
                        </label>';
                            }
                        ])->hint(Yii::t('app', 'Укажите пол')); ?>
            </div>
            <div class="col-md-12">
                <h5><strong><?= Yii::t('app', 'Дата рождения') ?></strong></h5>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'day_birth', ['template' => '{label}{input}'])
                    ->dropDownList($model->dayBirthList, [
                        'class'         => 'form-control selectpicker',
                        'data' => [
                            'style' => 'btn-primary',
                            'size' => 7,
                            'title' => Yii::t('app', 'День')
                        ]])->label(false) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'month_birth', ['template' => '{label}{input}'])
                    ->dropDownList($model->monthBirthList, [
                        'class'         => 'form-control selectpicker',
                        'data' => [
                            'style' => 'btn-primary',
                            'size' => 7,
                            'title' => Yii::t('app', 'Месяц')
                        ]])->label(false) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'year_birth', ['template' => '{label}{input}'])->dropDownList($model->yearBirthList, [
                    'class'         => 'form-control selectpicker',
                    'data' => [
                        'style' => 'btn-primary',
                        'size' => 7,
                        'title' => Yii::t('app', 'Год')
                    ]])->label(false) ?>
            </div>

            <?php
            elseif ($model->scenario == 'company'):
            ?>
                <div class="col-md-12">
                    <?= $form->field($model, 'name', ['parts' => ['{font-awesome}' => 'users']])
                        ->textInput(['placeholder' => Yii::t('app', 'Название компании')]) ?>
                </div>
                <?php
            endif;
            ?>

            <div class="col-md-12">
                <?= $form->field($model, 'email', ['parts' => ['{font-awesome}' => 'envelope']])
                    ->textInput(['placeholder' => 'Электронная почта'])
                    ->hint(Yii::t('app', 'Мы вышлем Вам письмо по электронной почте с ссылкой для активации учетной записи.'))  ?>
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
            if ($model->country_id):
                ?>
                <?php
                if ($model->city_id) {
                    $model->city = GeoCityForm::getCityName($model->city_id);
                }
                ?>
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
                <?= $form->field($model, 'city', ['template' => '<div class="col-md-12">{label}</div><div class="col-md-12">{input}</div>'])->widget(
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
                <?= $form->field($model, 'city_id', ['template' => '{input}'])->hiddenInput(['id' => 'city-id'])->label(false); ?>
                <?php
            endif;
            ?>

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
                endif;
                ?>
            </div>

            <div class="col-md-12">
                <?= $form->field($model, 'password', ['parts' => ['{font-awesome}' => 'lock']])
                    ->passwordInput(['placeholder' => Yii::t('app', 'Пароль')]) ?>
            </div>

            <div class="col-md-12">
                <?= $form->field($model, 'confirm_password', ['parts' => ['{font-awesome}' => 'lock']])
                    ->passwordInput(['placeholder' => Yii::t('app', 'Подтвердите пароль')]) ?>
            </div>

            <?= $form->field($model, 'model_scenario', ['template' => '{input}'])->hiddenInput(['value' => $model->scenario])->label(false) ?>

            <?= Html::hiddenInput('model', 'common\models\forms\UserForm') ?>
            <?= Html::hiddenInput('scenario', $model->scenario) ?>
            <?= Html::hiddenInput('form', '@frontend/views/site/signup') ?>

            <div class="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Регистрация'), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>