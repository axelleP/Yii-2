<?php
namespace app\documents\excels;

use Yii;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Xlsx;

class Excel_1 extends Spreadsheet
{
    public $tabParams = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function init() {
        $this->getProperties()
        ->setTitle('Liste des articles')
        ->setSubject('Liste des articles')
        ->setDescription('Export de la liste des articles')
        ->setCreator('Axelle Palermo')
        ->setLastModifiedBy('Axelle Palermo');

        $this->getDefaultStyle()->getFont()->setName('helvetica')->setSize(11);
    }

    public function create() {
        $formatter = Yii::$app->formatter;//config par défaut dans web.php

        $sheet = $this->getActiveSheet();//onglet courant
        $sheet->setTitle('ARTS') ;//titre de l'onglet
        $startCol = 'A';
        $endCol = 'E';

        /* images d'exemple */
        //rque : je crois qu'on ne peut pas mettre d'image à l'intérieur d'une cellule
        $image1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $image1->setName('Excel');
        $image1->setDescription('Excel');
        $image1->setPath(Yii::getAlias('@webroot') . '/img//excel.png');
        $image1->setCoordinates("B3");
        $image1->setHeight(74);
        $image1->setOffsetX(1);
        $image1->setWorksheet($sheet);

        $image2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $image2->setName('Excel');
        $image2->setDescription('Excel');
        $image2->setPath(Yii::getAlias('@webroot') . '/img//excel.png');
        $image2->setCoordinates("D3");
        $image2->setHeight(74);
        $image2->setOffsetX(1);
        $image2->setWorksheet($sheet);
        /* images d'exemple */

        //titre
        $coordTitre = "{$startCol}1:{$endCol}2";
        $this->getActiveSheet()->mergeCells($coordTitre);//fusion de cellules
        $sheet->getStyle($coordTitre)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($coordTitre)->getAlignment()->setVertical('center');
        $sheet->getStyle($coordTitre)->getFont()->setSize(16);
        $sheet->getStyle($coordTitre)->getFont()->setBold(true);
        $sheet->setCellValue("{$startCol}1", 'Liste des articles');

        //texte
        $coordTexte = "{$startCol}4:{$endCol}6";
        $sheet->mergeCells($coordTexte);//fusion de cellules
        $sheet->getStyle($coordTexte)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($coordTexte)->getAlignment()->setVertical('center');
        $sheet->getStyle($coordTexte)->getAlignment()->setWrapText(true);//permet les sauts de ligne
        $texte = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
        $texte .= "\n\n";//rque : mettre "" et pas '' pour que \n soit interprété
        $texte .= 'Curabitur dolor dolor, gravida quis consequat in, lobortis vel odio.';
        $sheet->setCellValue("{$startCol}4", $texte);

        /* tableau */
        //thead
        $row = 8;
        $sheet->setAutoFilter("{$startCol}{$row}:E{$row}");//met des filtres
        $styleTableHead = [
            'font' => [
                'color' => ['rgb' => 'FFFFFF']
                , 'bold' => true
                , 'size' => 11
            ]
            , 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2361A3']]
        ];
        $sheet->getRowDimension($row)->setRowHeight(20);//augmente la hauteur de la ligne

        $sheet->setCellValue("A{$row}", 'Date création');
        $sheet->setCellValue("B{$row}", 'Nom');
        $sheet->setCellValue("C{$row}", 'Description');
        $sheet->setCellValue("D{$row}", 'Prix');
        $sheet->setCellValue("E{$row}", 'Quantité');

        $sheet->getStyle("{$startCol}{$row}:{$endCol}{$row}")->applyFromArray($styleTableHead);

        //tbody
        $row++;
        $styleTableBody = [
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CAE2F9']]
        ];
        $firstRowTable = $row;
        foreach ($this->tabParams['articles'] as $article) {
            $sheet->setCellValue("A{$row}", $formatter->asDate($article->a_date_creation));
            $sheet->setCellValue("B{$row}", $article->a_nom);
            $sheet->setCellValue("C{$row}", nl2br($article->a_description));
            $sheet->setCellValue("D{$row}", $formatter->asCurrency($article->a_prix));
            $sheet->setCellValue("E{$row}", $formatter->asInteger($article->a_quantite));

            $row++;
        }
        $lastRowTable = $row;
        $sheet->getStyle("{$startCol}{$firstRowTable}:{$endCol}{$lastRowTable}")->applyFromArray($styleTableBody);
        /* fin tableau */

        //redimensionne les colonnes
        $this->autoSizeColumn($sheet, $startCol, ['C']);
        $sheet->getColumnDimension('C')->setWidth(70);

        //exemple pour chq lignes
        //foreach($sheet->getRowDimensions() as $row) {}
    }

    //redimensionne la largeur des colonnes automatiquement
    public function autoSizeColumn($sheet, $startCol, $except = []) {
        foreach(range($startCol, $sheet->getHighestDataColumn()) as $column) {//pour chq colonnes
            if (!in_array($column, $except)) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
        }
    }

    public function download() {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Articles.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($this);
        $writer->save('php://output');
        exit;
    }
}