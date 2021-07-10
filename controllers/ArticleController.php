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
            $article->load(Yii::$app->request->post());
            $image = UploadedFile::getInstance($article, 'a_image');
            $nomImg = time() . '_' . bin2hex(random_bytes(10)) . '.' . $image->extension;
            $article->a_image = $nomImg;

            //note : save() appel validate()
            if ($article->save() && $article->a_image) {
                $image->saveAs('uploads/article/' . $nomImg);
                return $this->redirect(['article/show-view', 'a_id' => $article->a_id]);
            }
        }

        return $this->render('form', ['article' => $article]);
    }

    public function actionDelete($a_id)
    {
        $article = Article::findOne($a_id);
        $article->delete();
        //à faire : supprimer l'image
        return $this->redirect(['article/index']);
    }

    //A FAIRE
    public function actionDeleteImage($a_id)
    {
        $article = User::findOne($a_id);

        if ($article->deleteImage()) {
            Yii::$app->session->setFlash('success', 'Your image was removed successfully. Upload another by clicking Browse below');
        } else {
            Yii::$app->session->setFlash('error', 'Error removing image. Please try again later or contact the system admin.');
        }

        return $this->render('profile', ['model'=>$model]);
    }

}
