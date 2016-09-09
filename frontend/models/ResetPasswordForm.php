<?php
namespace frontend\models;

use common\models\Identity;
use yii\base\InvalidParamException;

/**
 * Password reset form
 */
class ResetPasswordForm extends Identity
{
    public $password;
    public $confirm_password;

    /**
     * @var Identity
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(\Yii::t('app', 'Токен для сброса пароля не может быть пустым.'));
        }
        $this->_user = Identity::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException(\Yii::t('app', 'Не верный токен для сброса пароля.'));
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message'=> \Yii::t('app', 'Пароли не совпадают.')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password'      => \Yii::t('app', 'Пароль'),
            'confirm_password'  => \Yii::t('app', 'Повторите пароль'),
        ];
    }

    /**
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}
