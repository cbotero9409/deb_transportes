<?php
include_once 'layout/general.php';
include_once '../back_end/userController.php';
include_once '../back_end/orderController.php';
include_once '../back_end/charts.php';

$layout = new general();
$controller = new orderController();
$orders = $controller->orderInfo();
$charts = new chartsClass();
$status_array = $charts->statusChart();
$munic_array = $charts->municipalityChart();
?>

<!DOCTYPE html>
<html>
    <?= $layout->head('Información General'); ?>

    <body>
        <div id='wrapper'>
            <div class='overlay'></div>
            <!-- Sidebar -->
            <?= $layout->navbar('info'); ?>    
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id='page-content-wrapper'>
                <button type='button' class='hamburger animated fadeInLeft is-closed' data-toggle='offcanvas'>
                    <span class='hamb-top'></span>
                    <span class='hamb-middle'></span>
                    <span class='hamb-bottom'></span>
                </button>

                <?= $layout->navTitle("Admin", 1, "Información General"); ?>

                <!-- Page Content -->
                <?php // print_r($munic);  ?>
                <div class="row mt-4">
                    <div class="col-lg-3 col-6">
                        <div class="info info_1 py-3 my-2">
                            <span>Pendientes (Totales)<br><?= $orders['Pendientes'] . ' (' . $orders['Total_Pendientes'] . ')'; ?></span><br>
                            <i class=" info_icons icon_1 fas fa-clipboard-list"></i>
                        </div>                        
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="info info_2 py-3 my-2">
                            <span>Recogidas<br><?= $orders['Recogidas']; ?></span><br>
                            <i class=" info_icons icon_2 fas fa-clipboard-check"></i>
                        </div>                        
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="info info_3 py-3 my-2">
                            <span>Ofertadas<br><?= $orders['Ofertadas']; ?></span><br>
                            <i class=" info_icons icon_3 fas fa-tags"></i>
                        </div>                        
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="info info_4 py-3 my-2">
                            <span>Desechadas<br><?= $orders['Desechadas']; ?></span><br>
                            <i class=" info_icons icon_4 fas fa-trash-alt"></i>
                        </div>                        
                    </div>                    
                </div>
                <div class="row mt-5">
                    <div class="col-12 text-center h4 font-weight-bold">
                        <span>Estadística de Ordenes por Estado Año <?php //echo date("Y"); ?>2021</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <canvas id="myChart" width="100%"></canvas>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12 text-center h4 font-weight-bold">
                        <span>Estadística de Ordenes por Municipio Año <?php //echo date("Y"); ?>2021</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <canvas id="myChart1" width="100%"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0-rc.1/chartjs-plugin-datalabels.min.js" integrity="sha512-+UYTD5L/bU1sgAfWA0ELK5RlQ811q8wZIocqI7+K0Lhh8yVdIoAMEs96wJAIbgFvzynPm36ZCXtkydxu1cs27w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    var ctx = document.getElementById("myChart").getContext("2d");

    var data = {
        labels: <?php echo json_encode($status_array['Meses'], JSON_NUMERIC_CHECK); ?>,
        datasets: [{
                label: "Pendientes",
                backgroundColor: "#DD2A2A",
                data: <?php echo json_encode($status_array['Pendientes'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Recogidas",
                backgroundColor: "#228A33",
                data: <?php echo json_encode($status_array['Recogidas'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Desechadas",
                backgroundColor: "#2926B1",
                data: <?php echo json_encode($status_array['Desechadas'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Ofertadas",
                backgroundColor: "#C39B25",
                data: <?php echo json_encode($status_array['Ofertadas'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Totales",
                backgroundColor: "#9E15AA",
                data: <?php echo json_encode($status_array['Totales'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Otra Ciudad",
                backgroundColor: "#fd7e14",
                data: <?php echo json_encode($status_array['Otra Ciudad'], JSON_NUMERIC_CHECK); ?>
            }]
    };

    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            plugins: {
                datalabels: {
                    align: 'end',
                    anchor: 'end',
                    color: "black",
                    font: {
                        size: 10
                    }
                },
                legend: {
                    position: "bottom"
                }
            },
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                        ticks: {
                            min: 0,
                        }
                    }]
            },
            layout: {
                padding: {
                    top: 20
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    var ctx1 = document.getElementById("myChart1").getContext("2d");

    var h = [];
    let day = new Date();
    var month = day.getMonth();
    for (let i = 0; i < 12; i++) {
        if (month === i || month === i + 1) {
            h[i] = false;
        } else {
            h[i] = true;
        }
    }

    var data1 = {
        labels: <?php echo json_encode($munic_array['Municipios'], JSON_NUMERIC_CHECK); ?>,
        datasets: [{
                label: "Enero",
                backgroundColor: "#DD2A2A",
                hidden: h[0],
                data: <?php echo json_encode($munic_array['Enero'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Febrero",
                backgroundColor: "#228A33",
                hidden: h[1],
                data: <?php echo json_encode($munic_array['Febrero'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Marzo",
                backgroundColor: "#2926B1",
                hidden: h[2],
                data: <?php echo json_encode($munic_array['Marzo'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Abril",
                backgroundColor: "#C39B25",
                hidden: h[3],
                data: <?php echo json_encode($munic_array['Abril'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Mayo",
                backgroundColor: "#9E15AA",
                hidden: h[4],
                data: <?php echo json_encode($munic_array['Mayo'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Junio",
                backgroundColor: "#fd7e14",
                hidden: h[5],
                data: <?php echo json_encode($munic_array['Junio'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Julio",
                backgroundColor: "#a47960",
                hidden: h[6],
                data: <?php echo json_encode($munic_array['Julio'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Agosto",
                backgroundColor: "#80858b",
                hidden: h[7],
                data: <?php echo json_encode($munic_array['Agosto'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Septiembre",
                backgroundColor: "#e050c9",
                hidden: h[8],
                data: <?php echo json_encode($munic_array['Septiembre'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Octubre",
                backgroundColor: "#ff4242",
                hidden: h[9],
                data: <?php echo json_encode($munic_array['Octubre'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Noviembre",
                backgroundColor: "#42f6ff",
                hidden: h[10],
                data: <?php echo json_encode($munic_array['Noviembre'], JSON_NUMERIC_CHECK); ?>
            }, {
                label: "Diciembre",
                backgroundColor: "#ffd942",
                hidden: h[11],
                data: <?php echo json_encode($munic_array['Diciembre'], JSON_NUMERIC_CHECK); ?>
            }]
    };

    var myBarChart1 = new Chart(ctx1, {
        type: 'bar',
        data: data1,
        options: {
            plugins: {
                datalabels: {
                    align: 'end',
                    anchor: 'end',
                    color: "black",
                    font: {
                        size: 10
                    }
                },
                legend: {
                    position: "bottom"
                }
            },
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                        ticks: {
                            min: 0,
                        }
                    }]
            },
            layout: {
                padding: {
                    top: 20
                }
            }
        },
        plugins: [ChartDataLabels]
    });

</script>
