<?php

include_once 'orderController.php';
include_once 'municipalityClass.php';

class chartsClass {

    function statusChart() {

        $controller = new orderController();
        $mensual_orders = $controller->yearOrdersStatus();
        
        $pendientes = array();
        $recogidas = array();
        $desechadas = array();
        $ofertadas = array();
        $otra_ciudad = array();
        $totales = array();
        $i = 0;
        $months_spanish = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        
        foreach ($mensual_orders as $key => $value) {
            $pendientes[$i] = $value['Pendientes'];
            $recogidas[$i] = $value['Recogidas'];
            $desechadas[$i] = $value['Desechadas'];
            $ofertadas[$i] = $value['Ofertadas'];
            $otra_ciudad[$i] = $value['Otra_Ciudad'];
            $totales[$i] = $value['Totales'];
            $month[$i] = $months_spanish[$i];
            $i++;
        }
        
        $main_array = ["Meses" => $month, "Pendientes" => $pendientes, "Recogidas" => $recogidas, "Desechadas" => $desechadas, "Ofertadas" => $ofertadas, "Otra Ciudad" => $otra_ciudad, "Totales" => $totales];
        return $main_array;
    }
    
    function municipalityChart() {

        $controller = new orderController();
        $mensual_orders = $controller->yearOrdersMunicipality();
        
        $model_municipality = new municipalityClass();
        $all_municipality = $model_municipality->read("1");
        foreach ($all_municipality as $municipality) {
            $munic[] = $municipality['municipality'];
        }
        
        $january = array();
        $february = array();
        $march = array();
        $april = array();
        $may = array();
        $june = array();
        $july = array();
        $august = array();
        $september = array();
        $octuber = array();
        $november = array();
        $december = array();
        $i = 0;
                
        foreach ($mensual_orders as $key => $value) {
            $january[$i] = $value['Enero'];
            $february[$i] = $value['Febrero'];
            $march[$i] = $value['Marzo'];
            $april[$i] = $value['Abril'];
            $may[$i] = $value['Mayo'];
            $june[$i] = $value['Junio'];
            $july[$i] = $value['Julio'];
            $august[$i] = $value['Agosto'];
            $september[$i] = $value['Septiembre'];
            $october[$i] = $value['Octubre'];
            $november[$i] = $value['Noviembre'];
            $december[$i] = $value['Diciembre'];
            $i++;
        }
        
        $main_array = ["Municipios" => $munic, "Enero" => $january, "Febrero" => $february, "Marzo" => $march, "Abril" => $april, "Mayo" => $may, "Junio" => $june, "Julio" => $july, "Agosto" => $august, "Septiembre" => $september, "Octubre" => $october, "Noviembre" => $november, "Diciembre" => $december];
        return $main_array;
    }

}
