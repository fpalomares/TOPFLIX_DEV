<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Item;

/**
 * ItemSearch represents the model behind the search form of `app\models\Item`.
 */
class ItemSearch extends Item
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'max_season_number', 'runtime', 'jw_id', 'fa_id', 'imdb_id'], 'integer'],
            [['title', 'short_description', 'description', 'original_release_year', 'type', 'original_title', 'original_language', 'poster', 'poster_fa', 'age_certification', 'created_dt', 'updated_dt'], 'safe'],
            [['imdb_score', 'filmaffinity_score', 'rottentomatoes_score'], 'number'],
            [['extended_info'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $query = Item::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 24,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // providers join
        if ( $provider_id = \Yii::$app->request->get('provider_id','') ) {

            $query
                ->innerJoin('item_provider ip', 'ip.fk_item = item.id')
                ->innerJoin('provider p', 'p.id = ip.fk_provider')
                ->andWhere(['p.id' => $provider_id]);
        }

        // genres join
        if ( $genres = \Yii::$app->request->get('genres','') ) {

            $query
                ->innerJoin('item_genre ig', 'ig.fk_item = item.id')
                ->innerJoin('genre g', 'g.id = ig.fk_genre')
                ->andWhere(['IN','g.id', $genres]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'max_season_number' => $this->max_season_number,
            'runtime' => $this->runtime,
            'rottentomatoes_score' => $this->rottentomatoes_score,
            'jw_id' => $this->jw_id,
            'fa_id' => $this->fa_id,
            'imdb_id' => $this->imdb_id,
            'extended_info' => $this->extended_info,
            'created_dt' => $this->created_dt,
            'updated_dt' => $this->updated_dt,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'short_description', $this->short_description])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'original_title', $this->original_title])
            ->andFilterWhere(['like', 'original_language', $this->original_language])
            ->andFilterWhere(['>=', 'imdb_score', $this->imdb_score])
            ->andFilterWhere(['>=', 'filmaffinity_score', $this->filmaffinity_score])
            ->andFilterWhere(['like', 'age_certification', $this->age_certification]);

        if ( $this->original_release_year ) {
            $years_range = explode(",",$this->original_release_year);
            $query
                ->andFilterWhere(['>=', 'original_release_year', $years_range[0]])
                ->andFilterWhere(['<=', 'original_release_year', $years_range[1]]);
        }

        // only with image (poster)
        $query->andWhere(['not',['poster' => null]]);

        // sorting regarding scores selection
        if (
            $this->filmaffinity_score && $this->imdb_score ||
            (empty($this->filmaffinity_score) && empty($this->imdb_score))
        ) {
            $query->orderBy('imdb_score DESC, filmaffinity_score DESC');
        } else if ( $this->filmaffinity_score ) {
            $query->orderBy('filmaffinity_score DESC');
        } else {
            $query->orderBy('imdb_score DESC');
        }

        return $dataProvider;
    }
}
