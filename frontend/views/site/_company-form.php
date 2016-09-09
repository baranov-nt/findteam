<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.09.2016
 * Time: 17:54
 */
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\helpers\Url;
use yii\widgets\MaskedInput;
use dosamigos\typeahead\Bloodhound;
use dosamigos\typeahead\TypeAhead;
use common\models\Identity;
?>
<?php $form = ActiveForm::begin([
    'action' => Url::to(['/site/company-signup']),
    'id' => 'form']) ?>

<div class="col-md-12">
    <?php $model->account_type = Identity::ACCOUNT_COMPANY; ?>
    <?= $form->field($model, 'account_type')
        ->radioList(
            $model->userAccountTypeList,
            [
                'class' => 'radio radio-primary',
                'item' => function ($index, $label, $name, $checked, $value){
                    return '<div class="col-md-6"><input type="radio" id="check-h-'.$index.'" name="'.$name.'" value="'.$value.'" '.($checked ? 'checked' : '').'>
                            <label for="check-h-'.$index.'">'.$label.'</label></div>';
                },
                'onchange' => '
                $.pjax({
                    type: "POST",
                    url: "'.Url::to(['/site/signup']).'",
                    container: "#pjaxBlock",
                    push: true,
                    scrollTo: false
                })
            '
            ])->hint(Yii::t('app', 'Укажите тип аккаунта')); ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'username')->textInput(['placeholder' => 'Логин']) ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'name')->textInput(['placeholder' => Yii::t('app', 'Название компании')]) ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'email')->textInput(['placeholder' => 'Электронная почта'])
        ->hint(Yii::t('app', 'Мы вышлем Вам письмо по электронной почте с ссылкой для активации учетной записи.'))  ?>
</div>
<div class="col-md-12">
    <?php $model->country_id = $model->isNewRecord ? $model->country_id : $model->countryUser ?>
    <?= $form->field($model, 'country_id', [
        'template' => '{label}{input}{error}'])
        ->dropDownList($model->countriesList, [
            'class'  => 'form-control selectpicker',
            'data' => [
                'style' => 'btn-primary',
                'live-search' => true,
                'size' => 7,
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

<div class="col-md-12">
    <?= $form->field($model, 'city_id', [
        'template' => '{input}'])->hiddenInput(['id' => 'city-id'])->label(false); ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'phone',
        [
            'template' => '{label}
                            <div class="input-group">
                                <span class="input-group-addon">+'.$model->calling_code.'</span>{input}
                             </div>
                      <i>{hint}</i>{error}']
    )->widget(MaskedInput::className(),[
        'name' => 'phone',
        'mask' => $model->phone_mask
    ])
        ->hint(Yii::t('app', 'Телефон')) ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app', 'Пароль')]) ?>
</div>

<div class="col-md-12">
    <?= $form->field($model, 'confirm_password')->passwordInput(['placeholder' => Yii::t('app', 'Подтвердите пароль')]) ?>
</div>

<?= Html::hiddenInput('model', 'common\models\ProfileCompanyForm') ?>
<?= Html::hiddenInput('scenario', $model->scenario) ?>
<?= Html::hiddenInput('form', '@frontend/views/site/signup') ?>

<div class="col-md-12">
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Зарегистрировать'), ['class' => 'btn btn-primary block full-width m-b']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

