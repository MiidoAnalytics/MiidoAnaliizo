<!DOCTYPE html>
<html lang="en">

    <head>

        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>      
        <title>Miido - Analiizo</title>
        <script src="../../../core/lib/amchart/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/serial.js" type="text/javascript"></script>
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
                    //window.location = ("informeAvances.php");
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
                        <h1 class="page-header">Informe de Avances -</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
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

                    <div class="row">
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
                                                    <th>Nombre Encuesta</th>                                                                                              
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                if ($namePoll != ''):
                                            ?>

                                                <tr class="odd gradeX">
                                                        <td><?php echo $namePoll; ?></td>
                                                <input type="hidden" id="codTown" value="<?php echo $item['codmunicipio']; ?>" >                                                                                                                 
                                                </tr>
                                                    <?php
                                            else:
                                                ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo('POR FAVOR SELECCIONE UNA ENCUESTA'); ?></td>
                                                <input type="hidden" id="codTown" value="0" >                                                                                                                   
                                                </tr>
                                            <?php
                                            endif;
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row"> 

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
                

            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <?php include("../../../sitemedia/html/scriptpie.php"); ?>

    </head>
<body>
    <div id="barchart_material" style="width: 900px; height: 500px;"></div>
</body>


<!--Grafico en el tiempo-->

<?php
print_r($TablaInformeEncuestadoresTiempo);
if (count($TablaInformeEncuestadoresTiempo) > 0):
    foreach ($TablaInformeEncuestadoresTiempo as $item):
        $date = date_create($item['fechaencuesta']);

        $dAInformeEncuestadoresTiempo[] = json_decode(
            "{".
            "\"fechaencuesta\":\"".date_format($date, 'Y-m-d')."\",".
            "\"totalencuestados\":".$item['totalencuestados'].
            "}");
                                                        
    endforeach;
    print_r($dAInformeEncuestadoresTiempo);
else:
    $dAInformeEncuestadoresTiempo[] = array('fechaencuesta', 'totalencuestados');
    $dAInformeEncuestadoresTiempo[] = array([], []);
    ?>
    <p> No hay registros para mostrar </p>
<?php
endif;
?>

<script>

    var chartData = generateChartData();
    //console.log(chartData);
    var chart = AmCharts.makeChart("linechartEncuestadorTiempoDinamico", {
        "type": "serial",
        "theme": "light",
        "marginRight": 80,
        "autoMarginOffset": 20,
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
            "useLineColorForBulletBorder": true
        }],
        "chartScrollbar": {
            "autoGridCount": true,
            "graph": "g1",
            "scrollbarHeight": 40
        },
        "chartCursor": {

        },
        "categoryField": "date",
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
            ms = Date.parse(aux);
            fecha = new Date(ms);
            var totalEncuesta = chartData1[i]['totalencuestados'];

            chartData.push({
                date: fecha,
                totalEncuesta: totalEncuesta
            });

        }

        return chartData;
    }
</script>

<script type="text/javascript">
   /* google.load("visualization", "1", {packages: ["linechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {

        data_array = <?= json_encode($dAInformeEncuestadoresTiempo, JSON_NUMERIC_CHECK) ?>;
        var data = google.visualization.arrayToDataTable(data_array);
        var chart = new google.visualization.LineChart(document.getElementById('linechartEncuestadorTiempoDinamico'));
        chart.draw(data, {width: 900, height: 500, legend: 'bottom', title: ''});
    }*/
</script>




<!--Grafico de barras-->
<?php
/*if (count($TablaEncuestadoresPorMunicipio) > 0):
    foreach ($TablaEncuestadoresPorMunicipio as $item):

        $dAEncuestadoresPorMunicipio[] = $item;

    endforeach;
    ?>
    <script>
        Morris.Bar({
            element: 'bar-chart',
            data: <?php echo json_encode($dAEncuestadoresPorMunicipio); ?>,
            xkey: 'y',
            ykeys: ['a'],
            labels: ['Porcentaje Encuestado: '],
            barColors: ['#0aae13', '#60ca66', '60ca66']
        });</script>
    <?php
else:
    ?>
    <p> No hay registros para mostrar </p>
<?php
endif;*/
?>


<?php
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

