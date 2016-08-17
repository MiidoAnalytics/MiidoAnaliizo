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
                    window.location = ("morbilidad.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName + "&municipio=" + municipio+ "&muniName=" + muniName);
                }
            }

            //descarga los reportes asignados a cada id
            function reply_click(clicked_id)
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
            }
        </script>
        <script type="text/javascript">
            /*function reply_click(clicked_id)
            {

                reportid = clicked_id;
                var town = document.getElementById('codTown').value;
                if (town > 0) {
                    town;
                    window.location = ("reportExcel.php?intIdDiv=" + reportid + "&town_id=" + town);
                } else {
                    //town = 0;
                    window.location = ("reportExcel.php?intIdDiv=" + reportid + "&town_id=" + town);
                }
            }*/
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
                        <h1 class="page-header">Informe de indicadores - Morbilidad</h1>
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
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Principales medicamentos usados por los afiliados - Gráfico
                                </div>                           
                                <div class="panel-body">
                                    <div id="chartMedicamentos" style="width: 1200px; height: 1200px;"></div>
                                </div>                            
                            </div>                       
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Principales medicamentos usados por los afiliados
                                    </div>                               
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                <div class="cssToolTip" id='19' onClick="reply_click(this.id)">Nombre Medicamento
                                                    <span>Nombre del medicamento</span>
                                                </div>
                                                </th>
                                                <th>
                                                <div class="cssToolTip">numerador
                                                    <span>Total de personas que toman un medicamento específico encuestados de {$client_name}</span>
                                                </div>
                                                </th>
                                                <th>
                                                <div class="cssToolTip">denominador
                                                    <span>Total de personas que toman medicamentos encuestados de {$client_name}</span>
                                                </div>
                                                </th>
                                                <th>
                                                <div class="cssToolTip">Porcentaje
                                                    <span>Porcentaje del (numerador entre el denominador) x 100</span>
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
                                                    if (count($TablaMedicamentosAfiliados) > 0):
                                                        foreach ($TablaMedicamentosAfiliados as $item):
                                                            ?>

                                                            <tr class="odd gradeX">
                                                                <td><?php echo $item['descripcion_medicamento']; ?></td>
                                                                <td><?php echo $item['numerador']; ?></td>
                                                                <td><?php echo $item['denominador']; ?></td>
                                                                <td><?php
                                                                    $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                    echo $num;
                                                                    ?></td>                                                        
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
                    </div>

                    <div class="row">                                        

                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Patologías personales Reportadas - Gráfico
                                </div>                            
                                <div class="panel-body">
                                    <div id="chartEnfermedadesPersonales" style="width: 900px; height: 900px;"></div>                                
                                </div>                           
                            </div>                        
                        </div>

                        <div class="row">

                            <div class="col-lg-8">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Patologías personales Reportadas
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                <div class="cssToolTip" id='20' onClick="reply_click(this.id)">Nombre Enfermedad
                                                    <span>Nombre de la patología</span>
                                                </div>
                                                </th>
                                                <th>
                                                <div class="cssToolTip">numerador
                                                    <span>Total de personas que presentan una patología específica encuestadas de {$client_name}</span>
                                                </div>
                                                </th>
                                                <th>
                                                <div class="cssToolTip">denominador
                                                    <span>Total de personas presentan alguna patología encuestadas de {$client_name}</span>
                                                </div>
                                                </th>
                                                <th>
                                                <div class="cssToolTip">Porcentaje
                                                    <span>Porcentaje del (numerador entre el denominador) x 100</span>
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
                                                    if (count($TablaEnfermedadesPersonales) > 0):
                                                        foreach ($TablaEnfermedadesPersonales as $item):
                                                            ?>

                                                            <tr class="odd gradeX">
                                                                <td><?php echo $item['detalle']; ?></td>
                                                                <td><?php echo $item['numerador']; ?></td>
                                                                <td><?php echo $item['denominador']; ?></td>
                                                                <td><?php
                                                                    $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                    echo $num;
                                                                    ?></td>                                                        
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
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.panel-body -->
                                </div>
                                <!-- /.panel -->
                            </div>

                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Prevalencia de anemia en mujeres de 10 a 13 años
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='21' onClick="reply_click(this.id)">Numerador
                                                <span>Total mujeres de 10 a 13 años, con código enfernmedad D50, D64 encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='22' onClick="reply_click(this.id)">Denominador
                                                <span>Total mujeres de 10 a 13 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
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
                                                if (count($TablaPrevaAnemiMuj3a10) > 0):
                                                    foreach ($TablaPrevaAnemiMuj3a10 as $item):
                                                        ?>

                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                echo $num;
                                                                ?></td>                                                         
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Prevalencia de Diabetes Mellitus en personas de 18 a 69 años
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='23' onClick="reply_click(this.id)">Numerador
                                                <span>Total personas de 18 a 69 años, con código enfernmedad E10, E64, O24 encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='24' onClick="reply_click(this.id)">Denominador
                                                <span>Total personas de 18 a 69 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
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
                                                if (count($TablaDiabetesMellitus18a69) > 0):
                                                    foreach ($TablaDiabetesMellitus18a69 as $item):
                                                        ?>

                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                echo $num;
                                                                ?></td>                                                        
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-6 -->
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Prevalencia de enfermedad renal crónica
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='25' onClick="reply_click(this.id)">Numerador
                                                <span>Total personas con código enfernmedad N17, N19 encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total personas encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
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
                                                if (count($TablaEnfermedadRenal) > 0):
                                                    foreach ($TablaEnfermedadRenal as $item):
                                                        ?>

                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                echo $num;
                                                                ?></td>                                                        
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-6 -->
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Prevalencia de Hipertensión Arterial en personas de 18 a 69 años
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='26' onClick="reply_click(this.id)">Numerador
                                                <span>Total personas de 18 a 69 años, con código enfernmedad I10,I15,O10,O14,I16, presión arterial diastólica mayor de 80 y presión arterial sistólica mayor de 120 encuestados de Comfasucre</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='24' onClick="reply_click(this.id)">Denominador
                                                <span>Total personas de 18 a 69 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
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
                                                if (count($TablaHipertensionArterial18a69) > 0):
                                                    foreach ($TablaHipertensionArterial18a69 as $item):
                                                        ?>

                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                echo $num;
                                                                ?></td>                                                        
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-6 -->
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Prevalencia de obesidad en mujeres de 18 a 64 años
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='27' onClick="reply_click(this.id)">Numerador
                                                <span>Total mujeres de 18 a 64 años con indice de masa corporal mayor a 30 encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='28' onClick="reply_click(this.id)">Denominador
                                                <span>Total mujeres de 18 a 64 años encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
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
                                                if (count($TablaObesidadMuj18a64) > 0):
                                                    foreach ($TablaObesidadMuj18a64 as $item):
                                                        ?>

                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                echo $num;
                                                                ?></td>                                                        
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-6 -->
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Prevalencia de obesidad en personas de 18 a 64 años
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='29' onClick="reply_click(this.id)">Numerador
                                                <span>Total personas de 18 a 64 años con indice de masa corporal mayor a 30 encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='30' onClick="reply_click(this.id)">Denominador
                                                <span>Total personas de 18 a 64 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
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
                                                if (count($TablaObesidad18a64) > 0):
                                                    foreach ($TablaObesidad18a64 as $item):
                                                        ?>

                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                echo $num;
                                                                ?></td>                                                        
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-6 -->
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Prevalencia de obesidad en hombres de 18 a 64 años
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='31' onClick="reply_click(this.id)">Numerador
                                                <span>Total hombres de 18 a 64 años con indice de masa corporal mayor a 30 encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='32' onClick="reply_click(this.id)">Denominador
                                                <span>Total hombres de 18 a 64 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
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
                                                if (count($TablaObesidadHom18a64) > 0):
                                                    foreach ($TablaObesidadHom18a64 as $item):
                                                        ?>

                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                echo $num;
                                                                ?></td>                                                        
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-6 -->
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Prevalencia registrada de VIH/Sida
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='33' onClick="reply_click(this.id)">Numerador
                                                <span>Total personas con código enfernmedad B20,B24,R75,Z21, encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total personas encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
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
                                                if (count($TablaVIHSida) > 0):
                                                    foreach ($TablaVIHSida as $item):
                                                        ?>

                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                echo $num;
                                                                ?></td>                                                        
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-6 -->
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Prevalencia registrada de VIH/Sida en personas de 15 a 49 años
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='34' onClick="reply_click(this.id)">Numerador
                                                <span>Total personas de 15 a 49 años con código enfernmedad B20,B24,R75,Z21, encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='35' onClick="reply_click(this.id)">Denominador
                                                <span>Total personas encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
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
                                                if (count($TablaVIHPersonas15a49) > 0):
                                                    foreach ($TablaVIHPersonas15a49 as $item):
                                                        ?>

                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                echo $num;
                                                                ?></td>                                                        
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-6 -->
                    </div>
                </div>
            </div>

            <?php include("../../../sitemedia/html/scriptpie.php"); ?>

            <!--        
            *****************************************************************************
            Graficos de la página
            *****************************************************************************            
            -->

            <!--Incidencia medicamentos personales-->

            <script>
               
                var chart = AmCharts.makeChart("chartMedicamentos", {
                    "theme": "light",
                    "type": "serial",
                    "rotate": true,
                    "startDuration": 1,
                    "dataProvider": <?= json_encode($TablaMedicamentosAfiliados, JSON_NUMERIC_CHECK) ?>,
                    "valueAxes": [{
                        "title": "Porcentaje"
                    }],
                    "graphs": [{
                        "balloonText": "Porcentaje [[category]]:[[value]]",
                        "fillAlphas": 1,
                        "lineAlpha": 0.2,
                        "title": "porcentaje",
                        "type": "column",
                        "valueField": "porcentaje"
                    }],
                    "depth3D": 20,
                    "angle": 30,
                    "rotate": true,
                    "categoryField": "descripcion_medicamento",
                    "categoryAxis": {
                        "gridPosition": "start",
                        "fillAlpha": 0.05,
                        "position": "left"
                    },
                    "export": {
                        "enabled": true
                     }
                });
                jQuery('.chart-input').off().on('input change',function() {
                    var property    = jQuery(this).data('property');
                    var target      = chart;
                    chart.startDuration = 0;

                    if ( property == 'topRadius') {
                        target = chart.graphs[0];
                        if ( this.value == 0 ) {
                          this.value = undefined;
                        }
                    }

                    target[property] = this.value;
                    chart.validateNow();
                });
            </script>

            <!--Incidencia enfermedades personales-->

            <script>
                var chart = AmCharts.makeChart("chartEnfermedadesPersonales", {
                    "type": "serial",
                     "theme": "light",
                    "categoryField": "detalle",
                    "rotate": true,
                    "startDuration": 1,
                    "categoryAxis": {
                        "gridPosition": "start",
                        "position": "left"
                    },
                    "trendLines": [],
                    "graphs": [
                        {
                            "balloonText": "Porcentaje:[[porcentaje]]",
                            "fillAlphas": 0.8,
                            "id": "AmGraph-1",
                            "lineAlpha": 0.2,
                            "title": "Porcentaje",
                            "type": "column",
                            "valueField": "porcentaje"
                        }
                    ],
                    "guides": [],
                    "valueAxes": [
                        {
                            "id": "ValueAxis-1",
                            "position": "top",
                            "axisAlpha": 0
                        }
                    ],
                    "allLabels": [],
                    "balloon": {},
                    "titles": [],
                    "dataProvider":<?= json_encode($TablaEnfermedadesPersonales, JSON_NUMERIC_CHECK) ?>,
                    "export": {
                        "enabled": true
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
