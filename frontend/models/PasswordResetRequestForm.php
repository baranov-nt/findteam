<?php
namespace frontend\models;

use common\models\Identity;
use Yii;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Identity
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => Identity::STATUS_ACTIVE],
                'message' => Yii::t('app', 'Пользователя с такой электронной почтой не существует.')
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user Identity */
        $user = Identity::findOne([
            'status' => Identity::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!Identity::isPasswordResetTokenValid($user->password_reset_token)) {
            /* @var $user Identity */
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app->mailer->compose('resetPassword', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::t('app', '{app} (отправлено роботом)', ['app' => Yii::$app->name])])
            ->setTo($this->email)
            ->setSubject(Yii::t('app', 'Сброс пароля для {app}', ['app' => Yii::$app->name]))
            ->send();
    }
}
