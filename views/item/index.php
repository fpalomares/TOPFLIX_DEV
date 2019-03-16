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

<?php Pjax::begin(['timeout'=>5000]); ?>
<?php echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="container-fluid item-index" id="js-item-index">

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'div',
            'class' => 'list-wrapper row margin-bottom-20px',
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

    <div class="text-center">
        <iframe src="https://rcm-eu.amazon-adsystem.com/e/cm?o=30&p=12&l=ur1&category=electronica&banner=046J26ETS9M7NDGDASG2&f=ifr&linkID=ca89e308b39764d8c6775bcd92fe5a18&t=bballislife-21&tracking_id=bballislife-21" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
    </div>
</div>
