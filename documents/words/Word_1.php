<?php
namespace app\documents\words;

use Yii;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html as HtmlPhpWord;

class Word_1 extends PhpWord
{
    public $tabParams = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function init() {
        $this->setDefaultFontName('helvetica');
        $this->setDefaultFontSize(11);
    }

    public function create() {
        $formatter = Yii::$app->formatter;//config par défaut dans web.php

        $section = $this->addSection(['orientation' => 'landscape']);

        //titre
        $this->addFontStyle('fTitre', ['bold' => true, 'size' => 20]);
        $this->addParagraphStyle('pTitre', ['align' => 'center']);
        $section->addText('Liste des articles', 'fTitre', 'pTitre');

        $section->addTextBreak(2);

        //texte
        $p = "<p>";
        $p .= "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent a ligula in massa aliquam feugiat. Fusce ultrices, nibh a pharetra lobortis, lectus odio rhoncus ipsum, vitae viverra lacus tellus laoreet justo.";
        $p .= "<br/><br/>";
        $p .= "Curabitur dolor dolor, gravida quis consequat in, lobortis vel odio. Nulla facilisi. Integer nec posuere nibh, sed pharetra diam.";
        $p .= "</p>";
        HtmlPhpWord::addHtml($section, $p);

        $section->addTextBreak(1);

        //tableau
        $this->addTableStyle('articles', [
            'borderSize' => 2,
            'borderColor' => 'black;',
            'cellMarginTop'=> 50,
            'cellMarginLeft'=> 50,
            'cellMarginRight'=> 50,
            'cellMarginBottom'=> 50,
        ]);
        $table = $section->addTable('articles');

        $table->addRow();

        //rque : les cellules ou on ne définit pas de width se dimensionnent automatiquement
        $table->addCell(1450)->addText('Date création', [], ['align' => 'center']);
        $table->addCell()->addText('Nom', [], ['align' => 'center']);
        $table->addCell()->addText('Description', [], ['align' => 'center']);
        $table->addCell(1000)->addText('Prix', [], ['align' => 'center']);
        $table->addCell()->addText('Quantité', [], ['align' => 'center']);
        $table->addCell()->addText('Image', [], ['align' => 'center']);

        foreach ($this->tabParams['articles'] as $article) {
            $table->addRow();

            $table->addCell()->addText($formatter->asDate($article->a_date_creation), [], ['align' => 'center']);
            $table->addCell()->addText($article->a_nom);
            $table->addCell()->addText($article->a_description);
            $table->addCell()->addText($formatter->asCurrency($article->a_prix), [], ['align' => 'right']);
            $table->addCell()->addText($formatter->asInteger($article->a_quantite), [], ['align' => 'right']);
            $table->addCell()->addImage(Yii::getAlias('@webroot') . '/uploads/article/' . $article->a_image);
        }
    }

    public function download() {
        ob_clean();
        $this->save('Articles.docx', 'Word2007', true);
        exit;
    }
}