<?php
/* @var $this \yii\web\View */
/* @var $adminLteAsset \phpnt\adminLTE\AdminLteAsset */
/* @var $user \common\models\Identity */
/* @var $username string */
/* @var $avatar string */
/* @var $items array */

use yii\helpers\Url;
use yii\widgets\Menu;
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $avatar ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= $username ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <?php
        if (Yii::$app->user->can('redactor')) {
            $items = [
                [
                    'label' => '<i class="fa fa-pie-chart"></i> <span>' . Yii::t('app', 'Главная') . '</span>',
                    'url' => Url::to(['/']),
                    'options' => ['class' => (Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index') ? 'active' : '']
                ],
            ];
        }

        if (Yii::$app->user->can('admin')) {
            $items[] = [
                'label'     => '<i class="fa fa-user"></i> <span>'.Yii::t('app', 'Пользователи').'</span>',
                'url'       => Url::to(['/user/manage/index']),
                'options'   => ['class' => Yii::$app->controller->module->id == 'user' ? 'active' : '']
            ];
            $items[] = [
                'label'     => '<i class="fa fa-users" aria-hidden="true"></i> <span>'.Yii::t('app', 'Компании').'</span>',
                'url'       => Url::to(['/company/manage/index']),
                'options'   => ['class' => Yii::$app->controller->module->id == 'company' ? 'active' : '']
            ];
        }

        if (Yii::$app->user->can('redactor')) {
            $items[] = [
                'label' => '<i class="fa fa-newspaper-o"></i> <span>' . Yii::t('app', 'Контент') . '</span>',
                'url' => Url::to(['/content/manage/index']),
                'options' => ['class' => Yii::$app->controller->module->id == 'content' ? 'active' : '']
            ];
        }

        if (Yii::$app->user->can('redactor') && Yii::$app->setting->show_all_cities == null) {
            $items[] = [
                'label' => '<i class="fa fa-map-marker"></i> <span>' . Yii::t('app', 'Управление городами') . '</span>',
                'url' => Url::to(['/cities/manage/index']),
                'options' => ['class' => Yii::$app->controller->module->id == 'cities' ? 'active' : '']
            ];
        }

        if (Yii::$app->user->can('redactor') && Yii::$app->setting->show_all_countries == null) {
            $items[] = [
                'label' => '<i class="fa fa-map"></i> <span>' . Yii::t('app', 'Управление странами') . '</span>',
                'url' => Url::to(['/countries/manage/index']),
                'options' => ['class' => Yii::$app->controller->module->id == 'countries' ? 'active' : '']
            ];
            $items[] = [
                'label' => '<i class="fa fa-language"></i> <span>' . Yii::t('app', 'Управление переводом') . '</span>',
                'url' => Url::to(['/translate/manage/index']),
                'options' => ['class' => Yii::$app->controller->module->id == 'translate' ? 'active' : '']
            ];
        }

        if (Yii::$app->user->can('admin')) {
            $items[] = [
                'label'     => '<i class="fa fa-gear"></i> <span>'.Yii::t('app', 'Настройка').'</span>',
                'url'       => Url::to(['/setting/manage/index']),
                'options'   => ['class' => Yii::$app->controller->module->id == 'setting' ? 'active' : '']
            ];
        }

        ?>
        <?= Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'encodeLabels' => false,
                'items' => $items
            ]
        ) ?>
    </section>
</aside>
