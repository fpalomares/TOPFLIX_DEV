<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "url_link".
 *
 * @property string $id
 * @property string $fk_item
 * @property string $fk_provider
 * @property string $web
 * @property string $android
 * @property string $ios
 * @property string $quality
 *
 * @property Item $fkItem
 * @property Provider $fkProvider
 */
class UrlLink extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'url_link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_item', 'fk_provider'], 'integer'],
            [['web', 'android', 'ios'], 'string'],
            [['quality'], 'string', 'max' => 10],
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
            'web' => Yii::t('app', 'Web'),
            'android' => Yii::t('app', 'Android'),
            'ios' => Yii::t('app', 'Ios'),
            'quality' => Yii::t('app', 'Quality'),
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
