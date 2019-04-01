<?php

namespace app\controllers;

use app\models\Backdrop;
use Yii;
use app\models\Item;
use app\models\ItemSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ItemController implements the CRUD actions for item model.
 */
class ItemController extends Controller
{
    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all item models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //$this->view->registerJsFile('/js/search.js',['depends' => 'yii\web\JqueryAsset']);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function actionItem($id)
    {
        if (empty($id)) {
            throw new NotFoundHttpException("Missing required parameter.");
        }

        /** @var Item $item */
        $item = Item::findOne($id);

        if (empty($item)) {
            throw new NotFoundHttpException("Item not found");
        }

        $item_backdrops = [];

        /** @var Backdrop $backdrop */
        foreach ($item->backdrops as $backdrop) {
            $img=  'https://images.justwatch.com' . str_replace('{profile}', 's1440',$backdrop->url);
            $item_backdrops[] = "<div class='backdrop' style='background-image: url( ". $img  ." )'></div>";
        }

        return $this->render('item.twig', [
            'item'              => $item,
            'item_backdrops'    => $item_backdrops
        ]);
    }

    /**
     * Finds the item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = item::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
