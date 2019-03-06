<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Backdrop;
use app\models\Clip;
use app\models\Credit;
use app\models\Item;
use app\models\ItemGenre;
use app\models\ItemProvider;
use app\models\Person;
use app\models\Provider;
use app\models\UrlLink;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Json;

// id jw => id topflix
const provider_id = [
    8   => 2, //netflix
    118 => 4, //hbo
    149 => 1, //mvs
    119 => 3, // prime video
];


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CronController extends Controller
{
    public function beforeAction($action)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 0);
        return parent::beforeAction($action);
    }

    public function actionGetitemjw( $section = '') {


        $transaction = Yii::$app->db->beginTransaction();

        try {

            $i = 1;
            while ($i < 4) {

                $items = Item::find()
                    //->where('extended_info is NULL OR extended_info <> 1')
                    //->where(['type'=>'movie'])
                    ->limit(150)
                    ->all();

                /** @var Item $item */
                foreach ($items as $item) {

                    $url = "https://apis.justwatch.com/content/titles/" . $item->type . "/" . $item->jw_id . "/locale/es_ES";

                    $json_data = file_get_contents($url);
                    $data = Json::decode($json_data);

                    // clips
                    if (
                        ( empty($section) || $section == 'clips' ) &&
                        !empty($data['clips'])
                    ) {

                        $clips = $data['clips'];

                        foreach ($clips as $clip) {

                            $clip_instance = new Clip();
                            $clip_instance->setAttributes($clip);
                            $clip_instance->fk_item = $item->id;
                            $clip_instance->save();
                        }
                    }

                    // genres
                    if (
                        ( empty($section) || $section == 'genres' ) &&
                        !empty($data['genre_ids'])
                    ) {

                        $genres = $data['genre_ids'];

                        foreach ($genres as $genre) {

                            $genre_item = new ItemGenre();
                            $genre_item->fk_item = $item->id;
                            $genre_item->fk_genre = $genre;
                            $genre_item->save();
                        }
                    }

                    // credits
                    $list_credits = ['ACTOR', 'DIRECTOR', 'PRODUCER', 'WRITER'];
                    if (
                        ( empty($section) || $section == 'credits' ) &&
                        !empty($data['credits'])
                    ) {

                        $credits = $data['credits'];

                        foreach ($credits as $credit) {

                            if (in_array($credit['role'], $list_credits)) {

                                $credit_instance = new Credit();
                                $credit_instance->fk_item = $item->id;
                                $credit_instance->setAttributes($credit);

                                // find person, otherwise create it
                                $person = Person::find()->where(['name' => $credit_instance->name])->one();
                                if ( empty($person) ) {

                                    $person = new Person();
                                    $person->name = $credit_instance->name;
                                    $person->save();
                                }
                                $credit_instance->fk_person = $person->id;
                                $credit_instance->save();
                            }
                        }
                    }

                    // score
                    if (
                        ( empty($section) || $section == 'scoring' ) &&
                        !empty($data['scoring'])
                    ) {

                        $scoring = $data['scoring'];

                        foreach ($scoring as $score) {

                            if ($score['provider_type'] == 'imdb:score') {
                                $item->imdb_score = $score['value'];
                            }

                            if ($score['provider_type'] == 'tomato:score') {
                                $item->rottentomatoes_score = $score['value'] / 10;
                            }
                        }
                    }

                    // offers (url's for movies only)
                    if (
                        ( empty($section) || $section == 'offers' ) &&
                        !empty($data['offers']) && $data['object_type'] == 'movie'
                    ) {

                        $offers = $data['offers'];

                        foreach ($offers as $offer) {

                            if (
                                $offer['monetization_type'] == "flatrate" &&
                                !empty($offer['urls']) &&
                                !empty(provider_id[$offer['provider_id']])
                            ) {

                                $url = new UrlLink();
                                $url->fk_item = $item->id;
                                $url->fk_provider = provider_id[$offer['provider_id']];
                                $url->quality = $offer['presentation_type'];
                                $url->web = ($offer['urls']['standard_web']) ?? '';
                                $url->android = ($offer['urls']['deeplink_android']) ?? '';
                                $url->ios = ($offer['urls']['deeplink_ios']) ?? '';

                                $url->save();
                            }
                        }
                    }

                    $item->extended_info = true;

                    $item->save();

                    //sleep(2);
                }
                $i++;
            }

            $transaction->commit();

        } catch ( \Exception $e ) {
            $transaction->rollBack();
            print_r($e->getMessage());echo "\n";
        }

        return ExitCode::OK;
    }

    public function actionGetjwbackdrops() {


        //$transaction = Yii::$app->db->beginTransaction();

        try {

            $items = Item::find()
                //->where('extended_info is NULL OR extended_info <> 1')
                //->where(['type'=>'movie'])
                //->limit(150)
                ->all();

            /** @var Item $item */
            foreach ($items as $item) {

                $url = "https://apis.justwatch.com/content/titles/" . $item->type . "/" . $item->jw_id . "/locale/es_ES";

                $json_data = file_get_contents($url);
                $data = Json::decode($json_data);

                // backdrops
                if ( !$item->backdrops ) {

                    if ( !empty($data['backdrops']) ) {

                        $backdrops = $data['backdrops'];

                        foreach ($backdrops as $backdrop) {

                            $backdrop_instance = new Backdrop();
                            $backdrop_instance->fk_item = $item->id;
                            $backdrop_instance->url = $backdrop['backdrop_url'];
                            $backdrop_instance->save();
                        }
                    }
                }


                // update score score
                if ( !empty($data['scoring']) ) {

                    $scoring = $data['scoring'];

                    foreach ($scoring as $score) {

                        if (
                            $score['provider_type'] == 'imdb:score' &&
                            $item->imdb_score != $score['value']
                        ) {
                            $item->imdb_score = $score['value'];

                        } else if ($score['provider_type'] == 'tomato:score' &&
                            $item->rottentomatoes_score != $score['value'] / 10
                        ) {
                            $item->rottentomatoes_score = $score['value'] / 10;
                        }
                    }
                }


                $item->save();

                sleep(1);
            }

            //$transaction->commit();

        } catch ( \Exception $e ) {
            //$transaction->rollBack();
            print_r($e->getMessage());echo "\n";
        }

        return ExitCode::OK;
    }

    public function actionGetstreaming( $section = '') {


        $transaction = Yii::$app->db->beginTransaction();

        try {

            $items = Item::find()
                //->where('extended_info is NULL OR extended_info <> 1')
                ->where(['type'=>'show'])
                //->limit(150)
                ->all();

            /** @var Item $item */
            foreach ($items as $item) {

                $url = "https://apis.justwatch.com/content/titles/" . $item->type . "/" . $item->jw_id . "/locale/es_ES";

                $json_data = file_get_contents($url);
                $data = Json::decode($json_data);


                // offers (url's for movies only)
                if ( !empty($data['offers']) ) {

                    $offers = $data['offers'];

                    foreach ($offers as $offer) {

                        if (
                            $offer['monetization_type'] == "flatrate" &&
                            !empty($offer['urls']) &&
                            !empty(provider_id[$offer['provider_id']])
                        ) {

                            $url = new UrlLink();
                            $url->fk_item = $item->id;
                            $url->fk_provider = provider_id[$offer['provider_id']];
                            $url->quality = $offer['presentation_type'];
                            $url->web = ($offer['urls']['standard_web']) ?? '';
                            $url->android = ($offer['urls']['deeplink_android']) ?? '';
                            $url->ios = ($offer['urls']['deeplink_ios']) ?? '';

                            $url->save();
                            print_r($item->title);echo "\n";
                        }
                    }
                }

                //sleep(2);
            }


            $transaction->commit();

        } catch ( \Exception $e ) {
            $transaction->rollBack();
            print_r($e->getMessage());echo "\n";
        }

        return ExitCode::OK;
    }

    public function actionGetdata() {

        //"mvs","nfx","prv","hbo"
        $provider = "hbo";
        /** @var Provider $provider_instance */
        $provider_instance = Provider::find()->where(['code'=>$provider])->one();
        $page = 1;
        $page_size = 100;

        while ( $page < 16 ) {

            $body = '{
                "age_certifications":null,
                "content_types":null,
                "genres":null,
                "languages":null,
                "max_price":null,
                "min_price":null,
                "monetization_types":["flatrate","rent","buy"],
                "page":'.$page.',
                "page_size":'.$page_size.',
                "presentation_types":null,
                "providers":["'.$provider.'"],
                "release_year_from":null,
                "release_year_until":null,
                "scoring_filter_types":null,
                "timeline_type":null
            }';

            $body = urlencode($body);

            print_r('PAGINA'. $page);echo "\n";

            $url = "https://apis.justwatch.com/content/titles/es_ES/popular?body=$body";

            $json_data = file_get_contents($url);

            $data = Json::decode($json_data);

            if (!empty($data['items'])) {

                foreach ($data['items'] as $item) {

                    $item_instance = Item::find()
                        ->where([
                            'jw_id' => $item['id'],
                            'type' => $item['object_type']
                        ])
                        ->one();

                    if ( !$item_instance ) {

                        $item_instance = new Item();
                        $item_instance->setAttributes($item);
                        $item_instance->type = $item['object_type'];
                        $item_instance->jw_id = $item['id'];

                        if (!$item_instance->save()) {
                            print_r(serialize($item_instance->getFirstErrors()));echo "\n";
                        }

                        $provider_item = new ItemProvider();
                        $provider_item->fk_item = $item_instance->id;
                        $provider_item->fk_provider = $provider_instance->id;

                        if (!$provider_item->save()) {
                            print_r(serialize($provider_item->getFirstErrors()));echo "\n";
                        }

                    } else {

                        $provider_item = new ItemProvider();
                        $provider_item->fk_item = $item_instance->id;
                        $provider_item->fk_provider = $provider_instance->id;

                        if (!$provider_item->save()) {
                            print_r(serialize($provider_item->getFirstErrors()));echo "\n";
                        }
                    }

                }

            } else {
                echo("error");
            }

            $page++;

            sleep(3);
        }

        return ExitCode::OK;

    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionGetfascore($message = '')
    {

        //$transaction = Yii::$app->db->beginTransaction();

        try {

            $i = 1;

            while ($i < 2) {

                $items = Item::find()
                    ->where('filmaffinity_score is NULL')
                    ->andWhere(['>','id',5981])
                    ->limit(500)
                    ->all();

                /** @var Item $item */
                foreach ($items as $item) {
                    print_r("--------------------------------");echo "\n";
                    $query = urlencode($item->title);

                    $url = "https://filmaffinity-unofficial.herokuapp.com/api/search?q=".$query."&lang=ES";

                    $json_data = file_get_contents($url);
                    print_r("BUSCANDO:".$item->title);echo "\n";
                    $suggestions = Json::decode($json_data);

                    if ( empty($suggestions) ) {
                        continue;
                    }

                    $title_og = $item->title;

                    $title = $title_og;
                    $title_alt = $title_og . " (".$item->original_release_year.")";
                    $title_alt_2 = $title_alt_3 = $title_alt_4 = $title_alt_5 = '';

                    if ( $item->type == 'show' ) {
                        $title          = $title_og . " (Serie de TV)";//" (TV Series)";
                        $title_alt      = $title_og . " (Miniserie de TV)";//" (TV Miniseries)";
                        $title_alt_2    = $title_og . " (TV)";
                        $title_alt_3    = $title_og . " (Serie de TV)". " (".$item->original_release_year.")";
                        $title_alt_4    = $title_og . " (Miniserie de TV)". " (".$item->original_release_year.")";
                        $title_alt_5    = $title_og . " (TV)". " (".$item->original_release_year.")";
                    }

                    foreach ($suggestions as $suggestion) {
                        print_r("Suggestion:".$suggestion['title']);echo "\n";
                        if (
                            count($suggestions) == 1 ||
                            strtolower(str_replace(" ","",$suggestion['title'])) == strtolower(str_replace(" ","",$title)) ||
                            strtolower(str_replace(" ","",$suggestion['title'])) == strtolower(str_replace(" ","",$title_alt)) ||
                            strtolower(str_replace(" ","",$suggestion['title'])) == strtolower(str_replace(" ","",$title_alt_2)) ||
                            strtolower(str_replace(" ","",$suggestion['title'])) == strtolower(str_replace(" ","",$title_alt_3)) ||
                            strtolower(str_replace(" ","",$suggestion['title'])) == strtolower(str_replace(" ","",$title_alt_4)) ||
                            strtolower(str_replace(" ","",$suggestion['title'])) == strtolower(str_replace(" ","",$title_alt_5))
                        ) {

                            $item->fa_id = $suggestion['id'];

                            $url_item = "https://filmaffinity-unofficial.herokuapp.com/api/movie/$item->fa_id?lang=ES";

                            $json_data_item = file_get_contents($url_item);

                            $fa_item = Json::decode($json_data_item);

                            if (
                                !empty($fa_item) &&
                                ( count($suggestions) == 1 || $fa_item['year'] == $item->original_release_year )
                            ) {
                                print_r("ENCONTRADO:".$item->title." ".$fa_item['rating']);echo "\n";

                                $item->filmaffinity_score = $fa_item['rating'] ?? 0.0;
                                $item->poster_fa = $fa_item['poster_big'];
                                if (!$item->save()) {
                                    print_r($item->getFirstErrors());echo "\n";
                                }

                                break;
                            }
                        }
                    }
                    sleep(2);
                }
                $i++;
            }

            //$transaction->commit();

        } catch ( \Exception $e ) {
            //$transaction->rollBack();
            print_r($e->getMessage());echo "\n";
        }

        return ExitCode::OK;
    }
}
