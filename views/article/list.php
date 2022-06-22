<?php
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;

//session
if (Yii::$app->session->hasFlash('successPopulateDatabase')) {
    echo "<div class='alert alert-success'>";
    echo Yii::$app->session->getFlash('successPopulateDatabase');
    echo "</div>";
}

echo '</br></br>';

//icônes de téléchargement
echo Html::a('<img src="https://img.icons8.com/office/60/000000/pdf.png"/>'
, ['document/export-pdf-articles']
, ['data-method' => 'post'
    , 'data-params' => ['articlesJSON' => $articlesJSON]
  ]
);
echo Html::a('<img src="https://img.icons8.com/color/60/000000/word.png"/>'
, ['document/export-word-articles'], ['data-method' => 'post', 'data-params' => ['articlesJSON' => $articlesJSON]]);
echo Html::a('<img src="https://img.icons8.com/color/60/000000/export-excel.png"/>'
, ['document/export-excel-articles'], ['data-method' => 'post', 'data-params' => ['articlesJSON' => $articlesJSON]]);
echo '</br></br>';

//actions
echo Html::a('Ajouter', ['article/show-form'], ['class' => 'btn btn-primary']);
echo '&nbsp;&nbsp;&nbsp;';
echo Html::a('Générer des articles test', ['article/populate-database'], ['class' => 'btn btn-primary']);
echo '</br></br></br>';

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'emptyCell' => '',
    'filterModel' => $article,
    'tableOptions' => ['class' => ' table table-bordered table-hover'],
    'columns' => [
        [
            'label' => $article->getLabel('a_date_creation'),
            'attribute' => 'a_date_creation',
            'format' => 'html',
            'filter' => DatePicker::widget([
                'model' => $article,
                'attribute' => 'a_date_creation',
                'language' => Yii::$app->language,
                'dateFormat' => 'php:d/m/Y',
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                    'showOtherMonths' => true,
                    'selectOtherMonths' => true,
                    'showButtonPanel' => true,
                ],
            ]),
            'value' => function($data) {
                $formatter = Yii::$app->formatter;//config par défaut dans web.php
                return $formatter->asDate($data->a_date_creation);
            },
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'label' => $article->getLabel('a_nom'),
            'attribute' => 'a_nom',
            'format' => 'raw',
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
            'contentOptions' => ['style' => 'text-align:right;width:11%;'],
        ],
        [
            'label' => $article->getLabel('a_quantite'),
            'attribute' => 'a_quantite',
            'value' => function($data) {
                $formatter = Yii::$app->formatter;//config par défaut dans web.php
                return $formatter->asInteger($data->a_quantite);
            },
            'contentOptions' => ['style' => 'text-align:right;width:5%;'],
        ],
        [
            'label' => $article->getLabel('a_image'),
            'attribute' => 'a_image',
            'enableSorting' => false,
            'filter' => '',
            'format' => 'html',
            'value' => function($data) {
                return Html::img('@web/uploads/article/' . $data->a_image);
            },
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'text-align:center;width:6%;'],
            'template' => '{view} {update} {delete}',
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'view') {
                    return Url::to(['article/show-view', 'a_id' => $model->a_id]);
                } elseif ($action === 'update') {
                    return Url::to(['article/show-form', 'a_id' => $model->a_id]);
                } else {
                    return Url::to(['article/' . $action, 'a_id' => $model->a_id]);
                }
            }
        ],
    ]
]);
