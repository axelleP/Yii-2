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
            'attribute' => 'date_creation',
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
            'attribute' => 'prix',
        ],
        [
            'label' => $article->getLabel('a_quantite'),
            'attribute' => 'quantite',
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