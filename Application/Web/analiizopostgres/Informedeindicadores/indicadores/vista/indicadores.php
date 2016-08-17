<!DOCTYPE html>
<html lang="en">

    <head>

        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>

        <title>Miido - Analiizo</title>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
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
                    window.location = ("indicadores.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName);
                }
            }
            /*function reply_click(clicked_id)
            {
                reportid = clicked_id;
                var town = document.getElementById('codTown').value;
                if (town > 0) {
                    town;
                    window.location = ("reportExcel.php?intIdDiv=" + reportid + "&town_id=" + town + "&intIdReport=1");
                } else {
                    //town = 0;
                    window.location = ("reportExcel.php?intIdDiv=" + reportid + "&town_id=" + town + "&intIdReport=1");
                }
            }*/
        
        </script>
        <script src="../../../core/lib/amchart/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/pie.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/serial.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/themes/light.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/plugins/export/export.min.js" type="text/javascript"></script>
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
        <?php include("../../../Administrador/menu/controlador/menu.php"); ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Informe de indicadores - Indicadores Básicos</h1>
                    </div>                    
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
        <?php
            $i = 1;
            $t = 1; 
            $images = 1;
            foreach ($preguntasGeneradas as $key) { 
                if($key['tipo'] != 'tf' && $key['tipo'] != 'cb'):
                ?>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="min-height: 82px;">
                                <div class="panel-heading-text">
                                    <?php 
                                        echo $key['descripcion'];
                                    ?>
                                    <input id="titulos<?php echo $t; ?>" type="hidden" value="<?php echo $key['descripcion']; ?>" />
                                </div>
                                <div class="panel-heading-menu">
                                    <ul class="nav navbar-top-links">
                                        <li class="dropdown">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                <i class="fa fa-caret-down"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-user">
                                                <li><a class="botonExcel" id="botonExcel<?php echo $t; ?>"><i class="fa fa-file-excel-o fa-fw"></i> Descargar en Excel</a>
                                                </li>
                                            </ul>            
                                        </li> 
                                    </ul>  
                                </div>
                            </div>  
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table" id="Exportar_a_Excel<?php echo $t; ?>">
                                        <thead>
                                        <tr>
                                            <th>
                                                <div class="cssToolTip">Valor
                                                    <span>Valor de respuestas</span>
                                                </div>
                                            </th>
                                        <th>
                                        <div class="cssToolTip">Total
                                            <span>Total de encuestados que respondieron a cada opcíon</span>
                                        </div>
                                        </th>                                            
                                        </tr>
                                        </thead>
                                        <tbody id="autoSuggestionsList">                               
                                    <?php
                                        if (empty($_SESSION['user'])) {
                                    ?>
                                            <div class="alert alert-danger">
                                                El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                            </div>
                                            <?php
                                        } else {
                                            $pregunta = $key['pregunta'];
                                            if($key['tipo'] == 'tv'){
                                                $idPre = $key['id'];
                                                $proyecto = $indicador->getIdProyecto();
                                                $encuesta = $indicador->getIdEncuesta();
                                                $opciones = Indicadores::recuperarOpcionesPreguntaCB($idPre, $proyecto, $encuesta);

                                               $respuestasPreg99999999999[] = array();
                                               $index = 0;
                                               $opt = 0;
                                                foreach ($opciones as $key3) { 
                                                    $option = $key3['option'];
                                                    $option = str_replace('"', "", $option);
                                                    if($opt != 0){
                                                        $name = $pregunta.'_opcion'.$opt;
                                                        $proyecto = $indicador->getIdProyecto();
                                                        $encuesta = $indicador->getIdEncuesta();
                                                        $respuestasOpciones = Indicadores::recuperarrespuestaspreguntacheckbox($name,$proyecto, $encuesta);
                                                        $contador = 0;
                                                        foreach ($respuestasOpciones as $item) {
                                                            if($item['valor'] == 'true'){
                                                                $contador++;
                                                            }
                                                        }
                                                        if($contador > 0){
                                                            $respuestasPreg99999999999[$index]['total'] = $item['total']; 
                                                            $respuestasPreg99999999999[$index]['valor'] = $option ; 
                                                            $index++;
                                                        }else{
                                                            $respuestasPreg99999999999[$index]['total'] = 0; 
                                                            $respuestasPreg99999999999[$index]['valor'] = $option ; 
                                                            $index++;
                                                        }
                                                    }
                                                    $opt++;
                                                }
                                                $respuestasPregunta = $respuestasPreg99999999999;
                                            }else{ 
                                                $proyecto = $indicador->getIdProyecto();
                                                $encuesta = $indicador->getIdEncuesta();
                                                $respuestasPregunta = Indicadores::recuperarRespuestasPregunta($pregunta,$proyecto, $encuesta);
                                            }

                                            if (count($respuestasPregunta) > 0):
                                                foreach ($respuestasPregunta as $item):
                                                    if($item['valor'] != ''){
                                                   ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['valor']; ?></td>
                                                        <td><?php echo $item['total']; ?></td>  
                                                    </tr>
                                                    <?php
                                                    }
                                                endforeach;
                                            else:
                                                ?>
                                                <p> No hay registros para mostrar </p>
                                            <?php
                                            endif;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <form action="ficheroExcel.php" method="post" target="_blank" id="FormularioExportacion">
                                        <input type="hidden" id="titulo" name="titulo" />
                                        <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
                                    </form>
                                    <script>
                                        $("#botonExcel<?php echo $t; ?>").unbind();
                                        $("#botonExcel<?php echo $t; ?>").click(function(event) {
                                            $("#datos_a_enviar").val( $("<div>").append( $("#Exportar_a_Excel<?php echo $t; ?>").eq(0).clone()).html());
                                            $("#titulo").val($("#titulos<?php echo $t; ?>").val());
                                            $("#FormularioExportacion").submit();
                                        });
                                    </script>
                                    <?php $t++; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Gráfico
                            </div>
                            <div class="panel-body">
                                <div id="chart<?php echo $i; ?>" style="width: 100%; height: 65ex;"></div>
                                </div>                                
                            </div>                            
                        </div>
                    </div>
                    
                <?php
                        $images++;
                    endif;
                    $i++;
                }    
                ?>
            </div>   
        </div>
            <?php include("../../../sitemedia/html/scriptpie.php"); ?>
            <!--        
              *****************************************************************************
              Graficos de la página
              *****************************************************************************            
            -->  
            <?php
                $varf='';
                $j = 0; 
                foreach ($preguntasGeneradas as $key2) { 
                $j++;
                    $respuestasPregunta2[] = array();
                                   
                    $pregunta2 = $key2['pregunta'];
                    if($key2['tipo'] == 'tv'){
                        $idPre = $key2['id'];
                        $proyecto = $indicador->getIdProyecto();
                        $encuesta = $indicador->getIdEncuesta();
                        $opciones = Indicadores::recuperarOpcionesPreguntaCB($idPre, $proyecto, $encuesta);
                        $index = 0;
                        $opt = 0;
                        foreach ($opciones as $key3) { 
                            $option = $key3['option'];
                            $option = str_replace('"', "", $option);
                            if($opt != 0){
                                $name = $pregunta.'_opcion'.$opt;
                                $proyecto = $indicador->getIdProyecto();
                                $encuesta = $indicador->getIdEncuesta();
                                $respuestasOpciones = Indicadores::recuperarrespuestaspreguntacheckbox($name,$proyecto, $encuesta);
                                $contador = 0;
                                foreach ($respuestasOpciones as $item) {
                                    if($item['valor'] == 'true'){
                                        $contador++;
                                    }
                                }
                                if($contador > 0){
                                    $respuestasPreg2[$index] = json_decode(
                                    "{".
                                    "\"valor\":\"".$option."\",".
                                    "\"total\":".$item['total'].
                                    "}");
                                    $index++;
                                }else{
                                    $respuestasPreg2[$index] = json_decode(
                                    "{".
                                    "\"valor\":\"".$option."\",".
                                    "\"total\":"."0".
                                    "}");
                                    $index++;
                                }
                            }
                            $opt++;
                        }
                        $respuestasPregunta2 = $respuestasPreg2;
                    } elseif ($key2['tipo'] == 'cb') {

                    } else {
                        $proyecto = $indicador->getIdProyecto();
                        $encuesta = $indicador->getIdEncuesta();
                        $respuestasPregunta2 = Indicadores::recuperarRespuestasPregunta($pregunta2, $proyecto, $encuesta);
                    }
                    
                    if ($j%2==0){
                                    
            $varf .= '<script>  
                var num = "chart'.$j.'";
                var visits = Math.round(Math.random() * (40)) + 20;
                        chartDonut = \'varname\';
                        str = chartDonut+\' = \'+visits;
                        eval(str)
                varname = AmCharts.makeChart(num, {
                        "type": "pie",
                        "startDuration": 2,
                        "theme": "light",
                        "addClassNames": true,
                        "legend":{
                        "position":"bottom",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[valor]]<br><span style=\'font-size:14px\'><b>[[total]]</b> ([[percents]]%)</span>",
                        "autoMargins":false
                    },
                    "innerRadius": "0%",
                    "defs": {
                    "filter": [{
                        "id": "shadow",
                        "width": "50%",
                        "height": "50%",
                        "feOffset": {
                            "result": "offOut",
                            "in": "SourceAlpha",
                            "dx": 0,
                            "dy": 0
                        },
                        "feGaussianBlur": {
                        "result": "blurOut",
                        "in": "offOut",
                        "stdDeviation": 5
                        },
                        "feBlend": {
                            "in": "SourceGraphic",
                            "in2": "blurOut",
                            "mode": "normal"
                        }
                    }]
                    },
                    "dataProvider": JSON.parse(\''.json_encode($respuestasPregunta2, JSON_NUMERIC_CHECK).'\'),
                    "labelRadius" : "-30",
                    "labelText" : "",
                    "valueField": "total",
                    "titleField": "valor",
                                  /*"depth3D" : "10",
                                  "angle" : "15",*/
                    "export": {
                        "enabled": true
                      }
                    });
                    
                </script>';
                }else{
                    
                    $varf .= '<script>
                        var num = "chart'.$j.'";
                        var visits = '.$j.';
                        chartPutaBarra = \'varname\';
                        str = chartPutaBarra+\' = \'+visits;
                        eval(str)
                        varname = AmCharts.makeChart(num, {
                              "type": "serial",
                              "theme": "light",
                              "dataProvider": JSON.parse(\''.json_encode($respuestasPregunta2, JSON_NUMERIC_CHECK).'\'),
                              "gridAboveGraphs": true,
                              "rotate" : true,
                              "startDuration": 1,
                              "graphs": [ {
                                "balloonText": "[[valor]]: <b>[[total]]</b>",
                                "fillAlphas": 0.8,
                                "lineAlpha": 0.2,
                                "type": "column",
                                "valueField": "total"
                              } ],
                              "chartCursor": {
                                "categoryBalloonEnabled": false,
                                "cursorAlpha": 0,
                                "zoomable": false
                              },
                              "categoryField": "valor",
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
                    </script>';
                }
                }
                echo $varf;
            ?>    
        </body>
</html>