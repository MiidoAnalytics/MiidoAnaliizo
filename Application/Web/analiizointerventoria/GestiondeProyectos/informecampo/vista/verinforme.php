<!DOCTYPE html>
<html lang="en">

    <head>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <title>Ver informe</title>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <script src="../../../core/lib/amchart/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/serial.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/themes/light.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/plugins/export/export.min.js" type="text/javascript"></script>
        <script src="https://www.amcharts.com/lib/3/gauge.js"></script>
        <link href="../../../core/lib/amchart/amcharts/plugins/export/export.css" media="all" rel="stylesheet" type="text/css" />
    </head>

    <?php
    @session_start();

    if (empty($_SESSION['user'])) {
        ?>

        <div class="alert alert-danger">
            El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
        </div>
        <?php
    }
    ?>

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_01.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
        <?php //include("../../../Administrador/menu/controlador/menu.php"); ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">RECOPILACIÓN DE INFORMACIÓN EN CAMPO </h1>
                    </div>                    
                </div>
                <div class="panel-body">
                    <div class="row"> 
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <input type="hidden" value="<?php echo $feinise2; ?>" id="fechainiprisema">
                                <div class="panel-heading">
                                    Porcentaje Programado Vs Porcentaje Ejecutado - Gráfico
                                </div>                                
                                <div class="panel-body">
                                    <INPUT type="hidden" id="porEje" value="<?php echo $proporcion2; ?>">
                                    <div id="linechartporProvseje" style="width: 100%; height: 50ex;">
                                    </div>
                                </div>                                
                            </div>                            
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Personal de Obra - Gráfico
                                </div>                                
                                <div class="panel-body">
                                    <div id="chartPersonalObra" style="width: 100%; height: 50ex;">
                                    </div>
                                </div>                                
                            </div>                            
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Personal de Interventoría - Gráfico
                                </div>                                
                                <div class="panel-body">
                                    <div id="chartPersonalInterventoria" style="width: 100%; height: 50ex;">
                                    </div>
                                </div>                                
                            </div>                            
                        </div>
                    </div>
                </div>           
            </div>   
        </div>
            <?php include("../../../sitemedia/html/scriptpie.php"); ?>         
    </body>
    
    <script>
        //Gráfico del porcentaje Ejecutado vs Programado
        var porEjeTot = document.getElementById('porEje').value;
        console.log(porEjeTot);
        var maximun = 0;
        if (porEjeTot > 0 && porEjeTot <= 10) {
            maximo = 10;
        }else{
            if (porEjeTot > 10 && porEjeTot <= 30) {
                maximo = 30;
            }else{
                if (porEjeTot > 30 && porEjeTot <= 50) {
                    maximo = 50;
                }else{
                    if (porEjeTot > 50 && porEjeTot <= 70) {
                        maximo = 70;
                    }else{maximo = 100};
                };
            };
        };
        var chartData = generateChartData();
        console.log(chartData);
        var chart = AmCharts.makeChart("linechartporProvseje", {
            "type": "serial",
            "theme": "light",
            "legend": {
                "useGraphSettings": true
            },
            "dataProvider": chartData,
            "valueAxes": [{
                "integersOnly": false,
                "maximum": maximo,
                "minimum": 0,
                "reversed": false,
                "axisAlpha": 0,
                "dashLength": 5,
                "gridCount": 10,
                "position": "left",
                "title": "Porcentaje"
            }],
            "startDuration": 0.5,
            "graphs": [{
                "balloonText": "Porcentaje Programado [[category]]: [[value]]",
                "bullet": "round",
                "title": "Programado",
                "valueField": "Programado",
                "fillAlphas": 0,
                //"type": "column",
                "lineThickness": 5
            }, {
                "balloonText": "Porcentaje Ejecutado [[category]]: [[value]]",
                "bullet": "square",
                "title": "Ejecutado",
                "valueField": "Ejecutado",
                "fillAlphas": 0,
                //"type": "column",
                "lineThickness": 5
            }],
            "chartCursor": {
                "cursorAlpha": 0,
                "zoomable": false
            },
            "dataDateFormat": "YYYY-MM-DD",
            "categoryField": "date",
            "categoryAxis": {
                    "dateFormats": [{
                        "period": "DD",
                        "format": "DD"
                    }, {
                        "period": "WW",
                        "format": "MMM DD"
                    }, {
                        "period": "MM",
                        "format": "MMM"
                    }, {
                        "period": "YYYY",
                        "format": "YYYY"
                    }],
                "gridPosition": "start",
                "axisAlpha": 0,
                "fillAlpha": 0.05,
                "fillColor": "#000000",
                "gridAlpha": 0,
                "position": "bottom"
            },
            "export": {
                "enabled": true,
                "position": "top-right"
             }
        });

        // crear datos
        function generateChartData() {
            var chartData1 = <?= json_encode($AcumuladoGrafico, JSON_NUMERIC_CHECK) ?>;
            var chartData = [];
            var aux = document.getElementById('fechainiprisema').value;
            //console.log(aux);
            var Progra = 0;
            var Ejecu = 0;
            chartData.push({
                date: aux,
                Programado: Progra,
                Ejecutado: Ejecu
            });
            for(i=0;i<chartData1.length;i++){
                aux = chartData1[i]['date'];
                ms = Date.parse(aux);
                fecha = new Date(ms);
                Progra = chartData1[i]['Programado'];
                Ejecu = chartData1[i]['Ejecutado'];

                chartData.push({
                    date: aux,
                    Programado: Progra,
                    Ejecutado: Ejecu
                });

            }

            return chartData;
        }

        //Gráfica de personal de obra
        var dataPersonalTotal = <?= json_encode($DataPersonalObra, JSON_NUMERIC_CHECK) ?>;
        var chart = AmCharts.makeChart("chartPersonalObra", {
            "theme": "light",
            "type": "serial",
            "dataProvider": dataPersonalTotal,
            "valueAxes": [{
                "unit": "",
                "position": "left",
                "title": "Total personal de obra",
            }],
            "startDuration": 1,
            "graphs": [{
                "balloonText": "Total [[category]] : <b>[[value]]</b>",
                "fillAlphas": 0.9,
                "lineAlpha": 0.2,
                "title": "2004",
                "type": "column",
                "valueField": "total"
            }],
            "plotAreaFillAlphas": 0.1,
            "categoryField": "personal",
            "categoryAxis": {
                "gridPosition": "start"
            },
            "export": {
                "enabled": true
             }
        });

        //Gráfica de personal de interventoria
        var DataPersonalInterventoria = <?= json_encode($DataPersonalInterventoria, JSON_NUMERIC_CHECK) ?>;
        console.log(DataPersonalInterventoria);
        var chart = AmCharts.makeChart("chartPersonalInterventoria", {
            "theme": "light",
            "type": "serial",
            "dataProvider": DataPersonalInterventoria,
            "valueAxes": [{
                "unit": "",
                "position": "left",
                "title": "Total personal de obra",
            }],
            "startDuration": 1,
            "graphs": [{
                "balloonText": "Total [[category]] : <b>[[value]]</b>",
                "fillAlphas": 0.9,
                "lineAlpha": 0.2,
                "title": "2004",
                "type": "column",
                "valueField": "total"
            }],
            "plotAreaFillAlphas": 0.1,
            "categoryField": "personal",
            "categoryAxis": {
                "gridPosition": "start"
            },
            "export": {
                "enabled": true
             }
        });


    </script>
</html>