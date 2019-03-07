<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'TOPFLIX';
$this->params['breadcrumbs'][] = $this->title;
\app\assets\AppAsset::register($this);
?>

<button class="btn btn-default btn-open-search" id="js-open-search"><i class="glyphicon glyphicon-menu-hamburger"></i></button>
<div class="container-fluid" id="js-empty-block" style="height: 80px;overflow-y: hidden">
    &nbsp;
</div>

<div class="container-fluid item-index" id="js-item-index">

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'div',
            'class' => 'list-wrapper row',
            'id' => 'list-wrapper',
        ],
        'layout' => "<div class='col-xs-12'>{summary}</div>\n{items}\n<div class='col-xs-12 text-center'>{pager}</div>",
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_list_item',['item' => $model]);
        },
        'itemOptions' => [
            'tag' => false,
        ],
        'pager' => [
            'firstPageLabel' => '<<',
            'lastPageLabel' => '>>',
            'nextPageLabel' => '>',
            'prevPageLabel' => '<',
            'maxButtonCount' => 4,
        ],

    ]); ?>

    <?php /* echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'title',
            'short_description:ntext',
            //'description:ntext',
            'original_release_year',
            'type',
            //'original_title',
            //'original_language',
            //'max_season_number',
            //'poster',
            //'poster_fa',
            //'runtime:datetime',
            //'age_certification',
            'imdb_score',
            'filmaffinity_score',
            //'rottentomatoes_score',
            //'jw_id',
            //'fa_id',
            //'imdb_id',
            //'extended_info:boolean',
            //'created_dt',
            //'updated_dt',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); */ ?>
    <?php Pjax::end(); ?>
</div>
