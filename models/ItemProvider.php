<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_provider".
 *
 * @property string $id
 * @property string $fk_item
 * @property string $fk_provider
 *
 * @property Item $fkItem
 * @property Provider $fkProvider
 */
class ItemProvider extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_provider';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_item', 'fk_provider'], 'integer'],
            [['fk_item', 'fk_provider'], 'unique', 'targetAttribute' => ['fk_item', 'fk_provider']],
            [['fk_item'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['fk_item' => 'id']],
            [['fk_provider'], 'exist', 'skipOnError' => true, 'targetClass' => Provider::className(), 'targetAttribute' => ['fk_provider' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fk_item' => Yii::t('app', 'Fk Item'),
            'fk_provider' => Yii::t('app', 'Fk Provider'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'fk_item']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkProvider()
    {
        return $this->hasOne(Provider::className(), ['id' => 'fk_provider']);
    }
}
