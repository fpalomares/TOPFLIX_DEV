<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'original_release_year')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList([ 'show' => 'Show', 'movie' => 'Movie', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'original_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'original_language')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'max_season_number')->textInput() ?>

    <?= $form->field($model, 'poster')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'poster_fa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'runtime')->textInput() ?>

    <?= $form->field($model, 'age_certification')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imdb_score')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'filmaffinity_score')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rottentomatoes_score')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jw_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fa_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imdb_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'extended_info')->checkbox() ?>

    <?= $form->field($model, 'created_dt')->textInput() ?>

    <?= $form->field($model, 'updated_dt')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
