<?php
namespace frontend\controllers;

use common\models\AccountActivation;
use common\models\forms\UserForm;
use common\models\Identity;
use common\models\forms\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;

class SiteController extends BehaviorsController
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['/site/main']));
        }

        return $this->render('index');
    }

    public function actionMain()
    {
        return $this->render('main');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSetScenario($scenario = null)
    {
        $model = new UserForm(['scenario' => $scenario]);
        $model->load(Yii::$app->request->post());
        $model->scenario = $scenario;
        $model->model_scenario = $scenario;
        return $this->render('signup', ['model' => $model]);
    }

    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) { return $this->redirect('/'); }


        if (Yii::$app->request->isPost) {
            $model = new UserForm();
            $model->load(Yii::$app->request->post());
            $model->scenario = $model->model_scenario;
        } else {
            $model = new UserForm(['scenario' => 'user']);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->status === Identity::STATUS_ACTIVE) {
                if (\Yii::$app->getUser()->login($model)) {
                    return $this->redirect('/site/index');
                }
            } else {
                if ($mail = $model->sendActivationEmail($model)) {
                    \Yii::$app->session->set(
                        'message',
                        [
                            'type' => 'primary',
                            'message' => \Yii::t('app', 'Письмо с активацией отправленно на <strong> {email} </strong> (проверьте папку спам).', ['email' => $model->email]),
                        ]
                    );
                    return $this->redirect(Url::to(['/site/index']));
                } else {
                    \Yii::$app->session->set(
                        'message',
                        [
                            'type' => 'danger',
                            'message' => \Yii::t('app', 'Ошибка. Письмо не отправлено.'),
                        ]
                    );
                    \Yii::error(\Yii::t('app', 'Error. The letter was not sent.'));
                }
                return $this->refresh();
            }
            return $this->redirect('/site/index');
        }
        return $this->render('signup', ['model' => $model]);
    }

    public function actionActivateAccount($key)
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        try {
            $user = new AccountActivation($key);
        }
        catch(\HttpInvalidParamException $e) {
            \Yii::$app->session->set(
                'message',
                [
                    'type'      => 'danger',
                    'message'   => \Yii::t('app', 'Неправильный ключ. Повторите регистрацию.'),
                ]
            );
            throw new BadRequestHttpException($e->getMessage());
        }

        if($user = $user->activateAccount()) {
            /* @var $user Identity */
            \Yii::$app->session->set(
                'message',
                [
                    'type'      => 'primary',
                    'message'   => \Yii::t('app', 'Активация прошла успешно.'),
                ]
            );
            \Yii::$app->getUser()->login($user);
            return $this->redirect(['/site/index']);
        } else {
            \Yii::$app->session->set('message',
                [
                    'type'      => 'danger',
                    'message'   => \Yii::t('app', 'Ошибка активации.'),
                ]
            );
        }

        return $this->redirect(Url::to(['/site/index']));
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                \Yii::$app->session->set('message',
                    [
                        'type'      => 'primary',
                        'message'   => \Yii::t('app', 'Проверьте ваш емайл и следуйте дальнейшим инструкциям.'),
                    ]
                );
                return $this->goHome();
            } else {
                \Yii::$app->session->set('message',
                    [
                        'type'      => 'danger',
                        'message'   => \Yii::t('app', 'Извините, мы не можем сбросить пароль для введенной электронной почты.'),
                    ]
                );
            }
        }
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            \Yii::$app->session->set('message',
                [
                    'type'      => 'primary',
                    'message'   => \Yii::t('app', 'Новый пароль сохранен.'),
                ]
            );
            return $this->goHome();
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}