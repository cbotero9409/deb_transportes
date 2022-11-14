<?php

include_once 'orderController.php';
require "../vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$excel = new excelClass();
if (isset($_GET['report'])) {
    if ($_GET['report'] === 'gral') {
        $excel->excel();
    } else if ($_GET['report'] === 'time') {
        $excel->excel2();
    }
}

class excelClass {

    public function excel() {
        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

        date_default_timezone_set('Europe/London');

        $controller = new orderController();
        $status = $controller->monthInfoStatus();
//        echo '<pre>';
//        print_r($status);
        $municipality = $controller->monthInfoMunicipality();
//        print_r($municipality);
//        die;
        $start_date = $controller->formatDate($status['Fecha Inicial']);
        $end_date = $controller->formatDate($status['Fecha Final']);

        $objPHPExcel = new Spreadsheet();
        $objWorksheet = $objPHPExcel->getActiveSheet();

        //LOGO        
        $objWorksheet->getRowDimension('1')->setRowHeight(60);
        $objWorksheet->getStyle('A1:Z1')
                ->getFill()
                ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0030a4');
        $objDrawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $objDrawing->setName('Logo ');
        $objDrawing->setDescription('Logo');
        $objDrawing->setPath("../assets/img/logo_excel.PNG");
        $objDrawing->setResizeProportional(true);
        $objDrawing->setWidth(200);
        $objDrawing->setCoordinates('A1');
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

        $objWorksheet->getColumnDimension('J')->setWidth(36);
        $objWorksheet->getStyle('J1')
                ->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objWorksheet->setCellValue('J1', "SURAMERICANA\n$start_date a\n$end_date");
        $objWorksheet->getStyle('J1')->getAlignment()->setWrapText(true);
        $objWorksheet->getStyle("J1")->getFont()->setSize(16)->getColor()->setRGB('FFFFFF');

        //DATA
        $start_date_1 = date('d/m/y', strtotime(array_shift($status)));
        $end_date_1 = date('d/m/y', strtotime(array_shift($status)));

        $i = 0;
        $j = 0;
        $l = 7;
        $k = $l + 1;

        //Data Status
        foreach ($status as $key => $value) {
            $status_array[$i] = array($key => $value);
            $i++;
        }

        $objWorksheet->getColumnDimension('B')->setWidth(20);
        $objWorksheet->getColumnDimension('C')->setWidth(10);
        $objWorksheet->getStyle('B:C')
                ->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objWorksheet->getStyle('B:C')
                ->getAlignment()->setVertical(PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objWorksheet->getRowDimension($l)->setRowHeight(20);
        $objWorksheet->getStyle("B$l")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
        $objWorksheet->getStyle("C$l")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
        $objWorksheet->setCellValue("B$l", "Estado");
        $objWorksheet->setCellValue("C$l", "Cantidad");
        $objWorksheet->getStyle("B$l:C$l")
                ->getFill()
                ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0030a4');
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM
                )
            )
        );

        $objPHPExcel->getActiveSheet()->getStyle("B$l:C$l")->applyFromArray($styleArray);
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );

        $dataSeriesLabels1 = array();
        $dataSeriesValues1 = array();

        foreach ($status_array as $s_a) {
            $objWorksheet->fromArray(
                    array(
                        array_keys($status_array[$j])
                    ), NULL, 'B' . $k
            );
            $objWorksheet->fromArray(
                    array(
                        array_values($status_array[$j])
                    ), NULL, 'C' . $k
            );
            $objWorksheet->getRowDimension($k)->setRowHeight(20);
            $objPHPExcel->getActiveSheet()->getStyle("B$k:C$k")->applyFromArray($styleArray);
            $objWorksheet->getStyle("B$k:C$k")
                    ->getFill()
                    ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('d9dcf0');

            $dataSeriesLabels1[$j] = new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$' . $k, NULL, 1);
            $dataSeriesValues1[$j] = new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$' . $k, NULL, 4);

            $j++;
            $k++;
        }

        $xAxisTickValues1 = array(
            new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$' . $l, NULL, 4), //	Q1 to Q4
        );

//	Build the dataseries
        $series1 = new PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART, // plotType
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_STANDARD, // plotGrouping
                range(0, count($dataSeriesValues1) - 1), // plotOrder
                $dataSeriesLabels1, // plotLabel
                $xAxisTickValues1, // plotCategory
                $dataSeriesValues1        // plotValues
        );
