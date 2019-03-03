<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_genre".
 *
 * @property string $id
 * @property string $fk_item
 * @property string $fk_genre
 *
 * @property Genre $fkGenre
 * @property Item $fkItem
 */
class ItemGenre extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_genre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_item', 'fk_genre'], 'integer'],
            [['fk_genre'], 'exist', 'skipOnError' => true, 'targetClass' => Genre::className(), 'targetAttribute' => ['fk_genre' => 'id']],
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
            'fk_item' => Yii::t('app', 'Fk Item'),
            'fk_genre' => Yii::t('app', 'Fk Genre'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkGenre()
    {
        return $this->hasOne(Genre::className(), ['id' => 'fk_genre']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'fk_item']);
    }
}
