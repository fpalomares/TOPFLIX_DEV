<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "provider".
 *
 * @property string $id
 * @property string $code
 * @property string $name
 * @property string $jw_id
 *
 * @property ItemProvider[] $itemProviders
 * @property Item[] $fkItems
 * @property UrlLink[] $urlLinks
 */
class Provider extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'provider';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['jw_id'], 'integer'],
            [['code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'jw_id' => Yii::t('app', 'Jw ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemProviders()
    {
        return $this->hasMany(ItemProvider::className(), ['fk_provider' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkItems()
    {
        return $this->hasMany(Item::className(), ['id' => 'fk_item'])->viaTable('item_provider', ['fk_provider' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUrlLinks()
    {
        return $this->hasMany(UrlLink::className(), ['fk_provider' => 'id']);
    }
}
