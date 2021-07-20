<?php
use yii\widgets\DetailView;
use yii\helpers\Html;

echo Html::a('Retour aux articles', ['article/index'], ['class' => 'profile-link']);
echo '</br></br>';

echo DetailView::widget([
    'model' => $article,
    'attributes' => [
        [
            'label' => $article->getLabel('a_date_creation'),
            'attribute' => 'a_date_creation',
            'value' => function($data) {
                $formatter = Yii::$app->formatter;//config par défaut dans web.php
                return $formatter->asDate($data->a_date_creation);
            },
        ],
        [
            'label' => $article->getLabel('a_nom'),
            'attribute' => 'a_nom',
        ],
        [
            'label' => $article->getLabel('a_description'),
            'attribute' => 'a_description',
            'format' => 'ntext',
        ],
        [
            'label' => $article->getLabel('a_prix'),
            'attribute' => 'a_prix',
            'value' => function($data) {
                $formatter = Yii::$app->formatter;//config par défaut dans web.php
                return $formatter->asCurrency($data->a_prix);
            },
        ],
        [
            'label' => $article->getLabel('a_quantite'),
            'attribute' => 'a_quantite',
            'value' => function($data) {
                $formatter = Yii::$app->formatter;//config par défaut dans web.php
                return $formatter->asInteger($data->a_quantite);
            },
        ],
        [
            'label' => $article->getLabel('a_image'),
            'attribute' => 'a_image',
            'format' => 'html',
            'value' => function($data) {
                return Html::img('@web/uploads/article/' . $data->a_image);
            },
        ],
    ],
]);