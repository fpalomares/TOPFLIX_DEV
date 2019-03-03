<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\item */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'short_description:ntext',
            'description:ntext',
            'original_release_year',
            'type',
            'original_title',
            'original_language',
            'max_season_number',
            'poster',
            'poster_fa',
            'runtime:datetime',
            'age_certification',
            'imdb_score',
            'filmaffinity_score',
            'rottentomatoes_score',
            'jw_id',
            'fa_id',
            'imdb_id',
            'extended_info:boolean',
            'created_dt',
            'updated_dt',
        ],
    ]) ?>

</div>
