<?php

use app\models\Item;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $item app\models\item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 text-center item-card">

    <a class="btn-link item-link" href="/item/item?id=<?= $item->id ?>">
        <div  class="col-xs-12 scores">
            <span class="pull-left imdb-score"><?= $item->imdb_score ?></span>
            <?php if ( !empty($item->filmaffinity_score) && $item->filmaffinity_score != 0.0 ) { ?>
                <span class="pull-right fa-score"><?= $item->filmaffinity_score ?></span>
            <?php } ?>
        </div>
        <img alt="poster"
             class="img-responsive poster"
             src="/img/posters/<?=$item->id?>.jpg"
        />
        <?php $margintop = (strlen($item->title) > 30 ) ? 'margintop-info' : ''; ?>
        <div  class="col-xs-12 info <?= $margintop ?>">
            <span class="top"><?= $item->title ?></span>
            <span class="bottom"><?= \Yii::t('app', $item->type) ?></span>
        </div>
    </a>
</div>
