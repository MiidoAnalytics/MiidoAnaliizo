<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Miido - Analiizo</title>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <script src="../../../core/lib/amchart/amcharts/amcharts.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/pie.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/serial.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/themes/light.js" type="text/javascript"></script>
        <script src="../../../core/lib/amchart/amcharts/plugins/export/export.min.js" type="text/javascript"></script>
        <link href="../../../core/lib/amchart/amcharts/plugins/export/export.css" media="all" rel="stylesheet" type="text/css" />
        <script>
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

            function munEncuestados(inputString) {
                $.post("../../../Informedeindicadores/epidemiologicosbasicos/controlador/municpiosencuestas.php", {
                    proyecto:document.getElementById('proyecto').value,
                    encuesta:document.getElementById('encuesta').value
                }, function (data) {
                    $('#municipio').html(data);
                });
            } 

            function mostrarEncuestas() {
                proyecto = document.getElementById('proyecto').value;
                encuesta = document.getElementById('encuesta').value;
                municipio = document.getElementById('municipio').value;
                var encuestaName= $('#encuesta option:selected').text();
                var muniName= $('#municipio option:selected').text();
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
                    window.location = ("indicadores.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName + "&municipio=" + municipio+ "&muniName=" + muniName);
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

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_01.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
        <?php include("../../../Administrador/menu/controlador/menu.php"); ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Informe de indicadores - Indicadores Antropométricos y Riesgos Cardiovasculares</h1>
                    </div>                    
                </div>
                <!-- seleccionar proyecto encuesta -->
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-3">
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
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="encuesta" class="col-lg-2 control-label">Encuesta: </label>
                                        <select name='encuesta' id='encuesta' class='form-control' required required onchange="munEncuestados();">
                                             <option value="" selected="selected">SELECCIONE</option>
                                        </select> 
                                    </div>
                                </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="municpio" class="col-lg-2 control-label">Municpio: </label>
                                        <select name='municipio' id='municipio' class='form-control' > 
                                            <option value="<?php echo '0'; ?>" selected="selected">TODOS LOS MUNICIPIOS</option> 
                                        </select> 
                                    </div>
                                </div> 
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success" onclick="mostrarEncuestas()" style="margin: 2rem; margin-left: 12rem;">Aceptar</button>
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
                                                            <th><?php echo strtoupper($namePoll); ?></th>
                                                            <th><?php echo $muniName; ?></th>
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

                <div class="row" id="divGraTablas" style="display: none;">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Estado Nutricional Adultos
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id='109' onClick="reply_click(this.id)">Estado
                                                            <span>Estado nutricional según OMS para el IMC para adultos.</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Total
                                                            <span>Total de los encuestados entre 19 y 59 años por Estado nutricional</span>
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
                                                if (count($TablaEstadoNutricional) > 0):
                                                    foreach ($TablaEstadoNutricional as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['estadonut']; ?></td>
                                                            <td><?php echo $item['total']; ?></td>  
                                                        </tr>
                                                        <?php
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Estado Nutricional Adultos - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartEstaNut" style="width: 100%; height: 24em;"></div>
                                </div>                                
                            </div>                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Riesgo de Enfermedades Cardio Vasculares (Circunferencia Abdominales) - Mujeres - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartRiesgoEnfeMujer" style="width: 100%; height: 24em;"></div>
                                </div>                                
                            </div>                            
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Riesgo de Enfermedades Cardio Vasculares (Circunferencia Abdominales) - Mujeres
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id='110' onClick="reply_click(this.id)">Riesgo
                                                            <span>Riesgo de Enfermedades Cardio Vasculares (Circunferencia Abdominales) - Mujeres.</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Total
                                                            <span>Total de los encuestados entre 19 y 59 años por Riesgo de Enfermedades Cardio Vasculares</span>
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
                                                if (count($TablaRiesgoEnfeCardioMujer) > 0):
                                                    foreach ($TablaRiesgoEnfeCardioMujer as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['detalle']; ?></td>
                                                            <td><?php echo $item['total']; ?></td>  
                                                        </tr>
                                                        <?php
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Riesgo de Enfermedades Cardio Vasculares (Circunferencia Abdominales) - Hombres
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id='111' onClick="reply_click(this.id)">Riesgo
                                                            <span>Riesgo de Enfermedades Cardio Vasculares (Circunferencia Abdominales) - Hombres.</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Total
                                                            <span>Total de los encuestados Hombres entre 19 y 59 años por Riesgo de Enfermedades Cardio Vasculares</span>
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
                                                if (count($TablaRiesgoEnfeCardioHombre) > 0):
                                                    foreach ($TablaRiesgoEnfeCardioHombre as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['detalle']; ?></td>
                                                            <td><?php echo $item['total']; ?></td>  
                                                        </tr>
                                                        <?php
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Riesgo de Enfermedades Cardio Vasculares (Circunferencia Abdominales) - Hombres - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartRiesgoEnfeHombre" style="width: 100%; height: 24em;"></div>
                                </div>                                
                            </div>                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                 <div class="panel-heading">
                                    Estado Nutricional Niñas entre 5 - 19 Años - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartEstaNut519ninas" style="width: 100%; height: 24em;"></div>
                                </div> 
                            </div>                            
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Estado Nutricional Niñas entre 5 - 19 Años
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id='112' onClick="reply_click(this.id)">Estado
                                                            <span>Estado nutricional según OMS para el IMC para Niñas entre 5 - 19 Años.</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Total
                                                            <span>Total de los encuestados entre 5 - 19 años por Estado nutricional</span>
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
                                            }else{
                                                if (count($TablaEstadoNutricional519F) > 0):
                                                    foreach ($TablaEstadoNutricional519F as $item):
                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['estadonut']; ?></td>
                                                            <td><?php echo $item['total']; ?></td>  
                                                        </tr>
                                                        <?php
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Estado Nutricional Niños Entre 5 - 19 Años
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id='113' onClick="reply_click(this.id)">Estado
                                                            <span>Estado nutricional según OMS para el IMC para Niños Entre 5 - 19 Años.</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Total
                                                            <span>Total de los encuestados Entre 5 - 19 Años por Estado nutricional</span>
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
                                                if (count($TablaEstadoNutricional519M) > 0):
                                                    foreach ($TablaEstadoNutricional519M as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['estadonut']; ?></td>
                                                            <td><?php echo $item['total']; ?></td>  
                                                        </tr>
                                                        <?php
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Estado Nutricional Niños Entre 5 - 19 Años - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartEstaNut519ninos" style="width: 100%; height: 24em;"></div>
                                </div>                                
                            </div>                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                 <div class="panel-heading">
                                    Estado Nutricional Niñas entre 0 - 5 Años - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartEstaNut05ninas" style="width: 100%; height: 24em;"></div>
                                </div> 
                            </div>                            
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Estado Nutricional Niñas entre 0 - 5 Años
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id='114' onClick="reply_click(this.id)">Estado
                                                            <span>Estado nutricional según OMS para el IMC para Niñas entre 0 - 5 Años.</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Total
                                                            <span>Total de los encuestados entre 0 - 5 años por Estado nutricional</span>
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
                                            }else{
                                                if (count($TablaEstadoNutricional05F) > 0):
                                                    foreach ($TablaEstadoNutricional05F as $item):
                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['estadonut']; ?></td>
                                                            <td><?php echo $item['total']; ?></td>  
                                                        </tr>
                                                        <?php
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Estado Nutricional Niños Entre 0 - 5 Años
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id='115' onClick="reply_click(this.id)">Estado
                                                            <span>Estado nutricional según OMS para el IMC para Niños Entre 0 - 5 Años.</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Total
                                                            <span>Total de los encuestados Entre 0 - 5 Años por Estado nutricional</span>
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
                                                if (count($TablaEstadoNutricional05M) > 0):
                                                    foreach ($TablaEstadoNutricional05M as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['estadonut']; ?></td>
                                                            <td><?php echo $item['total']; ?></td>  
                                                        </tr>
                                                        <?php
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Estado Nutricional Niños Entre 5 - 19 Años - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartEstaNut05ninos" style="width: 100%; height: 24em;"></div>
                                </div>                                
                            </div>                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Presión Arterial Alta (Diastólica y Sistólica) por Género
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartpresionArterial" style="width: 100%; height: 24em;"></div>
                                </div>                                
                            </div>                            
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Presión Arterial Alta (Diastólica y Sistólica) por Género
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id='116' onClick="reply_click(this.id)">Género
                                                            <span>Género de las personas con presión arterial alta (diastólica y sistólica).</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Total
                                                            <span>Total de los encuestados presión arterial alta (diastólica y sistólica)</span>
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
                                                if (count($TablaPresionArterialGenero) > 0):
                                                    foreach ($TablaPresionArterialGenero as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['genero']; ?></td>
                                                            <td><?php echo $item['total']; ?></td>  
                                                        </tr>
                                                        <?php
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Riesgo de enfermedades pulmanares, cardiácas, crisis de asma por género
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id='117' onClick="reply_click(this.id)">Género
                                                            <span>Género de las personas con nivel de oximetría por debajo de 95%.</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Total
                                                            <span>Total de los encuestados con nivel de oximetría por debajo de 95%.</span>
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
                                                if (count($TablaBajaOximetria) > 0):
                                                    foreach ($TablaBajaOximetria as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['genero']; ?></td>
                                                            <td><?php echo $item['total']; ?></td>  
                                                        </tr>
                                                        <?php
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Riesgo de enfermedades pulmanares, cardiácas, crisis de asma - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartOximetria" style="width: 100%; height: 24em;"></div>
                                </div>                                
                            </div>                            
                        </div>
                    </div>

                    <div class="row" style="display: none;">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Campana Peso/Edad Niñas Entre 0 - 5 Años - Gráfico
                                </div>
                                <?php
                                    
                                ?>
                                <div class="panel-body">
                                    <div id="campanaNinas0_5pesoEdad" style="width: 100%; height: 34em;"></div>
                                </div>                                
                            </div>                            
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Campana Peso/Edad Niños Entre 0 - 5 Años - Gráfico
                                </div>
                                <?php
                                    
                                ?>
                                <div class="panel-body">
                                    <div id="campanaNinos0_5pesoEdad" style="width: 100%; height: 34em;"></div>
                                </div>                                
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include("../../../sitemedia/html/scriptpie.php"); ?>
        <!--        
          *****************************************************************************
          Graficos de la página
          *****************************************************************************            
        -->  
        <script>
            //Total estado nutricional Adultos
            var chart;
            AmCharts.ready(function () {
                var chart = AmCharts.makeChart("donutchartEstaNut", {
                "type": "pie",
                "sequencedAnimation":true,
                "startEffect":"elastic",
                "startDuration": 2,
                "theme": "light",
                "addClassNames": true,
                "legend":{
                    "position":"right",
                    "marginRight":0,
                    "autoMargins":true
                  },
                "innerRadius": "30%",
                "defs": {
                    "filter": [{
                      "id": "shadow",
                      "width": "200%",
                      "height": "200%",
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
                  "dataProvider": <?= json_encode($TablaEstadoNutricional, JSON_NUMERIC_CHECK) ?>,
                  "labelText" : "",
                  "valueField": "total",
                  "titleField": "estadonut",
                  "export": {
                    "enabled": true
                  }
                });

                chart.addListener("init", handleInit);

                chart.addListener("rollOverSlice", function(e) {
                  handleRollOver(e);
                });

                function handleInit(){
                  chart.legend.addListener("rollOverItem", handleRollOver);
                }

                function handleRollOver(e){
                  var wedge = e.dataItem.wedge.node;
                  wedge.parentNode.appendChild(wedge);  
                }
            });

            //Total estado nutricional 5 -19 años niñas
            var chart2;
            AmCharts.ready(function () {
                var chart2 = AmCharts.makeChart("donutchartEstaNut519ninas", {
                "type": "pie",
                "sequencedAnimation":true,
                "startEffect":"elastic",
                "startDuration": 2,
                "theme": "light",
                "addClassNames": true,
                "legend":{
                    "position":"right",
                    "marginRight":0,
                    "autoMargins":true
                  },
                "innerRadius": "30%",
                "defs": {
                    "filter": [{
                      "id": "shadow",
                      "width": "200%",
                      "height": "200%",
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
                  "dataProvider": <?= json_encode($TablaEstadoNutricional519F, JSON_NUMERIC_CHECK) ?>,
                  "labelText" : "",
                  "valueField": "total",
                  "titleField": "estadonut",
                  "export": {
                    "enabled": true
                  }
                });

                chart2.addListener("init", handleInit);

                chart2.addListener("rollOverSlice", function(e) {
                  handleRollOver(e);
                });

                function handleInit(){
                  chart2.legend.addListener("rollOverItem", handleRollOver);
                }

                function handleRollOver(e){
                  var wedge = e.dataItem.wedge.node;
                  wedge.parentNode.appendChild(wedge);  
                }
            });

            //Total estado nutricional 5 -19 años niños
            var chart3;
            AmCharts.ready(function () {
                var chart3 = AmCharts.makeChart("donutchartEstaNut519ninos", {
                "type": "pie",
                "sequencedAnimation":true,
                "startEffect":"elastic",
                "startDuration": 2,
                "theme": "light",
                "addClassNames": true,
                "legend":{
                    "position":"right",
                    "marginRight":0,
                    "autoMargins":true
                  },
                "innerRadius": "30%",
                "defs": {
                    "filter": [{
                      "id": "shadow",
                      "width": "200%",
                      "height": "200%",
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
                  "dataProvider": <?= json_encode($TablaEstadoNutricional519M, JSON_NUMERIC_CHECK) ?>,
                  "labelText" : "",
                  "valueField": "total",
                  "titleField": "estadonut",
                  "export": {
                    "enabled": true
                  }
                });

                chart3.addListener("init", handleInit);

                chart3.addListener("rollOverSlice", function(e) {
                  handleRollOver(e);
                });

                function handleInit(){
                  chart3.legend.addListener("rollOverItem", handleRollOver);
                }

                function handleRollOver(e){
                  var wedge = e.dataItem.wedge.node;
                  wedge.parentNode.appendChild(wedge);  
                }
            });

            //Total estado nutricional 0 - 5 años niñas
            var chart4;
            AmCharts.ready(function () {
                var chart4 = AmCharts.makeChart("donutchartEstaNut05ninas", {
                "type": "pie",
                "sequencedAnimation":true,
                "startEffect":"elastic",
                "startDuration": 2,
                "theme": "light",
                "addClassNames": true,
                "legend":{
                    "position":"right",
                    "marginRight":0,
                    "autoMargins":true
                  },
                "innerRadius": "30%",
                "defs": {
                    "filter": [{
                      "id": "shadow",
                      "width": "200%",
                      "height": "200%",
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
                  "dataProvider": <?= json_encode($TablaEstadoNutricional05F, JSON_NUMERIC_CHECK) ?>,
                  "labelText" : "",
                  "valueField": "total",
                  "titleField": "estadonut",
                  "export": {
                    "enabled": true
                  }
                });

                chart4.addListener("init", handleInit);

                chart4.addListener("rollOverSlice", function(e) {
                  handleRollOver(e);
                });

                function handleInit(){
                  chart4.legend.addListener("rollOverItem", handleRollOver);
                }

                function handleRollOver(e){
                  var wedge = e.dataItem.wedge.node;
                  wedge.parentNode.appendChild(wedge);  
                }
            });

            //Total estado nutricional 0 - 5 años niños
            var chart5;
            AmCharts.ready(function () {
                var chart5 = AmCharts.makeChart("donutchartEstaNut05ninos", {
                "type": "pie",
                "sequencedAnimation":true,
                "startEffect":"elastic",
                "startDuration": 2,
                "theme": "light",
                "addClassNames": true,
                "legend":{
                    "position":"right",
                    "marginRight":0,
                    "autoMargins":true
                  },
                "innerRadius": "30%",
                "defs": {
                    "filter": [{
                      "id": "shadow",
                      "width": "200%",
                      "height": "200%",
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
                  "dataProvider": <?= json_encode($TablaEstadoNutricional05M, JSON_NUMERIC_CHECK) ?>,
                  "labelText" : "",
                  "valueField": "total",
                  "titleField": "estadonut",
                  "export": {
                    "enabled": true
                  }
                });

                chart5.addListener("init", handleInit);

                chart5.addListener("rollOverSlice", function(e) {
                  handleRollOver(e);
                });

                function handleInit(){
                  chart5.legend.addListener("rollOverItem", handleRollOver);
                }

                function handleRollOver(e){
                  var wedge = e.dataItem.wedge.node;
                  wedge.parentNode.appendChild(wedge);  
                }
            });

            //Total encuestados con presión arterial alta por género
            var chart6;
            AmCharts.ready(function () {
                var chart6 = AmCharts.makeChart("donutchartpresionArterial", {
                "type": "pie",
                "sequencedAnimation":true,
                "startEffect":"elastic",
                "startDuration": 2,
                "theme": "light",
                "addClassNames": true,
                "legend":{
                    "position":"right",
                    "marginRight":0,
                    "autoMargins":true
                  },
                "innerRadius": "30%",
                "defs": {
                    "filter": [{
                      "id": "shadow",
                      "width": "200%",
                      "height": "200%",
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
                  "dataProvider": <?= json_encode($TablaPresionArterialGenero, JSON_NUMERIC_CHECK) ?>,
                  "labelText" : "",
                  "valueField": "total",
                  "titleField": "genero",
                  "export": {
                    "enabled": true
                  }
                });

                chart6.addListener("init", handleInit);

                chart6.addListener("rollOverSlice", function(e) {
                  handleRollOver(e);
                });

                function handleInit(){
                  chart6.legend.addListener("rollOverItem", handleRollOver);
                }

                function handleRollOver(e){
                  var wedge = e.dataItem.wedge.node;
                  wedge.parentNode.appendChild(wedge);  
                }
            });

            //Total encuestados Mujeres Riesgo Cardio vascular Circunferencia abdominal
            var chart7;
            AmCharts.ready(function () {
                var chart7 = AmCharts.makeChart("donutchartRiesgoEnfeMujer", {
                "type": "pie",
                "sequencedAnimation":true,
                "startEffect":"elastic",
                "startDuration": 2,
                "theme": "light",
                "addClassNames": true,
                "legend":{
                    "position":"right",
                    "marginRight":0,
                    "autoMargins":true
                  },
                "innerRadius": "30%",
                "defs": {
                    "filter": [{
                      "id": "shadow",
                      "width": "200%",
                      "height": "200%",
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
                  "dataProvider": <?= json_encode($TablaRiesgoEnfeCardioMujer, JSON_NUMERIC_CHECK) ?>,
                  "labelText" : "",
                  "valueField": "total",
                  "titleField": "detalle",
                  "export": {
                    "enabled": true
                  }
                });

                chart7.addListener("init", handleInit);

                chart7.addListener("rollOverSlice", function(e) {
                  handleRollOver(e);
                });

                function handleInit(){
                  chart7.legend.addListener("rollOverItem", handleRollOver);
                }

                function handleRollOver(e){
                  var wedge = e.dataItem.wedge.node;
                  wedge.parentNode.appendChild(wedge);  
                }
            });

             //Total encuestados oximetria baja
            var chart7;
            AmCharts.ready(function () {
                var chart7 = AmCharts.makeChart("donutchartOximetria", {
                "type": "pie",
                "sequencedAnimation":true,
                "startEffect":"elastic",
                "startDuration": 2,
                "theme": "light",
                "addClassNames": true,
                "legend":{
                    "position":"right",
                    "marginRight":0,
                    "autoMargins":true
                  },
                "innerRadius": "30%",
                "defs": {
                    "filter": [{
                      "id": "shadow",
                      "width": "200%",
                      "height": "200%",
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
                  "dataProvider": <?= json_encode($TablaBajaOximetria, JSON_NUMERIC_CHECK) ?>,
                  "labelText" : "",
                  "valueField": "total",
                  "titleField": "genero",
                  "export": {
                    "enabled": true
                  }
                });

                chart7.addListener("init", handleInit);

                chart7.addListener("rollOverSlice", function(e) {
                  handleRollOver(e);
                });

                function handleInit(){
                  chart7.legend.addListener("rollOverItem", handleRollOver);
                }

                function handleRollOver(e){
                  var wedge = e.dataItem.wedge.node;
                  wedge.parentNode.appendChild(wedge);  
                }
            });
        </script>
    </body>
<?php 
    if ($namePoll != '' && $muniName): ?>
?>  <script type="text/javascript">
        document.getElementById('divEncSel').style.display="block";
        document.getElementById('divGraTablas').style.display = "block";
    </script>
<?php
    else:
?>
        <script type="text/javascript">
            document.getElementById('divEncSel').style.display="none";
            document.getElementById('divGraTablas').style.display = "none";
        </script>
<?php
    endif;
?>
</html>