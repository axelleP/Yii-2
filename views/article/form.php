<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\Url;

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
    'options' => ['class' => 'form-control']
]);
echo $form->field($article, 'a_nom')->textInput(['maxlength' => 50]);
echo $form->field($article, 'a_description')->textarea(['maxlength' => 250, 'rows' => 6, 'cols' => 50]);
echo $form->field($article, 'a_prix')->textInput(['type' => 'number', 'step' => '0.01']);
echo $form->field($article, 'a_quantite')->textInput(['type' => 'number']);

/* gestion de l'image */
if (empty($article->a_image)) {
    $cssDownloadDisplay = 'display:block;';
    $cssImageDisplay = 'display:none;';
} else {
    $cssDownloadDisplay = 'display:none;';
    $cssImageDisplay = 'display:block;';
}

//input file
echo '<span id="downloadDisplay" style="' . $cssDownloadDisplay . '">';
echo $form->field($article, 'a_image')->fileInput(['id' => 'a_image']);
echo '</span>';

//affichage de l'image et du bouton de suppression
echo '<span id="imageDisplay" class="form-group" style="' . $cssImageDisplay . '">';
echo Html::label($article->getLabel('a_image'));
echo Html::hiddenInput('a_image2', $article->a_image, ['id' => 'a_image2']);
echo '</br>';
echo Html::img('@web/uploads/article/' . $article->a_image);
echo '</br></br>';
echo Html::a("Supprimer l'image", [''], ['class' => 'btn btn-danger', 'onclick' => 'js:confirmDeleteImage(' . $article->a_id . ');return false;']);
echo '</br></br></br>';
echo '</span>';
/* fin gestion de l'image */
?>

<div class="form-group">
    <?php
    echo Html::a('Annuler', ['article/index'], ['class' => 'btn btn-secondary']);
    if (empty($article->a_id)) {
        echo Html::submitButton('Ajouter', ['class' => 'btn btn-primary']);
    } else {
        echo Html::submitButton('Modifier', ['class' => 'btn btn-primary']);
    }
    ?>
</div>
<?php
ActiveForm::end() ;
?>

<script type="text/javascript">
    function confirmDeleteImage(idArticle) {
        toDelete = window.confirm("Confirmez-vous la suppression de l'image ?");

        if (toDelete) {
            deleteImage(idArticle);
        }
    }

    function deleteImage(idArticle) {
        $.ajax({
            url: '<?= Url::toRoute(['article/delete-image']); ?>',
            data: {"idArticle": idArticle, "_token": "{{ csrf_token() }}"},
            type: 'POST',//type d'envoi
            dataType: 'json',//type des données récupérées
            success : function(code_html, statut) {//code_html contient le HTML renvoyé
                $("#downloadDisplay").css("display","block");
                $("#imageDisplay").css("display","none");
                $("#a_image2").val('');
            }
        });
    }
</script>