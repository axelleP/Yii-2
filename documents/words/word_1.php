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

        $section->addTextBreak(3);

        //texte
        $p = "<p>";
        $p .= "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent a ligula in massa aliquam feugiat. Fusce ultrices, nibh a pharetra lobortis, lectus odio rhoncus ipsum, vitae viverra lacus tellus laoreet justo.";
        $p .= "<br/><br/>";
        $p .= "Curabitur dolor dolor, gravida quis consequat in, lobortis vel odio. Nulla facilisi. Integer nec posuere nibh, sed pharetra diam.";
        $p .= "</p>";
        HtmlPhpWord::addHtml($section, $p);

        $section->addTextBreak(2);

        //tableau
        $table = $section->addTable();

        foreach ($this->tabParams['articles'] as $article) {
            $table->addRow();
            
            $table->addCell(1750)->addText($formatter->asDate($article->a_date_creation));
            $table->addCell(1750)->addText($article->a_nom);
            $table->addCell(1750)->addText($article->a_description);
            $table->addCell(1750)->addText($formatter->asCurrency($article->a_prix));
            $table->addCell(1750)->addText($formatter->asInteger($article->a_quantite));
            $table->addCell(1750)->addImage(Yii::getAlias('@webroot') . '/uploads/article/' . $article->a_image);
        }
    }

    public function download() {
        ob_clean();
        $this->save('Articles.docx', 'Word2007', true);
        exit;
    }
}