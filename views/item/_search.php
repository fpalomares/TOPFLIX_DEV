<?php

use app\components\LanguageFunctions;
use app\components\SearchFunctions;
use app\models\Genre;
use app\models\Item;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div id="search" class="container-fluid hidden">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-xs-12 col-lg-3"><?= $form->field($model, 'title') ?></div>
        <div class="col-xs-12 col-lg-4"><?= $form->field($model, 'short_description') ?></div>
        <div class="col-xs-12 col-sm-6 col-lg-4">
            <?php // echo $form->field($model, 'original_release_year')->textInput(['class'=>'js-range-year','type' => 'text','data-slider-step'=>'1','data-slider-min'=>'1940','data-slider-max'=>date('Y')]) ?>
            <div class="form-group field-itemsearch-original_release_year">
                <label class="control-label" for="itemsearch-original_release_year">Año de estreno</label>
                <input type="text" id="itemsearch-original_release_year" class="" name="ItemSearch[original_release_year]" value="<?= $model->original_release_year ?? '1940,'.date('Y') ?>" data-slider-step="1" data-slider-min="1940" data-slider-max="<?=date('Y')?>" data-value="">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-lg-3">
            <?php echo $form->field($model, 'type')->radioList([''=>'Todos','movie'=>'Películas','show'=>'Series'],
                [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $checked_prop = ($checked || empty($value) ) ? 'checked' : '';
                        $return = '<label class="">';
                        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" '.$checked_prop.'>';
                        $return .= '<span>' . ucwords($label) . '</span>';
                        $return .= '</label>';

                        return $return;
                    },
                    'class'=>'type-radio'
                ]
            ) ?>
        </div>
        <div class="col-xs-12 col-lg-4">
            <div class="form-group">
                <?= Html::label('Proveedor de contenido') ?>
                <?php echo Html::radioList('provider_id',\Yii::$app->request->getQueryParam('provider_id',0),SearchFunctions::providerImages(),['encode' => false, 'class' => 'provider-radio']) ?>
            </div>
        </div>
        <div class="col-xs-10 col-lg-5">
            <div class="form-group">
                <?= Html::label('Géneros') ?>
                <?php echo Html::checkboxList('genres',\Yii::$app->request->getQueryParam('genres',0),ArrayHelper::map(Genre::find()->all(),'id','name'),
                    [
                        'encode' => false,
                        'class' => 'genres-checkboxlist',
                        'item' => function($index, $label, $name, $checked, $value) {
                            $checked_prop = ($checked) ? 'checked' : '';
                            $return = '<label class="">';
                            $return .= '<input type="checkbox" name="' . $name . '" value="' . $value . '" '.$checked_prop.'>';
                            $return .= '<span>' . ucwords($label) . '</span>';
                            $return .= '</label>';

                            return $return;
                        },
                    ]) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-lg-3">
            <?php echo $form->field($model, 'imdb_score')->textInput(['type' => 'number','data-slider-step'=>'0.1','data-slider-min'=>'0','data-slider-max'=>'10']) ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-3">
            <?php echo $form->field($model, 'filmaffinity_score')->textInput(['type' => 'number','data-slider-step'=>'0.1','data-slider-min'=>'0','data-slider-max'=>'10']) ?>
        </div>
        <div class="col-xs-8 col-sm-6 col-lg-3">
            <?php echo $form->field($model, 'original_language')->dropDownList(LanguageFunctions::getLanguages()) ?>
        </div>
    </div>

    <?= Html::hiddenInput('actor_id', \Yii::$app->request->getQueryParam('actor_id','')) ?>
    <?= Html::hiddenInput('director_id', \Yii::$app->request->getQueryParam('director_id','')) ?>

    <?php //$form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'original_title') ?>

    <?php // echo $form->field($model, 'original_language') ?>

    <?php // echo $form->field($model, 'max_season_number') ?>

    <?php // echo $form->field($model, 'poster') ?>

    <?php // echo $form->field($model, 'poster_fa') ?>

    <?php // echo $form->field($model, 'runtime') ?>

    <?php // echo $form->field($model, 'age_certification') ?>

    <?php // echo $form->field($model, 'rottentomatoes_score') ?>

    <?php // echo $form->field($model, 'jw_id') ?>

    <?php // echo $form->field($model, 'fa_id') ?>

    <?php // echo $form->field($model, 'imdb_id') ?>

    <?php // echo $form->field($model, 'extended_info')->checkbox() ?>

    <?php // echo $form->field($model, 'created_dt') ?>

    <?php // echo $form->field($model, 'updated_dt') ?>

    <div class="form-group">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary js-btn-search']) ?>
        <?= Html::a('Resetear', "/item", ['class' => 'btn btn-default']) ?>
        <span class="text-credits">2019. Created by F. Palomares</span>
    </div>

    <?php ActiveForm::end(); ?>
</div>
