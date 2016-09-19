<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 13.09.2016
 * Time: 9:44
 */

namespace common\models\forms;

use common\models\UserOnline;

/**
 * @property array $onlineList
 * @property array $onlineMark
 * @property boolean $onlineStatus
 */

class UserOnlineForm extends UserOnline
{
    /* Онлайн статус */
    const ONLINE_IS     = 1;
    const ONLINE_NOT    = 2;

    public static function getOnlineList()
    {
        return [
            self::ONLINE_IS     => \Yii::t('app', 'Онлайн'),
            self::ONLINE_NOT    => \Yii::t('app', 'Оффлайн'),
        ];
    }

    public function getOnlineStatus()
    {
        $model = self::findOne($this->user_id);
        if ($model) {
            $time = time() - $model->online;
            if ($time <= \Yii::$app->params['online']) {
                return true;
            }
        }
        return false;
    }

    public function getOnlineMark()
    {
        $online = $this->onlineStatus;
        if ($online) {
            return '<span class="label label-primary">online</span>';
        }
        return '<span class="label label-warning">offline</span>';
    }
}