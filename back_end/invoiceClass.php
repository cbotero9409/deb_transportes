<?php

include_once 'orderController.php';
//require '../vendor/PHPExcel-1.8/Classes/PHPExcel.php';


class invoiceClass {
    function read($values) {
        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "SELECT * FROM `invoices` WHERE $values ORDER BY date DESC";
        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result');</script>";
            return 'error';
        }
        return $result;
    }
    
    function readForinvoice() {
        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "SELECT * FROM `orders` O LEFT JOIN lots L ON O.fk_lot = L.id WHERE l.invoice = 0 AND fk_lot > 0 ORDER BY l.date; ";
        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result');</script>";
            return 'error';
}
        return $result;
    }
}

//$excel = new invoiceClass();
//$excel->excelInvoice();
//
//class invoiceClass {
//
//    public function excelInvoice() {
//        /** Error reporting */
//        error_reporting(E_ALL);
//        ini_set('display_errors', TRUE);
//        ini_set('display_startup_errors', TRUE);
//        date_default_timezone_set('Europe/London');
//
//        define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
//
//        date_default_timezone_set('Europe/London');
//
//        $controller = new orderController();
//        $status = $controller->monthInfoStatus();
//        $municipality = $controller->monthInfoMunicipality();
//        $start_date = $controller->formatDate($status['Fecha Inicial']);
//        $end_date = $controller->formatDate($status['Fecha Final']);        
//
//        $objPHPExcel = new PHPExcel();
//        $objWorksheet = $objPHPExcel->getActiveSheet();
//
//        //LOGO        
//        $objWorksheet->getRowDimension('1')->setRowHeight(60);
//        $objWorksheet->getStyle('A1:D1')
//                ->getFill()
//                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
//                ->getStartColor()
//                ->setARGB('0030a4');
//        $objDrawing = new PHPExcel_Worksheet_Drawing();
//        $objDrawing->setName('Logo ');
//        $objDrawing->setDescription('Logo ');
//        $objDrawing->setPath("../assets/img/logoExcel.png");
//        $objDrawing->setResizeProportional(true);
//        $objDrawing->setWidth(200);
//        $objDrawing->setCoordinates('A1');
//        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
//
//        $objWorksheet->getColumnDimension('J')->setWidth(36);
//        $objWorksheet->getStyle('J1')
//                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//        $objWorksheet->setCellValue('J1', "SURAMERICANA\n$start_date a\n$end_date");
//        $objWorksheet->getStyle('J1')->getAlignment()->setWrapText(true);
//        $objWorksheet->getStyle("J1")->getFont()->setSize(16)->getColor()->setRGB('FFFFFF');
//
//        //DATA
//        $start_date_1 = date('d/m/y', strtotime(array_shift($status)));
//        $end_date_1 = date('d/m/y', strtotime(array_shift($status)));
//     
//        $i = 0;
//        $j = 0;
//        $k = 9;
//
//        //Data Status
//        foreach ($status as $key => $value) {
//            $status_array[$i] = array($key => $value);
//            $i++;
//        }
//
//        $objWorksheet->getColumnDimension('B')->setWidth(20);
//        $objWorksheet->getColumnDimension('C')->setWidth(10);
//        $objWorksheet->getStyle('B:C')
//                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//        $objWorksheet->getStyle('B:C')
//                ->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
//        $objWorksheet->getRowDimension(8)->setRowHeight(20);
//        $objWorksheet->getStyle("B8")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF'); //negrilla
//        $objWorksheet->getStyle("C8")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
//        $objWorksheet->setCellValue('B8', "Estado");//rotula
//        $objWorksheet->setCellValue('C8', "Cantidad");
//        $objWorksheet->getStyle('B8:C8')
//                ->getFill()
//                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
//                ->getStartColor()
//                ->setARGB('0030a4');
//        $styleArray = array(
//            'borders' => array(
//                'allborders' => array(
//                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM
//                )
//            )
//        );
//
//        $objPHPExcel->getActiveSheet()->getStyle('B8:C8')->applyFromArray($styleArray);
//        $styleArray = array(
//            'borders' => array(
//                'allborders' => array(
//                    'style' => PHPExcel_Style_Border::BORDER_THIN
//                )
//            )
//        );
//
//        $dataSeriesLabels1 = array();
//        $dataSeriesValues1 = array();
//
//        foreach ($status_array as $s_a) {
//            $objWorksheet->fromArray(
//                    array(
//                        array_keys($status_array[$j])
//                    ), NULL, 'B' . $k
//            );
//            $objWorksheet->fromArray(
//                    array(
//                        array_values($status_array[$j])
//                    ), NULL, 'C' . $k
//            );
//            $objWorksheet->getRowDimension($k)->setRowHeight(20);
//            $objPHPExcel->getActiveSheet()->getStyle("B$k:C$k")->applyFromArray($styleArray);
//            $objWorksheet->getStyle("B$k:C$k")
//                    ->getFill()
//                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
//                    ->getStartColor()
//                    ->setRGB('d9dcf0');
//
//            $dataSeriesLabels1[$j] = new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$B$' . $k, NULL, 1);
//            $dataSeriesValues1[$j] = new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$C$' . $k, NULL, 4);
//
//            $j++;
//            $k++;
//        }
//
//        $xAxisTickValues1 = array(
//            new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$B$8', NULL, 4), //	Q1 to Q4
//        );
//
////	Build the dataseries
//        $series1 = new PHPExcel_Chart_DataSeries(
//                PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
//                PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
//                range(0, count($dataSeriesValues1) - 1), // plotOrder
//                $dataSeriesLabels1, // plotLabel
//                $xAxisTickValues1, // plotCategory
//                $dataSeriesValues1        // plotValues
//        );
////	Set additional dataseries parameters
////		Make it a vertical column rather than a horizontal bar graph
//        $series1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
//
////	Set the series in the plot area
//        $plotArea1 = new PHPExcel_Chart_PlotArea(NULL, array($series1));
////	Set the chart legend
//        $legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
//
//        $title1 = new PHPExcel_Chart_Title("Ordenes por Estado");
////        $yAxisLabel1 = new PHPExcel_Chart_Title('Cantidad');
////	Create the chart
//        $chart1 = new PHPExcel_Chart(
//                'chart1', // name
//                $title1, // title
//                $legend1, // legend
//                $plotArea1, // plotArea
//                true, // plotVisibleOnly
//                0, // displayBlanksAs
//                NULL // xAxisLabel
////                $yAxisLabel1 // yAxisLabel
//        );
//
////	Set the position where the chart should appear in the worksheet
//        $chart1->setTopLeftPosition('E4');
//        $chart1->setBottomRightPosition('P20');
//
//        //Data Municipality
//        $i = 0;
//        $j = 0;
//        $k = 23;
//
//        foreach ($municipality as $key => $value) {
//            $municipality_array[$i] = array($key => $value);
//            $i++;
//        }
//
//        $objWorksheet->getStyle("B22")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
//        $objWorksheet->getStyle("C22")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
//        $objWorksheet->setCellValue('B22', "Municipio");
//        $objWorksheet->setCellValue('C22', "Cantidad");
//        $objWorksheet->getStyle('B22:C22')
//                ->getFill()
//                ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
//                ->getStartColor()
//                ->setARGB('0030a4');
//        $styleArray = array(
//            'borders' => array(
//                'allborders' => array(
//                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM
//                )
//            )
//        );
//
//        $objPHPExcel->getActiveSheet()->getStyle('B22:C22')->applyFromArray($styleArray);
//        $styleArray = array(
//            'borders' => array(
//                'allborders' => array(
//                    'style' => PHPExcel_Style_Border::BORDER_THIN
//                )
//            )
//        );
//
//        $dataSeriesLabels2 = array();
//        $dataSeriesValues2 = array();
//
//        foreach ($municipality_array as $m_a) {
//            $objWorksheet->fromArray(
//                    array(
//                        array_keys($municipality_array[$j])
//                    ), NULL, 'B' . $k
//            );
//            $objWorksheet->fromArray(
//                    array(
//                        array_values($municipality_array[$j])
//                    ), NULL, 'C' . $k
//            );
//            $objWorksheet->getRowDimension($k)->setRowHeight(20);
//            $objPHPExcel->getActiveSheet()->getStyle("B$k:C$k")->applyFromArray($styleArray);
//            $objWorksheet->getStyle("B$k:C$k")
//                    ->getFill()
//                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
//                    ->getStartColor()
//                    ->setRGB('d9dcf0');
//
//            $dataSeriesLabels2[$j] = new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$B$' . $k, NULL, 1);
//            $dataSeriesValues2[$j] = new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$C$' . $k, NULL, 4);
//
//            $j++;
//            $k++;
//        }
//
//        $xAxisTickValues2 = array(
//            new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$B$22', NULL, 4), //	Q1 to Q4
//        );
//
////	Build the dataseries
//        $series2 = new PHPExcel_Chart_DataSeries(
//                PHPExcel_Chart_DataSeries::TYPE_BARCHART, // plotType
//                PHPExcel_Chart_DataSeries::GROUPING_STANDARD, // plotGrouping
//                range(0, count($dataSeriesValues2) - 1), // plotOrder
//                $dataSeriesLabels2, // plotLabel
//                $xAxisTickValues2, // plotCategory
//                $dataSeriesValues2        // plotValues
//        );
////	Set additional dataseries parameters
////		Make it a vertical column rather than a horizontal bar graph
//        $series2->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
//
////	Set the series in the plot area
//        $plotArea2 = new PHPExcel_Chart_PlotArea(NULL, array($series2));
////	Set the chart legend
//        $legend2 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
//
//        $title2 = new PHPExcel_Chart_Title("Ordenes por Municipio");
////        $yAxisLabel1 = new PHPExcel_Chart_Title('Cantidad');
////	Create the chart
//        $chart2 = new PHPExcel_Chart(
//                'chart2', // name
//                $title2, // title
//                $legend2, // legend
//                $plotArea2, // plotArea
//                true, // plotVisibleOnly
//                0, // displayBlanksAs
//                NULL // xAxisLabel
////                $yAxisLabel1 // yAxisLabel
//        );
//
////	Set the position where the chart should appear in the worksheet
//        $chart2->setTopLeftPosition('E24');
//        $chart2->setBottomRightPosition('P40');
////
////	Add the chart to the worksheet
//        $objWorksheet->addChart($chart1);
//        $objWorksheet->addChart($chart2);
//
//        // Save Excel 2007 file
//        // Redirect output to a client’s web browser (Excel2007)
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header("Content-Disposition: attachment;filename=Factura ($start_date_1 - $end_date_1).xlsx");
//        header('Cache-Control: max-age=0');
//// If you're serving to IE 9, then the following may be needed
//        header('Cache-Control: max-age=1');
//
//// If you're serving to IE over SSL, then the following may be needed
//        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
//        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
//        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
//        header('Pragma: public'); // HTTP/1.0
//
//        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//        $objWriter->setIncludeCharts(TRUE);
//        $objWriter->save('php://output');
//    }
//
//}