//	Set additional dataseries parameters
//		Make it a vertical column rather than a horizontal bar graph
        $series1->setPlotDirection(PhpOffice\PhpSpreadsheet\Chart\DataSeries::DIRECTION_COL);

//	Set the series in the plot area
        $plotArea1 = new PhpOffice\PhpSpreadsheet\Chart\PlotArea(NULL, array($series1));
//	Set the chart legend
        $legend1 = new PhpOffice\PhpSpreadsheet\Chart\Legend(PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_RIGHT, NULL, false);

        $title1 = new PhpOffice\PhpSpreadsheet\Chart\Title("Ordenes por Estado");
//        $yAxisLabel1 = new PHPExcel_Chart_Title('Cantidad');
//	Create the chart
        $chart1 = new PhpOffice\PhpSpreadsheet\Chart\Chart(
                'chart1', // name
                $title1, // title
                $legend1, // legend
                $plotArea1, // plotArea
                true, // plotVisibleOnly
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::EMPTY_AS_GAP, // displayBlanksAs
                NULL, // xAxisLabel
//                $yAxisLabel1 // yAxisLabel
        );

//	Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('E4');
        $chart1->setBottomRightPosition('P20');

        //Data Municipality
        $i = 0;
        $j = 0;
        $l = 22;
        $k = $l + 1;

        foreach ($municipality as $key => $value) {
            $municipality_array[$i] = array($key => $value);
            $i++;
        }

        $objWorksheet->getStyle("B$l")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
        $objWorksheet->getStyle("C$l")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
        $objWorksheet->setCellValue("B$l", "Municipio");
        $objWorksheet->setCellValue("C$l", "Cantidad");
        $objWorksheet->getStyle("B$l:C$l")
                ->getFill()
                ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0030a4');
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM
                )
            )
        );

        $objPHPExcel->getActiveSheet()->getStyle("B$l:C$l")->applyFromArray($styleArray);
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );

        $dataSeriesLabels2 = array();
        $dataSeriesValues2 = array();

        foreach ($municipality_array as $m_a) {
            $objWorksheet->fromArray(
                    array(
                        array_keys($municipality_array[$j])
                    ), NULL, 'B' . $k
            );
            $objWorksheet->fromArray(
                    array(
                        array_values($municipality_array[$j])
                    ), NULL, 'C' . $k
            );
            $objWorksheet->getRowDimension($k)->setRowHeight(20);
            $objPHPExcel->getActiveSheet()->getStyle("B$k:C$k")->applyFromArray($styleArray);
            $objWorksheet->getStyle("B$k:C$k")
                    ->getFill()
                    ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('d9dcf0');

            $dataSeriesLabels2[$j] = new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$' . $k, NULL, 1);
            $dataSeriesValues2[$j] = new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$' . $k, NULL, 4);

            $j++;
            $k++;
        }

        $xAxisTickValues2 = array(
            new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$' . $l, NULL, 4), //	Q1 to Q4
        );

//	Build the dataseries
        $series2 = new PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART, // plotType
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_STANDARD, // plotGrouping
                range(0, count($dataSeriesValues2) - 1), // plotOrder
                $dataSeriesLabels2, // plotLabel
                $xAxisTickValues2, // plotCategory
                $dataSeriesValues2        // plotValues
        );
//	Set additional dataseries parameters
//		Make it a vertical column rather than a horizontal bar graph
        $series2->setPlotDirection(PhpOffice\PhpSpreadsheet\Chart\DataSeries::DIRECTION_COL);

//	Set the series in the plot area
        $plotArea2 = new PhpOffice\PhpSpreadsheet\Chart\PlotArea(NULL, array($series2));
//	Set the chart legend
        $legend2 = new PhpOffice\PhpSpreadsheet\Chart\Legend(PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_RIGHT, NULL, false);

        $title2 = new PhpOffice\PhpSpreadsheet\Chart\Title("Ordenes por Municipio");
