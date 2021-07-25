<?php
namespace app\documents\pdfs;

use Yii;
use yii\helpers\Html;
use TCPDF;

class Pdf_1 extends TCPDF
{
    public $tabParams = [];

    public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false)
    {
        parent::__construct();

        $this->setPageFormat('A4', 'L');//landscape
    }

    public function init() {
        $this->SetCreator(PDF_CREATOR);//nom du générateur pdf
        $this->SetAuthor('Axelle Palermo');//auteur
        $this->SetTitle('Liste des articles');//titre dans le document
        $this->SetSubject('Liste des articles');//sujet dont traite le pdf
        $this->SetKeywords('TCPDF, PDF, example, test, guide');//mots clés

        /* Fonts */
        //police par défaut
        $this->SetDefaultMonospacedFont('helvetica');
        //permet ou non la modification des sous-ensembles de polices par défaut
        $this->setFontSubsetting(true);

        //marges du pdf
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        //saut de page automatique
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        //facteur d'échelle de l'image
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

        /*
        //set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
                require_once(dirname(__FILE__).'/lang/eng.php');
                $this->setLanguageArray($l);
        }
        */
    }

    public function Header() {}

    public function Footer() {
        $this->SetFont('helvetica', '', 9);
        $this->SetY(-10);

        parent::Footer();
    }

    public function create() {
        $formatter = Yii::$app->formatter;//config par défaut dans web.php

        $this->AddPage();//ajout d'une page

        /* écriture */
        $this->SetFont('helvetica', 'B', 25);
        $this->writeHTML('Liste des articles', true, false, false, false, 'C');

        $this->Ln(10);
        $this->SetFont('helvetica', '', 12);
        $this->setCellPaddings(2, 2, 2, 2);//l, t, r, b

        $style = '<style>';
        $style .= 'table {';
        $style .= 'border: solid black 1px;';
        $style .= 'padding: 5px;';
        $style .= '}';
        $style .= '</style>';

        $textAlign_center = 'text-align:center;';
        $textAlign_right = 'text-align:right;';
        $widthDescription = '280px';
        $widthDate = '100px';
        $widthPrixQuantite = '120px';

        $table = '<table class="table" border="1">';
        $table .= '<thead>';
        $table .= '<tr>';
        $table .= '<th width="' . $widthDate . '">Date création</th>';
        $table .= '<th>Nom</th>';
        $table .= '<th width="' . $widthDescription . '">Description</th>';
        $table .= '<th width="' . $widthPrixQuantite . '">Prix</th>';
        $table .= '<th width="' . $widthPrixQuantite . '">Quantité</th>';
        $table .= '<th>Image</th>';
        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        foreach ($this->tabParams['articles'] as $article) {
            $table .= '<tr nobr="true">';//nobr pour que les lignes se soient pas coupées entre 2 pages
            $table .= '<td width="' . $widthDate . '" style="' . $textAlign_center . '">' . $formatter->asDate($article->a_date_creation) . '</td>';
            $table .= "<td>$article->a_nom</td>";
            $table .= '<td width="' . $widthDescription . '">' . nl2br($article->a_description) . '</td>';
            $table .= '<td width="' . $widthPrixQuantite . '" style="' . $textAlign_right . '">' . $formatter->asCurrency($article->a_prix) . '</td>';
            $table .= '<td width="' . $widthPrixQuantite . '" style="' . $textAlign_right . '">' . $formatter->asInteger($article->a_quantite) . '</td>';
            $table .= '<td>' . Html::img('@web/uploads/article/' . $article->a_image) . '</td>';
            $table .= '</tr>';
        }
        $table .= '</tbody>';
        $table .= '</table>';

        $this->writeHTMLCell(0, 0, '', '', $style.$table, 0, 1, 0, true, '', true);
    }

    public function download() {
        $this->Output('Articles.pdf', 'D');//D pour download
    }
}