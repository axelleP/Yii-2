<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class DocumentController extends Controller
{
    public function actionExportPdfArticles()
    {
        $post = Yii::$app->request->post();
        $tabArticles = json_decode($post['articlesJSON'], true);
        $articles = \components\MyHelpers::convertArrayToArrayObjects($tabArticles);

        $pdf = new \app\documents\pdfs\Pdf_1();
        $pdf->tabParams = ['articles' => $articles];
        $pdf->init();
        $pdf->create();
        $pdf->download();
    }

    public function actionExportWordArticles()
    {
        $post = Yii::$app->request->post();
        $tabArticles = json_decode($post['articlesJSON'], true);
        $articles = \components\MyHelpers::convertArrayToArrayObjects($tabArticles);

        $word = new \app\documents\words\Word_1();
        $word->tabParams = ['articles' => $articles];
        $word->init();
        $word->create();
        $word->download();
    }

}
