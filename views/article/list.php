<?php
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;

echo Html::a('Ajouter', ['article/show-form'], ['class' => 'btn btn-primary']);
echo '</br></br>';

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'emptyCell' => '',
    'filterModel' => $article,
    'tableOptions' => ['class' => ' table table-bordered table-hover'],
    'columns' => [
        [
            'label' => $article->getLabel('a_date_creation'),
            'attribute' => 'date_creation',
            'format' => 'html',
            'filter' => DatePicker::widget([
                'model' => $article,
                'attribute' => 'date_creation',
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
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'label' => $article->getLabel('a_nom'),
            'attribute' => 'a_nom',
            'format' => 'raw',
            'value' => function($data) {
                return '<b>' . $data->a_nom . '</b>';
            },
        ],
        [
            'label' => $article->getLabel('a_description'),
            'attribute' => 'a_description',
            'format' => 'ntext',
        ],
        [
            'label' => $article->getLabel('a_prix'),
            'attribute' => 'prix',
            'contentOptions' => ['style' => 'text-align:right;width:10%;'],
        ],
        [
            'label' => $article->getLabel('a_quantite'),
            'attribute' => 'quantite',
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
