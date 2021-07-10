<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

$form = ActiveForm::begin([
    'id' => 'article-form',
    'options' => [
        'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data'//pour l'image
    ],
    //'errorSummaryCssClass' => 'help-block',//pour changer le CSS du message d'erreur global
]);

echo $form->errorSummary($article);

echo $form->field($article, 'a_date_creation')
->widget(DatePicker::classname(), [
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
])->label($article->getLabel('a_date_creation'));
echo $form->field($article, 'a_nom')->textInput(['maxlength' => 50])->label($article->getLabel('a_nom'));
echo $form->field($article, 'a_description')->textarea(['maxlength' => 250, 'rows' => 6, 'cols' => 50])->label($article->getLabel('a_description'));
echo $form->field($article, 'a_prix')->textInput(['type' => 'number'])->label($article->getLabel('a_prix'));
echo $form->field($article, 'a_quantite')->textInput(['type' => 'number'])->label($article->getLabel('a_quantite'));

if (empty($article->a_image)) {
    echo $form->field($article, 'a_image')->fileInput()->label($article->getLabel('a_image'));
} else {
    echo Html::label($article->getLabel('a_image'));
    echo '</br>';
    echo Html::img('@web/uploads/article/' . $article->a_image);
    echo '</br></br>';
    echo Html::a("Supprimer l'image", [''], ['class' => 'btn btn-danger', 'onclick' => 'js:alert(11);false;']);
    echo '</br></br></br>';
}
?>
<div class="form-group">
    <?php
    echo Html::a('Annuler', ['article/index', 'a_id' => $article->a_id], ['class' => 'btn btn-secondary']);
    if (empty($article->a_id)) {
        echo Html::submitButton('Ajouter', ['class' => 'btn btn-primary']);
    } else {
        echo Html::submitButton('Modifier', ['class' => 'btn btn-primary']);
    }
    ?>
</div>
<?php
ActiveForm::end() ;