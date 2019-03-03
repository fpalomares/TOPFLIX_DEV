<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "backdrop".
 *
 * @property string $id
 * @property string $fk_item
 * @property string $url
 * @property string $path
 *
 * @property Item $fkItem
 */
class Backdrop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'backdrop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_item'], 'integer'],
            [['url', 'path'], 'string', 'max' => 250],
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
            'url' => Yii::t('app', 'Url'),
            'path' => Yii::t('app', 'Path'),
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
