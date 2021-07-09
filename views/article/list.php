<?php
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\helpers\Html;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'emptyCell' => '-',
    'filterModel' => $model,
    'tableOptions' => ['class' => ' table table-bordered table-hover'],
    'columns' => [
        [
            'label' => $model->getLabel('a_date_creation'),
            'attribute' => 'a_date_creation',
            'format' => 'html',
            'filter' => DatePicker::widget([
                'model' => $model,
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
            'contentOptions' => ['style' => 'text-align:center;width:8%;'],
        ],
        [
            'label' => $model->getLabel('a_nom'),
            'attribute' => 'a_nom',
            'format' => 'raw',
            'value' => function($data) {
                return '<b>' . $data->a_nom . '</b>';
            },
        ],
        [
            'label' => $model->getLabel('a_description'),
            'attribute' => 'a_description',
            'format' => 'ntext',
        ],
        [
            'label' => $model->getLabel('a_prix'),
            'attribute' => 'a_prix',
            'contentOptions' => ['style' => 'text-align:right;width:10%;'],
        ],
        [
            'label' => $model->getLabel('a_quantite'),
            'attribute' => 'a_quantite',
            'contentOptions' => ['style' => 'text-align:right;width:5%;'],
        ],
        [
            'label' => $model->getLabel('a_image'),
            'attribute' => 'a_image',
            'enableSorting' => false,
            'filter' => '',
            'format' => 'html',
            'value' => function($data) {
                return Html::img('@web/uploads/article/' . $data->a_image);
            },
        ],
    ]
]);