//        $yAxisLabel1 = new PHPExcel_Chart_Title('Cantidad');
//	Create the chart
        $chart2 = new PhpOffice\PhpSpreadsheet\Chart\Chart(
                'chart2', // name
                $title2, // title
                $legend2, // legend
                $plotArea2, // plotArea
                true, // plotVisibleOnly
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::EMPTY_AS_GAP, // displayBlanksAs
                NULL, // xAxisLabel
//                $yAxisLabel1 // yAxisLabel
        );

//	Set the position where the chart should appear in the worksheet
        $chart2->setTopLeftPosition('E24');
        $chart2->setBottomRightPosition('P40');
//
//	Add the chart to the worksheet
        $objWorksheet->addChart($chart1);
        $objWorksheet->addChart($chart2);

        // Save Excel 2007 file
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Informe Ordenes General ($start_date_1 - $end_date_1).xlsx");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->setIncludeCharts(TRUE);
        $objWriter->save('php://output');
    }

    public function excel2() {
        /** Error reporting */
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

        date_default_timezone_set('Europe/London');

        $controller = new orderController();
        $status = $controller->monthInfoStatus();
        $times = $controller->monthInfoTimes();
//        echo '<pre>';
//        print_r($times);
//        die;
        $averages = $times['average'];
        $max_times = $times['max'];
        $min_times = $times['min'];
        $start_date = $controller->formatDate($status['Fecha Inicial']);
        $end_date = $controller->formatDate($status['Fecha Final']);

        $objPHPExcel = new Spreadsheet();
        $objWorksheet = $objPHPExcel->getActiveSheet();

        //LOGO        
        $objWorksheet->getRowDimension('1')->setRowHeight(60);
        $objWorksheet->getStyle('A1:Z1')
                ->getFill()
                ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0030a4');
        $objDrawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $objDrawing->setName('Logo ');
        $objDrawing->setDescription('Logo');
        $objDrawing->setPath("../assets/img/logo_excel.PNG");
        $objDrawing->setResizeProportional(true);
        $objDrawing->setWidth(200);
        $objDrawing->setCoordinates('A1');
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

        $objWorksheet->getColumnDimension('J')->setWidth(36);
        $objWorksheet->getStyle('J1')
                ->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objWorksheet->setCellValue('J1', "SURAMERICANA\n$start_date a\n$end_date");
        $objWorksheet->getStyle('J1')->getAlignment()->setWrapText(true);
        $objWorksheet->getStyle("J1")->getFont()->setSize(16)->getColor()->setRGB('FFFFFF');

        //DATA
        $start_date_1 = date('d/m/y', strtotime(array_shift($status)));
        $end_date_1 = date('d/m/y', strtotime(array_shift($status)));

        $i = 0;
        $j = 0;
        $l = 7;
        $k = $l + 1;

        //Data Status
        foreach ($status as $key => $value) {
            $status_array[$i] = array($key => $value);
            $i++;
        }

        $objWorksheet->getColumnDimension('B')->setWidth(20);
        $objWorksheet->getColumnDimension('C')->setWidth(16);
        $objWorksheet->getStyle('B:C')
                ->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $objWorksheet->getStyle('B:C')
                ->getAlignment()->setVertical(PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $objWorksheet->getRowDimension($l)->setRowHeight(20);
        $objWorksheet->getStyle("B$l")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
        $objWorksheet->getStyle("C$l")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
        $objWorksheet->setCellValue("B$l", "Estado");
        $objWorksheet->setCellValue("C$l", "Cantidad");
        $objWorksheet->getStyle("B$l:C$l")
                ->getFill()
                ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0030a4');
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM
                )
            )
        );

        $objPHPExcel->getActiveSheet()->getStyle("B$l:C$l")->applyFromArray($styleArray);
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );

        $dataSeriesLabels1 = array();
        $dataSeriesValues1 = array();

        foreach ($status_array as $s_a) {
            $objWorksheet->fromArray(
                    array(
                        array_keys($status_array[$j])
                    ), NULL, 'B' . $k
            );
            $objWorksheet->fromArray(
                    array(
                        array_values($status_array[$j])
                    ), NULL, 'C' . $k
            );
            $objWorksheet->getRowDimension($k)->setRowHeight(20);
            $objPHPExcel->getActiveSheet()->getStyle("B$k:C$k")->applyFromArray($styleArray);
            $objWorksheet->getStyle("B$k:C$k")
                    ->getFill()
                    ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('d9dcf0');

            $dataSeriesLabels1[$j] = new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$' . $k, NULL, 1);
            $dataSeriesValues1[$j] = new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$' . $k, NULL, 4);

            $j++;
            $k++;
        }

        $xAxisTickValues1 = array(
            new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$' . $l, NULL, 4), //	Q1 to Q4
        );

