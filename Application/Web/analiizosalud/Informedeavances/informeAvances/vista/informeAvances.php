<!DOCTYPE html>
<html lang="en">

    <head>

        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>      
        <title>Miido - Analiizo</title>
        <script src="../../../core/lib/amchart/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/serial.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/pie.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/themes/light.js" type="text/javascript"></script>

        <script type="text/javascript">
            window.onload = function () {
                document.getElementById('proyecto').focus();
            }
            function encPro(inputString) {
                $.post("../../../Administrador/asignacionencuestas/controlador/encuestaproyecto.php", {
                    proyecto:document.getElementById('proyecto').value 
                }, function (data) {
                    $('#encuesta').html(data);
                });
            } 

            function mostrarEncuestas() {
                proyecto = document.getElementById('proyecto').value;
                encuesta = document.getElementById('encuesta').value;
                var encuestaName= $('#encuesta option:selected').text();
                if (proyecto == '')
                {
                    alert('Por favor seleccione un proyecto para continuar.');
                    document.getElementById('proyecto').focus();
                }else if(encuesta == ''){
                    alert('Por favor seleccione una encuesta para continuar.');
                    document.getElementById('encuesta').focus();
                }
                else
                {
                    window.location = ("informeAvances.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName);
                }
            }
        </script>
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
    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Informe de Avances </h1>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="proyecto" class="col-lg-2 control-label">Proyecto: </label>
                                        <select name='proyecto' id='proyecto' class='form-control' required onchange="encPro();">
                                            <option value="" selected="selected">SELECCIONE</option> 
                                            <?php foreach ($proyectos as $item): ?>
                                                <option value="<?php echo $item['intidproyecto']; ?>"><?php echo $item['nombre']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="encuesta" class="col-lg-2 control-label">Encuesta: </label>
                                        <select name='encuesta' id='encuesta' class='form-control' required>
                                             <option value="" selected="selected">SELECCIONE</option>
                                        </select> 
                                    </div>
                                </div> 
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success" onclick="mostrarEncuestas()">Aceptar</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="divEncSel" style="display: none;">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            Encuesta Seleccionada
                                        </div>                               
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo $namePoll; ?></th>                                                                                              
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
            
                        </div>
                    </div>
                </div>
                <div class="row" id="divGraTiempo"> 
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Informe encuestadores en el tiempo - Gráfico
                            </div>                                
                            <div class="panel-body">
                                <div id="linechartEncuestadorTiempoDinamico" style="width: 100%; height: 60ex;"></div>
                            </div>                                
                        </div>                            
                    </div>
                </div>
                <div class="row" id="divInfEnc">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Informe de Encuestadores
                            </div>                            
                            <div class="panel-body">
                                <div id="bar-chart" style="width: 100%; height: 500px;"></div>
                            </div>                           
                        </div>                        
                    </div>
                </div>

                <div class="row" id="divTableInfEnc" style="display: none;">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Informe de Encuestadores por Municipio
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="cssToolTip">Departamento
                                                        <span>Departamento encuestado</span>
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="cssToolTip">Municipio
                                                        <span>Nombre municipio en el que realizo las encuestas</span>
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="cssToolTip">Encuestador
                                                        <span>Usuario del encuestador</span>
                                                    </div>
                                                </th>
                                                <th>
                                                    <div class="cssToolTip">Número encuestados
                                                        <span>Num. Encuestados por encuestador</span>
                                                    </div>
                                                </th>                                            
                                                <th>
                                                    <div class="cssToolTip">Porcentaje
                                                        <span>Porcentaje de Encuestados por municipio</span>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (empty($_SESSION['user'])) {
                                                ?>
                                            <div class="alert alert-danger">
                                                El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                            </div>
                                            <?php
                                        } else {
                                            foreach ($TablaInformeEncuestadoresPorMunicipio as $item):
                                                ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo $item['nombredepartamento']; ?></td>
                                                    <td><?php echo $item['nombremunicipio']; ?></td>
                                                    <td><?php echo $item['username']; ?></td>
                                                    <td><?php echo $item['totalencuestados']; ?></td>
                                                    <td><?php
                                                        $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                        echo $num;
                                                        ?></td>                                                        
                                                </tr>
                                                <?php
                                            endforeach;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include("../../../sitemedia/html/scriptpie.php"); ?>

    </head>
<body>
    <div id="barchart_material" style="width: 900px; height: 500px;"></div>
</body>


<!--Grafico en el tiempo-->

<?php
if (count($TablaInformeEncuestadoresTiempo) > 0):
    foreach ($TablaInformeEncuestadoresTiempo as $item):
        $date = date_create($item['fechaencuesta']);

        $dAInformeEncuestadoresTiempo[] = json_decode(
            "{".
            "\"fechaencuesta\":\"".date_format($date, 'Y-m-d')."\",".
            "\"totalencuestados\":".$item['totalencuestados'].
            "}");                                       
    endforeach;
    ?><script type="text/javascript">document.getElementById('divGraTiempo').style.display="block";</script><?php
else:
    $dAInformeEncuestadoresTiempo[] = array('fechaencuesta', 'totalencuestados');
    $dAInformeEncuestadoresTiempo[] = array([], []);
    ?>
    ?><script type="text/javascript">document.getElementById('divGraTiempo').style.display="none";</script>
<?php
endif;
?>

<script>

    var chartData = generateChartData();
    var chart = AmCharts.makeChart("linechartEncuestadorTiempoDinamico", {
        "type": "serial",
        "theme": "light",
        "marginRight": 80,
        "autoMarginOffset": 20,
        "dataDateFormat": "YYYY-MM-DD",
        "marginTop": 7,
        "dataProvider": chartData,
        "valueAxes": [{
            "axisAlpha": 0.2,
            "dashLength": 1,
            "position": "left"
        }],
        "mouseWheelZoomEnabled": true,
        "graphs": [{
            "id": "g1",
            "balloonText": "[[date]]<br/><b><span style='font-size:14px;'>Total: [[totalEncuesta]]</span></b>",
            "bullet": "round",
            "bulletBorderAlpha": 1,
            "bulletColor": "#FFFFFF",
            "hideBulletsCount": 50,
            "title": "red line",
            "valueField": "totalEncuesta",
            "useLineColorForBulletBorder": true,
            "balloon":{
                "drop":true
                }
        }],
        "chartScrollbar": {
            "autoGridCount": true,
            "graph": "g1",
            "scrollbarHeight": 40
        },
        "chartCursor": {

        },
        "categoryField": "date",
        "dataDateFormat": "YYYY-MM-DD",
        "categoryAxis": {
            "parseDates": true,
            "axisColor": "#DADADA",
            "dashLength": 1,
            "minorGridEnabled": true
        },
        "export": {
            "enabled": true
        }
    });

    chart.addListener("rendered", zoomChart);
    zoomChart();

    // this method is called when chart is first inited as we listen for "rendered" event
    function zoomChart() {
        // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
        chart.zoomToIndexes(chartData.length - 40, chartData.length - 1);
    }


    // generate some random data, quite different range
    function generateChartData() {
        var chartData1 = <?= json_encode($dAInformeEncuestadoresTiempo, JSON_NUMERIC_CHECK) ?>;
        //console.log(chartData1);
        var chartData = [];
        for(i=0;i<chartData1.length;i++){
            var aux = chartData1[i]['fechaencuesta'];
            //ms = Date.parse(aux);
            //fecha = new Date(ms);
            var totalEncuesta = chartData1[i]['totalencuestados'];

            chartData.push({
                date: aux,
                totalEncuesta: totalEncuesta
            });
        }

        return chartData;
    }
</script>

<!--Grafico Encuestadores por Municipio-->
<?php
if (count($TablaEncuestasEncuestadores) > 0):
    ?>
    <script>
    var chart = AmCharts.makeChart( "bar-chart", {
          "type": "serial",
          "theme": "light",
          "dataProvider": <?php echo json_encode($TablaEncuestasEncuestadores); ?>,
          "gridAboveGraphs": true,
          "rotate" : true,
          "startDuration": 1,
          "graphs": [ {
            "balloonText": "[[username]]: <b>[[totalencuestados]]</b>",
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "totalencuestados"
          } ],
          "chartCursor": {
            "categoryBalloonEnabled": false,
            "cursorAlpha": 0,
            "zoomable": false
          },
          "categoryField": "username",
          "categoryAxis": {
            "gridPosition": "start",
            "gridAlpha": 0,
            "tickPosition": "start",
            "tickLength": 20
          },
          "export": {
            "enabled": true
          }

        } );   
</script>

    <?php
else:
    ?>
    ?><script type="text/javascript">document.getElementById('divInfEnc').style.display="none";</script>
<?php
endif;
?>

<?php if (count($TablaInformeEncuestadoresPorMunicipio) > 0): ?>
    <script type="text/javascript">document.getElementById('divTableInfEnc').style.display="block";</script>
<?php
    else:
?>  <script type="text/javascript">document.getElementById('divTableInfEnc').style.display="none";</script>
<?php
    endif; 

if ($namePoll != ''): ?>
    ?><script type="text/javascript">document.getElementById('divEncSel').style.display="block";
            //document.getElementById('divContGraficas').style.display = 'block';
            document.getElementById('divInfEnc').style.display="block";
    </script><?php
else:
     ?>
    ?><script type="text/javascript">document.getElementById('divEncSel').style.display="none";</script><?php
endif;

/*if (count($TablaInformePorDepartamento) > 0):

    foreach ($TablaInformePorDepartamento as $item):
        ?>

        <script>
            Morris.Donut({
                element: 'morris-donut-chart',
                data: [
                    {label: "Total Base EPS", value: <?php echo $item['totalbasedatos']; ?>},
                    {label: "Total encuestados", value: <?php echo $item['totalencuestadoseps']; ?>}
                ],
                colors: ['#0aae13', '#60ca66', '60ca66']
            });</script>                               
        <?php
    endforeach;
else:
    ?>
    <p> No hay registros para mostrar </p>
<?php
endif;*/
?>

</body>

</html>

