<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 06.09.2016
 * Time: 22:28
 */

namespace common\models;

/**
 * @property string $adminCompanyId
 * @property string $adminCompanyName
 * @property string $statusCompany
 * @property array $statusList
 * @property string $tariffName
 * @property array $tariffesOfCompanyList
 */

class ProfileCompanyIdentity extends ProfileCompany
{
    public $account_type;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['account_type'], 'integer'],
            [['tariff'], 'string'],
        ];
    }

    public function getAdminCompanyName()
    {
        $admin = '';
        foreach ($this->profileUsers as $profile) {
            /* @var $profile ProfileUser */
            foreach ($profile->id0->authAssignments as $one) {
                /* @var $one AuthAssignment */
                if ($one->item_name == 'adminCompany') {
                    $admin = $one->user->username;
                }

            }
        }
        return $admin;
    }

    public function getAdminCompanyId()
    {
        $admin = '';
        foreach ($this->profileUsers as $profile) {
            /* @var $profile ProfileUser */
            foreach ($profile->id0->authAssignments as $one) {
                /* @var $one AuthAssignment */
                if ($one->item_name == 'adminCompany') {
                    $admin = $one->user->id;
                }

            }
        }
        return $admin;
    }

    public function getStatusList()
    {
        return [
            Identity::STATUS_COMPANY_BLOCKED => \Yii::t('app', 'Заблокирован'),
            Identity::STATUS_COMPANY_ACTIVE => \Yii::t('app', 'Активен'),
            Identity::STATUS_COMPANY_WAIT =>  \Yii::t('app', 'Не активен'),
        ];
    }

    public function getStatusCompany()
    {
        switch ($this->status) {
            case Identity::STATUS_COMPANY_BLOCKED:
                return '<span class="label label-danger">
                            <i class="fa fa-ban" aria-hidden="true"></i> '.$this->getStatusList()[Identity::STATUS_COMPANY_BLOCKED].'</span>';
                break;
            case Identity::STATUS_COMPANY_WAIT:
                return '<span class="label label-warning">
                            <i class="glyphicon glyphicon-hourglass"></i> '.$this->getStatusList()[Identity::STATUS_COMPANY_WAIT].'</span>';
                break;
            case Identity::STATUS_COMPANY_ACTIVE:
                return '<span class="label label-success">
                            <i class="glyphicon glyphicon-ok"></i> '.$this->getStatusList()[Identity::STATUS_COMPANY_ACTIVE].'</span>';
                break;
        }
        return false;
    }

    public function getTariffesOfCompanyList()
    {
        $roles = [0 => \Yii::t('app', 'Удалить тариф')];
        foreach (AuthItem::getCompanyTariffes() as $one)
        {
            /* @var $one AuthItem */
            $roles[$one->name] = $one->description;
        }
        return $roles;
    }

    public function getTariffName()
    {
        /* @var $model AuthAssignment */
        $model = AuthAssignment::find()
            ->joinWith('itemName')
            ->where(['user_id' => $this->adminCompanyId])
            ->andWhere(['type' => AuthItem::TYPE_PERMISSION])
            ->one();
        if ($model) {
            return $model->itemName->name;
        }
        return false;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            return true;
        }
        return false;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->tariff) {
            if ($this->tariffName) {
                AuthAssignment::deleteAll(['item_name' => $this->tariffName, 'user_id' => $this->adminCompanyId]);
            }
            $auth = \Yii::$app->authManager;
            $role = $auth->getPermission($this->tariff);
            $auth->assign($role, $this->adminCompanyId);
        } else {
            AuthAssignment::deleteAll(['item_name' => $this->tariffName, 'user_id' => $this->adminCompanyId]);
        }

        $modelCompany           = ProfileCompany::findOne($this->id);
        $modelCompany->tariff   = $this->tariff ? $this->tariff : null;
        //dd($modelCompany);
        $modelCompany->save();
    }
}