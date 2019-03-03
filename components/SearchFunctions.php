<?php

/**
 * Created by PhpStorm.
 * User: franc
 * Date: 28/02/2019
 * Time: 20:56
 */

namespace app\components;

use app\models\Item;
use app\models\UrlLink;
use Yii;
use yii\helpers\Html;

class SearchFunctions
{

    public static function providerImages() {

        // adding "all" selection
        $providers_list[0] = Html::img(
            Yii::$app->request->baseUrl . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'providers' . DIRECTORY_SEPARATOR .'0.jpg'
            , ["class"=>"image-provider"]);

        // add providers
        /** @var \app\models\Provider $provider */
        foreach (\app\models\Provider::find()->all() as $provider) {

            $providers_list[$provider->id] = Html::img(
                Yii::$app->request->baseUrl . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'providers' . DIRECTORY_SEPARATOR . $provider->id .'.jpg'
                , ["class"=>"image-provider"]);
        }

        return $providers_list;
    }


}