//	Build the dataseries
        $series1 = new PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART, // plotType
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_STANDARD, // plotGrouping
                range(0, count($dataSeriesValues1) - 1), // plotOrder
                $dataSeriesLabels1, // plotLabel
                $xAxisTickValues1, // plotCategory
                $dataSeriesValues1        // plotValues
        );
//	Set additional dataseries parameters
//		Make it a vertical column rather than a horizontal bar graph
        $series1->setPlotDirection(PhpOffice\PhpSpreadsheet\Chart\DataSeries::DIRECTION_COL);

//	Set the series in the plot area
        $plotArea1 = new PhpOffice\PhpSpreadsheet\Chart\PlotArea(NULL, array($series1));
//	Set the chart legend
        $legend1 = new PhpOffice\PhpSpreadsheet\Chart\Legend(PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_RIGHT, NULL, false);

        $title1 = new PhpOffice\PhpSpreadsheet\Chart\Title("Ordenes por Estado");
//        $yAxisLabel1 = new PHPExcel_Chart_Title('Cantidad');
//	Create the chart
        $chart1 = new PhpOffice\PhpSpreadsheet\Chart\Chart(
                'chart1', // name
                $title1, // title
                $legend1, // legend
                $plotArea1, // plotArea
                true, // plotVisibleOnly
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::EMPTY_AS_GAP, // displayBlanksAs
                NULL, // xAxisLabel
//                $yAxisLabel1 // yAxisLabel
        );

//	Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('E4');
        $chart1->setBottomRightPosition('P20');

        //Data Averages
        $i = 0;
        $j = 0;
        $l = 29;
        $k = $l + 1;

        foreach ($averages as $key => $value) {
            $average_array[$i] = array($key => $value);
            $i++;
        }

        $objWorksheet->getStyle("B$l")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
        $objWorksheet->getStyle("C$l")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
        $objWorksheet->setCellValue("B$l", "Estado");
        $objWorksheet->setCellValue("C$l", "Promedio(días)");
        $objWorksheet->getStyle("B$l:C$l")
                ->getFill()
                ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0030a4');
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM
                )
            )
        );

        $objPHPExcel->getActiveSheet()->getStyle("B$l:C$l")->applyFromArray($styleArray);
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );

        $dataSeriesLabels2 = array();
        $dataSeriesValues2 = array();

        foreach ($average_array as $a_a) {
            $objWorksheet->fromArray(
                    array(
                        array_keys($average_array[$j])
                    ), NULL, 'B' . $k
            );
            $objWorksheet->fromArray(
                    array(
                        array_values($average_array[$j])
                    ), NULL, 'C' . $k
            );
            $objWorksheet->getRowDimension($k)->setRowHeight(20);
            $objPHPExcel->getActiveSheet()->getStyle("B$k:C$k")->applyFromArray($styleArray);
            $objWorksheet->getStyle("B$k:C$k")
                    ->getFill()
                    ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('d9dcf0');

            $dataSeriesLabels2[$j] = new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$' . $k, NULL, 1);
            $dataSeriesValues2[$j] = new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$' . $k, NULL, 4);

            $j++;
            $k++;
        }

        $xAxisTickValues2 = array(
            new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$' . $l, NULL, 4), //	Q1 to Q4
        );

//	Build the dataseries
        $series2 = new PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART, // plotType
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_STANDARD, // plotGrouping
                range(0, count($dataSeriesValues2) - 1), // plotOrder
                $dataSeriesLabels2, // plotLabel
                $xAxisTickValues2, // plotCategory
                $dataSeriesValues2        // plotValues
        );
//	Set additional dataseries parameters
//		Make it a vertical column rather than a horizontal bar graph
        $series2->setPlotDirection(PhpOffice\PhpSpreadsheet\Chart\DataSeries::DIRECTION_COL);

//	Set the series in the plot area
        $plotArea2 = new PhpOffice\PhpSpreadsheet\Chart\PlotArea(NULL, array($series2));
