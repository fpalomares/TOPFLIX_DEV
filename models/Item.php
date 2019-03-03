<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property string $id
 * @property string $title
 * @property string $short_description
 * @property string $description
 * @property string $original_release_year
 * @property string $type
 * @property string $original_title
 * @property string $original_language
 * @property int $max_season_number
 * @property string $poster
 * @property string $poster_fa
 * @property int $runtime
 * @property string $age_certification
 * @property string $imdb_score
 * @property string $filmaffinity_score
 * @property string $rottentomatoes_score
 * @property string $jw_id
 * @property string $fa_id
 * @property string $imdb_id
 * @property bool $extended_info
 * @property string $created_dt
 * @property string $updated_dt
 *
 * @property Backdrop[] $backdrops
 * @property Clip[] $clips
 * @property Credit[] $credits
 * @property ItemGenre[] $itemGenres
 * @property ItemProvider[] $itemProviders
 * @property Provider[] $fkProviders
 * @property UrlLink[] $urlLinks
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['short_description', 'description', 'type'], 'string'],
            [['original_release_year', 'created_dt', 'updated_dt'], 'safe'],
            [['type'], 'required'],
            [['max_season_number', 'runtime', 'jw_id', 'fa_id', 'imdb_id'], 'integer'],
            [['imdb_score', 'filmaffinity_score', 'rottentomatoes_score'], 'number'],
            [['extended_info'], 'boolean'],
            [['title', 'original_title'], 'string', 'max' => 100],
            [['original_language'], 'string', 'max' => 5],
            [['poster', 'age_certification'], 'string', 'max' => 50],
            [['poster_fa'], 'string', 'max' => 200],
            [['type', 'jw_id'], 'unique', 'targetAttribute' => ['type', 'jw_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'short_description' => Yii::t('app', 'Short Description'),
            'description' => Yii::t('app', 'Description'),
            'original_release_year' => Yii::t('app', 'Original Release Year'),
            'type' => Yii::t('app', 'Type'),
            'original_title' => Yii::t('app', 'Original Title'),
            'original_language' => Yii::t('app', 'Original Language'),
            'max_season_number' => Yii::t('app', 'Max Season Number'),
            'poster' => Yii::t('app', 'Poster'),
            'poster_fa' => Yii::t('app', 'Poster Fa'),
            'runtime' => Yii::t('app', 'Runtime'),
            'age_certification' => Yii::t('app', 'Age Certification'),
            'imdb_score' => Yii::t('app', 'Imdb Score'),
            'filmaffinity_score' => Yii::t('app', 'Filmaffinity Score'),
            'rottentomatoes_score' => Yii::t('app', 'Rottentomatoes Score'),
            'jw_id' => Yii::t('app', 'Jw ID'),
            'fa_id' => Yii::t('app', 'Fa ID'),
            'imdb_id' => Yii::t('app', 'Imdb ID'),
            'extended_info' => Yii::t('app', 'Extended Info'),
            'created_dt' => Yii::t('app', 'Created Dt'),
            'updated_dt' => Yii::t('app', 'Updated Dt'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBackdrops()
    {
        return $this->hasMany(Backdrop::className(), ['fk_item' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClips()
    {
        return $this->hasMany(Clip::className(), ['fk_item' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredits()
    {
        return $this->hasMany(Credit::className(), ['fk_item' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemGenres()
    {
        return $this->hasMany(ItemGenre::className(), ['fk_item' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemProviders()
    {
        return $this->hasMany(ItemProvider::className(), ['fk_item' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkProviders()
    {
        return $this->hasMany(Provider::className(), ['id' => 'fk_provider'])->viaTable('item_provider', ['fk_item' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUrlLinks()
    {
        return $this->hasMany(UrlLink::className(), ['fk_item' => 'id']);
    }
}
