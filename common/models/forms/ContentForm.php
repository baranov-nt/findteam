<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 10.09.2016
 * Time: 18:22
 */

namespace common\models\forms;

use common\models\Content;
use common\models\Identity;
use backend\modules\translate\models\SourceMessage;
use yii\behaviors\TimestampBehavior;
use Yii;

class ContentForm extends Content
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'description', 'location', 'message'], 'required'],
            [['message'], 'string'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['category'], 'string', 'max' => 16],
            [['description', 'location'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Identity::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['message'], 'trim'],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->user_id = Yii::$app->user->id;
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
        $model = ($model = SourceMessage::findOne($this->id)) ? $model : new SourceMessage();
        $model->category = $this->category;
        $model->message = $this->message;
        $model->location = '["'.$this->location.'"]';
        $model->content_id = $this->id;
        $model->save();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeDelete()
    {
        parent::afterDelete();
        SourceMessage::deleteAll(['content_id' => $this->id]);
        return true;
    }
}