//	Set the chart legend
        $legend2 = new PhpOffice\PhpSpreadsheet\Chart\Legend(PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_RIGHT, NULL, false);

        $title2 = new PhpOffice\PhpSpreadsheet\Chart\Title("Promedio Días Estados");
//        $yAxisLabel1 = new PHPExcel_Chart_Title('Cantidad');
//	Create the chart
        $chart2 = new PhpOffice\PhpSpreadsheet\Chart\Chart(
                'chart2', // name
                $title2, // title
                $legend2, // legend
                $plotArea2, // plotArea
                true, // plotVisibleOnly
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::EMPTY_AS_GAP, // displayBlanksAs
                NULL, // xAxisLabel
//                $yAxisLabel1 // yAxisLabel
        );

//	Set the position where the chart should appear in the worksheet
        $pos_x = 24;
        $pos_y = 40;
        $chart2->setTopLeftPosition("E$pos_x");
        $chart2->setBottomRightPosition("P$pos_y");
//
        //Data MAX
        $i = 0;
        $j = 0;
        $l += 20;
        $k = $l + 1;

        foreach ($max_times as $key => $value) {
            $max_array[$i] = array($key => $value);
            $i++;
        }

        $objWorksheet->getStyle("B$l")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
        $objWorksheet->getStyle("C$l")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
        $objWorksheet->setCellValue("B$l", "Estado");
        $objWorksheet->setCellValue("C$l", "Máximo(días)");
        $objWorksheet->getStyle("B$l:C$l")
                ->getFill()
                ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0030a4');
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM
                )
            )
        );

        $objPHPExcel->getActiveSheet()->getStyle("B$l:C$l")->applyFromArray($styleArray);
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );

        $dataSeriesLabels3 = array();
        $dataSeriesValues3 = array();

        foreach ($max_array as $max_a) {
            $objWorksheet->fromArray(
                    array(
                        array_keys($max_array[$j])
                    ), NULL, 'B' . $k
            );
            $objWorksheet->fromArray(
                    array(
                        array_values($max_array[$j])
                    ), NULL, 'C' . $k
            );
            $objWorksheet->getRowDimension($k)->setRowHeight(20);
            $objPHPExcel->getActiveSheet()->getStyle("B$k:C$k")->applyFromArray($styleArray);
            $objWorksheet->getStyle("B$k:C$k")
                    ->getFill()
                    ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('d9dcf0');

            $dataSeriesLabels3[$j] = new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$' . $k, NULL, 1);
            $dataSeriesValues3[$j] = new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$' . $k, NULL, 4);

            $j++;
            $k++;
        }

        $xAxisTickValues3 = array(
            new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$' . $l, NULL, 4), //	Q1 to Q4
        );

//	Build the dataseries
        $series3 = new PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART, // plotType
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_STANDARD, // plotGrouping
                range(0, count($dataSeriesValues3) - 1), // plotOrder
                $dataSeriesLabels3, // plotLabel
                $xAxisTickValues3, // plotCategory
                $dataSeriesValues3        // plotValues
        );
//	Set additional dataseries parameters
//		Make it a vertical column rather than a horizontal bar graph
        $series3->setPlotDirection(PhpOffice\PhpSpreadsheet\Chart\DataSeries::DIRECTION_COL);

//	Set the series in the plot area
        $plotArea3 = new PhpOffice\PhpSpreadsheet\Chart\PlotArea(NULL, array($series3));
//	Set the chart legend
        $legend3 = new PhpOffice\PhpSpreadsheet\Chart\Legend(PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_RIGHT, NULL, false);

        $title3 = new PhpOffice\PhpSpreadsheet\Chart\Title("Máximo Días Estados");
//        $yAxisLabel1 = new PHPExcel_Chart_Title('Cantidad');
//	Create the chart
        $chart3 = new PhpOffice\PhpSpreadsheet\Chart\Chart(
                'chart3', // name
                $title3, // title
                $legend3, // legend
                $plotArea3, // plotArea
                true, // plotVisibleOnly
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::EMPTY_AS_GAP, // displayBlanksAs
                NULL, // xAxisLabel
//                $yAxisLabel1 // yAxisLabel
        );

