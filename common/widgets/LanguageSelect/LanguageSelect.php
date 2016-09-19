<?php
/*
author :: Pitt Phunsanit
website :: http://plusmagi.com
change language by get language=EN, language=TH,...
or select on this widget
*/

namespace common\widgets\LanguageSelect;

use Yii;
use yii\base\Widget;
use yii\bootstrap\Nav;
use yii\helpers\Url;

class LanguageSelect extends Widget
{
    public $container = [
        'class' => 'pull-right'
    ];

    public $languages = [
        'en' => 'English',
        /*'fr' => 'Un Français',
        'ch' => '中國',*/
        'ru' => 'Русский',
    ];

    public function init()
    {
        if(php_sapi_name() === 'cli')
        {
            return true;
        }

        parent::init();

        $cookies = Yii::$app->response->cookies;
        $languageNew = Yii::$app->request->get('_language');

        if($languageNew)
        {
            if(isset($this->languages[$languageNew]))
            {
                Yii::$app->language = $languageNew;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'language',
                    'value' => $languageNew
                ]));
            }
        }
        elseif($cookies->has('language'))
        {
            Yii::$app->language = $cookies->getValue('language');
        }
    }

    public function run(){
        $languages = $this->languages;
        $current = $languages[Yii::$app->language];
        unset($languages[Yii::$app->language]);

        $items = [];

        $i = 0;
        foreach($languages as $code => $language)
        {
            /*if ($i == 0) {
                $items = [
                    '<li class="dropdown-header">'.Yii::t('app', 'Select the language').'</li>',
                    '<li class="divider"></li>'
                ];
            }*/
            $items[] = [
                'label' => Yii::t('app', $language),
                'url' => Url::current(['language' => $code])
            ];
            $i++;
        }

       $menuItems[] = [
            'label' => Yii::t('app', $current),
            'items' => $items,
            'linkOptions' => [

            ]
        ];
        echo Nav::widget([
            'items' => $menuItems,
            'activateParents' => true,
            'encodeLabels' => false,
            'options' => [
                'class' => 'navbar-nav navbar-right'
            ]
        ]);
    }
}