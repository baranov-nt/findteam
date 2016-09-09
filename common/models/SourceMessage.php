<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "source_message".
 *
 * @property integer $id
 * @property string $hash
 * @property string $category
 * @property string $message
 * @property string $location
 *
 * @property Message[] $messages
 */
class SourceMessage extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'source_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message', 'location'], 'string'],
            [['hash'], 'string', 'max' => 32],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'hash' => \Yii::t('app', 'Hash'),
            'category' => \Yii::t('app', 'Category'),
            'message' => \Yii::t('app', 'Message'),
            'location' => \Yii::t('app', 'Location'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['id' => 'id'])->indexBy('language');
    }

    public function saveMessages($messages)
    {
        foreach ($messages as $key => $value) {
            Message::deleteAll([
                'id' => $this->id,
                'language' => $key
            ]);

            $modelMessage = new Message();
            $modelMessage->id = $this->id;
            $modelMessage->language = $key;
            $modelMessage->translation = $value;
            $modelMessage = $modelMessage->save();

            if(!$modelMessage) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return array|SourceMessage[]
     */
    public static function getCategories()
    {
        $modelSourceMessage = SourceMessage::find()
            ->select('category')
            ->distinct('category')
            ->asArray()
            ->all()
        ;
        $arrayCategories = ArrayHelper::map($modelSourceMessage, 'category', 'category');
        return $arrayCategories;
    }
}