//	Set the position where the chart should appear in the worksheet
        $pos_x += 20;
        $pos_y += 20;
        $chart3->setTopLeftPosition("E$pos_x");
        $chart3->setBottomRightPosition("P$pos_y");

        //Data min
        $i = 0;
        $j = 0;
        $l += 20;
        $k = $l + 1;

        foreach ($min_times as $key => $value) {
            $min_array[$i] = array($key => $value);
            $i++;
        }

        $objWorksheet->getStyle("B$l")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
        $objWorksheet->getStyle("C$l")->getFont()->setBold(true)->setSize(12)->getColor()->setRGB('FFFFFF');
        $objWorksheet->setCellValue("B$l", "Estado");
        $objWorksheet->setCellValue("C$l", "Mínimo(días)");
        $objWorksheet->getStyle("B$l:C$l")
                ->getFill()
                ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('0030a4');
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM
                )
            )
        );

        $objPHPExcel->getActiveSheet()->getStyle("B$l:C$l")->applyFromArray($styleArray);
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );

        $dataSeriesLabels4 = array();
        $dataSeriesValues4 = array();

        foreach ($min_array as $min_a) {
            $objWorksheet->fromArray(
                    array(
                        array_keys($min_array[$j])
                    ), NULL, 'B' . $k
            );
            $objWorksheet->fromArray(
                    array(
                        array_values($min_array[$j])
                    ), NULL, 'C' . $k
            );
            $objWorksheet->getRowDimension($k)->setRowHeight(20);
            $objPHPExcel->getActiveSheet()->getStyle("B$k:C$k")->applyFromArray($styleArray);
            $objWorksheet->getStyle("B$k:C$k")
                    ->getFill()
                    ->setFillType(PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('d9dcf0');

            $dataSeriesLabels4[$j] = new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$' . $k, NULL, 1);
            $dataSeriesValues4[$j] = new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$' . $k, NULL, 4);

            $j++;
            $k++;
        }

        $xAxisTickValues4 = array(
            new PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues(PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$' . $l, NULL, 4), //	Q1 to Q4
        );

//	Build the dataseries
        $series4 = new PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART, // plotType
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_STANDARD, // plotGrouping
                range(0, count($dataSeriesValues4) - 1), // plotOrder
                $dataSeriesLabels4, // plotLabel
                $xAxisTickValues4, // plotCategory
                $dataSeriesValues4        // plotValues
        );
//	Set additional dataseries parameters
//		Make it a vertical column rather than a horizontal bar graph
        $series4->setPlotDirection(PhpOffice\PhpSpreadsheet\Chart\DataSeries::DIRECTION_COL);

//	Set the series in the plot area
        $plotArea4 = new PhpOffice\PhpSpreadsheet\Chart\PlotArea(NULL, array($series4));
//	Set the chart legend
        $legend4 = new PhpOffice\PhpSpreadsheet\Chart\Legend(PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_RIGHT, NULL, false);

        $title4 = new PhpOffice\PhpSpreadsheet\Chart\Title("Mínimo Días Estados");
//        $yAxisLabel1 = new PHPExcel_Chart_Title('Cantidad');
//	Create the chart
        $chart4 = new PhpOffice\PhpSpreadsheet\Chart\Chart(
                'chart4', // name
                $title4, // title
                $legend4, // legend
                $plotArea4, // plotArea
                true, // plotVisibleOnly
                PhpOffice\PhpSpreadsheet\Chart\DataSeries::EMPTY_AS_GAP, // displayBlanksAs
                NULL, // xAxisLabel
//                $yAxisLabel1 // yAxisLabel
        );

//	Set the position where the chart should appear in the worksheet
        $pos_x += 20;
        $pos_y += 20;
        $chart4->setTopLeftPosition("E$pos_x");
        $chart4->setBottomRightPosition("P$pos_y");

//	Add the chart to the worksheet
        $objWorksheet->addChart($chart1);
        $objWorksheet->addChart($chart2);
        $objWorksheet->addChart($chart3);
        $objWorksheet->addChart($chart4);

        // Save Excel 2007 file
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=Informe Ordenes Tiempos ($start_date_1 - $end_date_1).xlsx");
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
        $objWriter->setIncludeCharts(TRUE);
        $objWriter->save('php://output');
    }

}