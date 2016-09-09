<?php

namespace backend\modules\translate\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property string $language
 * @property string $hash
 * @property string $translation
 *
 * @property SourceMessage $id0
 */
class Message extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16],
            [['hash'], 'string', 'max' => 32],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => SourceMessage::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'language' => \Yii::t('app', 'Язык'),
            'hash' => \Yii::t('app', 'Хеш'),
            'translation' => \Yii::t('app', 'Перевод'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
    }
}
