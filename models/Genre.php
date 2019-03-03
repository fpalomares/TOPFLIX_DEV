<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "genre".
 *
 * @property string $id
 * @property string $code
 * @property string $name
 *
 * @property ItemGenre[] $itemGenres
 */
class Genre extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'genre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 100],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemGenres()
    {
        return $this->hasMany(ItemGenre::className(), ['fk_genre' => 'id']);
    }
}
