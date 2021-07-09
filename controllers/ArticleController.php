<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Article;

class ArticleController extends Controller
{
    public function actionIndex()
    {
        $this->view->title = 'Liste des articles';
        $model = new Article();
        $model->scenario = 'search';
        $dataProvider = $model->search(Yii::$app->request->get());

        return $this->render('list', ['dataProvider' => $dataProvider, 'model' => $model]);
    }

}
