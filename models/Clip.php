<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clip".
 *
 * @property string $id
 * @property string $external_id
 * @property int $height
 * @property string $name
 * @property string $provider
 * @property string $type
 * @property string $fk_item
 *
 * @property Item $fkItem
 */
class Clip extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['external_id', 'provider'], 'required'],
            [['height', 'fk_item'], 'integer'],
            [['type'], 'string'],
            [['external_id', 'name'], 'string', 'max' => 100],
            [['provider'], 'string', 'max' => 50],
            [['fk_item'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['fk_item' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'external_id' => Yii::t('app', 'External ID'),
            'height' => Yii::t('app', 'Height'),
            'name' => Yii::t('app', 'Name'),
            'provider' => Yii::t('app', 'Provider'),
            'type' => Yii::t('app', 'Type'),
            'fk_item' => Yii::t('app', 'Fk Item'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'fk_item']);
    }
}
