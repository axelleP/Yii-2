<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Article;
use yii\web\UploadedFile;

class ArticleController extends Controller
{
    public function actionIndex()
    {
        $this->view->title = 'Liste des articles';
        $article = new Article();
        $article->scenario = 'search';
        $dataProvider = $article->search(Yii::$app->request->get());

        return $this->render('list', ['dataProvider' => $dataProvider, 'article' => $article]);
    }

    public function actionPopulateDatabase() {
        $nbArticle = 2;

        for ($i = 1; $i <= $nbArticle; $i++) {
            Article::generateFakeArticle();
        }

        Yii::$app->session->setFlash('successPopulateDatabase', "$nbArticle article(s) crée(s).");

        return $this->redirect(['article/index']);
    }

    public function actionShowView($a_id)
    {
        $article = Article::findOne($a_id);
        return $this->render('view', ['article' => $article]);
    }

    public function actionShowForm($a_id = null)
    {
        if (!empty($a_id)) {
            $article = Article::findOne($a_id);
        } else {
            $article = new Article();
        }

        //soumission formulaire
        if (!empty(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $article->load($post);

            $downloadedImage = UploadedFile::getInstance($article, 'a_image');
            if (!empty($downloadedImage)) {//input file remplit
                $nomImg = \components\MyHelpers::createImageName($downloadedImage);
                $article->a_image = $nomImg;
            } else {//champ caché
                $article->a_image = $post['a_image2'];
            }

            //note : save() appel validate()
            if ($article->save()) {
                if (!empty($downloadedImage)) {
                    //enregistre l'image dans l'application
                    $downloadedImage->saveAs('uploads/article/' . $nomImg);
                }

                return $this->redirect(['article/show-view', 'a_id' => $article->a_id]);
            }
        }

        return $this->render('form', ['article' => $article]);
    }

    public function actionDelete($a_id)
    {
        $article = Article::findOne($a_id);
        $article->delete();
        return $this->redirect(['article/index']);
    }

    public function actionDeleteImage()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $post = Yii::$app->request->post();

        $article = Article::findOne($post['idArticle']);
        $article->scenario = 'deleteImageArticle';
        $article->deleteImage();
        $article->a_image = '';
        $article->save();
    }

}
