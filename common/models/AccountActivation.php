<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.08.2015
 * Time: 11:49
 */

namespace common\models;

use yii\base\InvalidParamException;
use yii\base\Model;

/* @property string $username */
class AccountActivation extends Model
{
    /* @var $user Identity */
    private $_user;

    public function __construct($key, $config = [])
    {
        if(empty($key) || !is_string($key))
            throw new InvalidParamException('Ключ не может быть пустым!');
        $this->_user = Identity::findByEmailConfirmToken($key);
        if(!$this->_user)
            throw new InvalidParamException('Не верный ключ!');
        parent::__construct($config);
    }

    public function activateAccount()
    {
        $user                   = $this->_user;
        $user->status           = Identity::STATUS_ACTIVE;
        $user->removeEmailConfirmToken();
        return $user->save() ? $user : false;
    }

    public function getUsername()
    {
        $user = $this->_user;
        return $user->username;
    }

}