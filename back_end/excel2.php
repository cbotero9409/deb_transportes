<?php

include_once 'orderController.php';
require "../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$excel = new excelClass2();
$excel->excel();

class excelClass2 {

    public function excel() {
        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

        date_default_timezone_set('Europe/London');

        $controller = new orderController();
        $date = $controller->formatDate(date('Y-m-d'));
////        $main_array = array('Números' => [1, 2, 3, 4], 'Descripción' => ['abcaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'deeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeef', 'ghi', 'jkl'], 'Valor' => [100000, 200000, 300000, 400000]);
        $main_array = $controller->invoiceExcel();
//        print "<pre>";
//        print_r($main_array);
//        print "</pre>";
//        die();

        for ($m = 0; $m < count($main_array['Numbers']); $m++) {
            $munic = $main_array['Municipality'][$m];
            $name = $main_array['Contact'][$m];
            $desc = $main_array['Description'][$m];
            $description[] = "Ir a $munic, 'Laureles', señor(a) $name, recoger $desc";
            $values[] = 86488;
        }

//        print "<pre>";
//        print_r($description);
//        print "</pre>";
//        die();

        $objPHPExcel = new Spreadsheet();
        $objWorksheet = $objPHPExcel->getActiveSheet();

        //LOGO        
        $objWorksheet->getRowDimension('1')->setRowHeight(60);
        $objWorksheet->getStyle('A1:C1')
                ->getFill()
                ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FFFFFF');

        $objWorksheet->getColumnDimension('A')->setWidth(20);
        $objWorksheet->getColumnDimension('B')->setWidth(50);
        $objWorksheet->getColumnDimension('C')->setWidth(20);

        
        $objWorksheet->getStyle('A1')->getAlignment()->setVertical(PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objWorksheet->setCellValue('A1', " Anexo a\n Factura\n  #373");
        $objWorksheet->getStyle('A1')->getAlignment()->setWrapText(true);
        $objWorksheet->getStyle("A1")->getFont()->setSize(13)->getColor()->setRGB('000000');

        $objDrawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
        $objWorksheet->getStyle('B1')
                ->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_FILL);
        $objDrawing->setName('Logo ');
        $objDrawing->setDescription('Logo');
        $objDrawing->setPath("../assets/img/logoexcel.png");
        $objDrawing->setResizeProportional(true);
        $objDrawing->setWidth(300);
        $objDrawing->setCoordinates('B1');
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

        $objPHPExcel->getActiveSheet()->mergeCells('A6:C6');
        $objPHPExcel->getActiveSheet()->mergeCells('A7:C7');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:C3');
        $objPHPExcel->getActiveSheet()->mergeCells('A4:C4');
        $objPHPExcel->getActiveSheet()->mergeCells('A5:C5');
        $objWorksheet->getRowDimension('3')->setRowHeight(20);
        $objWorksheet->getRowDimension('4')->setRowHeight(20);
        $objWorksheet->getRowDimension('5')->setRowHeight(20);
        $objWorksheet->getRowDimension('6')->setRowHeight(20);
        $objWorksheet->setCellValue('A3', "decheverrib@gmail.com");
        $objWorksheet->setCellValue('A4', "Medellín $date");
        $objWorksheet->setCellValue('A5', "Transporte de Salvamentos en el Valle del Aburra");
        $fecha1 = $main_array['Fecha Inicial'];
        $fecha2 = $main_array['Fecha Final'];
        $objWorksheet->setCellValue('A6', "Salvamentos entre $fecha1 a $fecha2");

        //DATA

        $i = 0;
        $k = 9;
        $j = 11;

        //Data Status


        $objWorksheet->getStyle('A:C')
                ->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objWorksheet->getStyle('A1')
                ->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $objWorksheet->getStyle('A:C')
                ->getAlignment()->setVertical(PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );

        $styleArrayBorders = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        while ($i < count($main_array['Numbers'])) {
            $number = $main_array['Numbers'][$i];
            $objWorksheet->setCellValueExplicit(
                    "A$k",
                    $number,
                    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
            );
//            $objWorksheet->fromArray(
//                    array(
//                        $main_array['Numbers'][$i]
//                    ), NULL, 'A' . $k
//            );
            $objWorksheet->fromArray(
                    array(
                        $description[$i]
                    ), NULL, 'B' . $k
            );
            $objWorksheet->fromArray(
                    array(
                        $values[$i]
                    ), NULL, 'C' . $k
            );

            $objWorksheet->getRowDimension("$k")->setRowHeight(-1);
            $j++;
            $k++;
            $i++;
        }


        $objWorksheet->getStyle("A9:C$k")->getAlignment()->setWrapText(true);

        $objWorksheet->getStyle("A2:C$j")
                ->getFill()
                ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setRGB('FFFFFF');
        $objWorksheet->getStyle("A2:C$j")->getFont()->setSize(12)->getColor()->setRGB('000000');

        
        $objWorksheet->getStyle("A8")->getFont()->setBold(true)->setSize(13)->getColor()->setRGB('000000');
        $objWorksheet->getStyle("B8")->getFont()->setBold(true)->setSize(13)->getColor()->setRGB('000000');
        $objWorksheet->getStyle("C8")->getFont()->setBold(true)->setSize(13)->getColor()->setRGB('000000');
        $objWorksheet->setCellValue('A8', "Número");
        $objWorksheet->setCellValue('B8', "Descripción");
        $objWorksheet->setCellValue('C8', "Valor");

        $objWorksheet->getRowDimension($k)->setRowHeight(-1);
        $objPHPExcel->getActiveSheet()->getStyle("A2:C$j")->applyFromArray($styleArray);
        $objWorksheet->getStyle("A1:C$j")->applyFromArray($styleArrayBorders);

        $l = $k - 1;
        $k++;
        $objWorksheet->getStyle("C9:C$k")->getNumberFormat()->setFormatCode("_(\"$\"* #,##0_);_(\"$\"* \(#,##0\);_(\"$\"* \"-\"??_);_(@_)");
        $objWorksheet->getRowDimension("$k")->setRowHeight(20);
        $objWorksheet->setCellValue("B$k", "Total: ");
        $objWorksheet->getStyle("B$k")
                ->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $objWorksheet->getStyle("B$k")->getFont()->setBold(true);

//        $sum = array_sum($main_array['Valor']);
//        $objWorksheet->setCellValue("C$k", "$sum");
        $objWorksheet->setCellValue("C$k", "=sum(C9:C$l)");

        $k++;
        $objWorksheet->getRowDimension("$k")->setRowHeight(70);
        $objPHPExcel->getActiveSheet()->mergeCells("A$k:C$k");
        $objWorksheet->getStyle("A$k")->getAlignment()->setWrapText(true);
        $objWorksheet->setCellValue("A$k", "  Dario Echeverri B.\n  C.C. 70.098.621 Medellín");
        $objWorksheet->getStyle("A$k")->getFont()->setBold(true)->setItalic(true);
        $objWorksheet->getStyle("A$k")
                ->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $objWorksheet->getStyle("A$k")
                ->getAlignment()->setVertical(PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM);

        // Save Excel 2007 file
        // Redirect output to a client’s web browser (Excel2007)
        $start_date = $main_array['Start Date'];
        $end_date = $main_array['End Date'];
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Factura ($start_date - $end_date).xlsx");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->save('php://output');
    }

}
