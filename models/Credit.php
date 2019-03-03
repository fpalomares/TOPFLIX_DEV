<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "credit".
 *
 * @property string $id
 * @property string $name
 * @property string $character_name
 * @property string $fk_person
 * @property string $role
 * @property string $fk_item
 *
 * @property Item $fkItem
 * @property Person $fkPerson
 */
class Credit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'fk_person', 'role'], 'required'],
            [['fk_person', 'fk_item'], 'integer'],
            [['role'], 'string'],
            [['name', 'character_name'], 'string', 'max' => 100],
            [['fk_item'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['fk_item' => 'id']],
            [['fk_person'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['fk_person' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'character_name' => Yii::t('app', 'Character Name'),
            'fk_person' => Yii::t('app', 'Fk Person'),
            'role' => Yii::t('app', 'Role'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'fk_person']);
    }
}
