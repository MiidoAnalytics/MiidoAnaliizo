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
                    window.location = ("mujeryedadfertil.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName + "&municipio=" + municipio+ "&muniName=" + muniName);
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
                        <h1 class="page-header">Informe de indicadores - Mujer y Edad Fértil</h1>
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
                                    Edad promedio de inicio de la mestruación
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="66" onClick="reply_click(this.id)">Numerador
                                                            <span>Edad Promedio De Inicio De La Mestruación</span>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="TablaEdadPromedioMenstruacion">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($recuperarEdadPromedioMenstruacion) > 0):
                                                    foreach ($recuperarEdadPromedioMenstruacion as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>                                                       
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
                                    Edad promedio de retiro de la mestruación
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="67" onClick="reply_click(this.id)">Numerador
                                                            <span>Edad Promedio De Retiro De La Mestruación</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaEdadRetiroMenstruacion">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaEdadRetiroMenstruacion) > 0):
                                                    foreach ($TablaEdadRetiroMenstruacion as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>                                                       
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
                                    Edad promedio de inicio de vida sexual activa
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="68" onClick="reply_click(this.id)">Numerador
                                                            <span>Edad Promedio De Inicio De Vida Sexual Activa</span>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="TablaEdadPromedioVidaSexual">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaEdadPromedioVidaSexual) > 0):
                                                    foreach ($TablaEdadPromedioVidaSexual as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>                                                       
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
                                    Porcentaje de mujeres en embarazo
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="69" onClick="reply_click(this.id)">Numerador
                                                            <span>Total mujeres embarazadas encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip" id="70" onClick="reply_click(this.id)">Denominador
                                                            <span>Total mujeres entre 15 y 45  encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaMujerEmbarazo">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaMujerEmbarazo) > 0):
                                                    foreach ($TablaMujerEmbarazo as $item):
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
                                    
                                </div>
                                
                            </div>
                            
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Porcentaje de mujeres embarazadas sin control de embarazo
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="71" onClick="reply_click(this.id)">Numerador
                                                            <span>Total mujeres embarazadas sin control de embarazo encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip" id="72" onClick="reply_click(this.id)">Denominador
                                                            <span>Total mujeres embarazadas encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaMujerEmbarazadaSinControl">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaMujerEmbarazadaSinControl) > 0):
                                                    foreach ($TablaMujerEmbarazadaSinControl as $item):
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
                                    
                                </div>
                                
                            </div>
                            
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Numero promedio de consultas por embarazo
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="73" onClick="reply_click(this.id)">Numerador
                                                            <span>Total consultas de embarazo de mujeres embarazadas con control de embarazo encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">denominador
                                                            <span>Total mujeres embarazadas encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Promedio
                                                            <span>Promedio de consultas por embarazo</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaConsultasembarazo">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaConsultasEmbarazo) > 0):
                                                    foreach ($TablaConsultasEmbarazo as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".") . "";
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
                        
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Porcentaje de mujes embarazadas sin suplemento nutricional
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="74" onClick="reply_click(this.id)">Numerador
                                                            <span>Total mujeres embarazadas sin suplemento nutricional encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip" id="75" onClick="reply_click(this.id)">Denominador
                                                            <span>Total mujeres embarazadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaMujerSinSumplementoNutricional">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaMujerSinSumplementoNutricional) > 0):
                                                    foreach ($TablaMujerSinSumplementoNutricional as $item):
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
                                    
                                </div>
                                
                            </div>
                            
                        </div>
                        
                    </div>

                    <div class="row">

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Porcentaje de mujes en edad fertil con planificacion familiar
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="76" onClick="reply_click(this.id)">Numerador
                                                            <span>Total mujeres entre 15 y 45 años con método de planificacion familiar encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip" id="77" onClick="reply_click(this.id)">Denominador
                                                            <span>Total mujeres entre 15 y 45 años encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaMujerEdadFertilPlanificacion">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaMujerEdadFertilPlanificacion) > 0):
                                                    foreach ($TablaMujerEdadFertilPlanificacion as $item):
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
                                    
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Metodos de planificación familiar
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="78" onClick="reply_click(this.id)">Tipo de método
                                                            <span>Tipo de método</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">numerador
                                                            <span>Total mujeres con un método de planificación específico encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">denominador
                                                            <span>Total mujeres com método de planificación familiar encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaMetodosPlanificacionFamiliar">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaMetodosPlanificacionFamiliar) > 0):
                                                    $dAPlanificacion[] = array('tipo_planificacion', 'porcentaje');
                                                    foreach ($TablaMetodosPlanificacionFamiliar as $item):
                                                        $dAPlanificacion[] = array($item[0], $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['tipo_planificacion']; ?></td>
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
                                                    $dAPlanificacion[] = array('tipo_planificacion', 'porcentaje');
                                                    $dAPlanificacion[] = array([], []);
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
                                    Metodos de planificación familiar - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="piechartPlanificacion" style="width: 100%; height: 60ex;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Proveedor métodos de planificación familiar - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="piechartProveedorPlanificacion" style="width: 100%; height: 50ex;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Proveedor métodos de planificación familiar
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="79" onClick="reply_click(this.id)">Proveedor
                                                            <span>Proveedor del método</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">numerador
                                                            <span>Cantidad de personas con métodos de planificación por proveedor encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">denominador
                                                            <span>Total personas con métodos de planificación familiar encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaProveedorMetodosPF">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaProveedorMetodosPF) > 0):
                                                    $dAProveedorPlanificacion[] = array('pertenencia', 'Porcentaje');
                                                    foreach ($TablaProveedorMetodosPF as $item):
                                                        $dAProveedorPlanificacion[] = array($item[0], $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['proovedor']; ?></td>
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
                                                    $dAProveedorPlanificacion[] = array('pertenencia', 'Porcentaje');
                                                    $dAProveedorPlanificacion[] = array([], []);
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
                                    Promedio de hijos por mujeres
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="80" onClick="reply_click(this.id)">Numerador
                                                            <span>Sumatoria de hijos por mujer encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">denominador
                                                            <span>Total mujeres con hijos encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Promedio
                                                            <span>Promedio de hijos por mujer</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaHijosPorMujer">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaHijosPorMujer) > 0):
                                                    foreach ($TablaHijosPorMujer as $item):
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
                                    
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Porcentaje de mujeres que no realizan citología durante el último año
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="81" onClick="reply_click(this.id)">Numerador
                                                            <span>Total mujeres mayores de 15 años con actividad sexual activa sin citologia encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip" id="82" onClick="reply_click(this.id)">Denominador
                                                            <span>Total mujeres mayores de 15 años con actividad sexual activa encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaMujerSinCitologia">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaMujerSinCitologia) > 0):
                                                    foreach ($TablaMujerSinCitologia as $item):
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
                                    
                                </div>
                                
                            </div>
                            
                        </div>
                        
                    
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Porcentaje de conocimiento exámen de mama
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="83" onClick="reply_click(this.id)">Numerador
                                                            <span>Total mujeres sin conocimiento de exámen de mama encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip" id="84" onClick="reply_click(this.id)">Denominador
                                                            <span>Total mujeres mayores de 18 años encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaConoceExamenMama">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaConoceExamenMama) > 0):
                                                    foreach ($TablaConoceExamenMama as $item):
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
                                    
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>

                    <div class="row">
                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Total de embarazos por rango de edad
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>                                                    
                                                        <th>
                                                            <div class="cssToolTip" id="85" onClick="reply_click(this.id)">Rango Edad
                                                                <span>Grupo etario</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Cantidad embarazos
                                                                <span>Cantidad de mujeres embarazadas correspondientes a un grupo etario encuestadas de {$client_name}</span>
                                                            </div>
                                                        </th>                                                    
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaembarazosRangoEdad">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaEmbarazosRangoEdad) > 0):
                                                        $dAembarazosEdad[] = array('rangoedad', 'embarazo');
                                                        foreach ($TablaEmbarazosRangoEdad as $item):
                                                            $dAembarazosEdad[] = array($item[0], $item[1]);
                                                            ?>
                                                            <tr class="odd gradeX">
                                                                <td><?php echo $item['rangoedad']; ?></td>
                                                                <td><?php echo $item['embarazo']; ?></td>                                                                    
                                                            </tr>
                                                            <?php
                                                        endforeach;
                                                    else:
                                                        $dAembarazosEdad[] = array('rangoedad', 'embarazo');
                                                        $dAembarazosEdad[] = array([], []);
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
                                        Total de embarazos por rango de edad - Gráfico
                                    </div>
                                    <div class="panel-body">
                                        <div id="chartEmbarazosEdad" style="width: 100%; height: 100ex;"></div>
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

            <!--Metodos de planificación familiar-->

            <script>
                var chartMetPla = AmCharts.makeChart("piechartPlanificacion", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"bottom",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[title]]<br><span style='font-size:14px'></span>",
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
                      "dataProvider": <?= json_encode($TablaMetodosPlanificacionFamiliar, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-40",
                      "labelText" : "",
                      "valueField": "porcentaje",
                      "titleField": "tipo_planificacion",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartMetPla.addListener("init", handleInit);

                    chartMetPla.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartMetPla.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
            
            <!--Proveedor métodos de planificación familiar-->

            <script>
                var chartProPla = AmCharts.makeChart("piechartProveedorPlanificacion", {
                  "type": "pie",
                  "startDuration": 1,
                   "theme": "light",
                  "addClassNames": true,
                  "legend":{
                    "position":"bottom",
                    "marginRight":100,
                    "markerType" : "circle",
                    "align" : "center",
                    "balloonText" : "[[title]]<br><span style='font-size:14px'></span>",
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
                  "dataProvider": <?= json_encode($TablaProveedorMetodosPF, JSON_NUMERIC_CHECK) ?>,
                  "labelRadius" : "-40",
                  "labelText" : "",
                  "valueField": "porcentaje",
                  "titleField": "proovedor",
                  "export": {
                    "enabled": true
                  }
                });

                chartProPla.addListener("init", handleInit);

                chartProPla.addListener("rollOverSlice", function(e) {
                  handleRollOver(e);
                });

                function handleInit(){
                  chartProPla.legend.addListener("rollOverItem", handleRollOver);
                }

                function handleRollOver(e){
                  var wedge = e.dataItem.wedge.node;
                  wedge.parentNode.appendChild(wedge);  
                }
            </script>         

            <!--Total de embarazos por rango de edad-->

            <script>
                var chart = AmCharts.makeChart("chartEmbarazosEdad", {
                "type": "serial",
                 "theme": "light",
                "categoryField": "rangoedad",
                "rotate": true,
                "startDuration": 1,
                "categoryAxis": {
                    "gridPosition": "start",
                    "position": "left"
                },
                "trendLines": [],
                "graphs": [
                    {
                        "balloonText": "Total:[[embarazo]]",
                        "fillAlphas": 0.8,
                        "id": "AmGraph-1",
                        "lineAlpha": 0.2,
                        "title": "embarazo",
                        "type": "column",
                        "valueField": "embarazo"
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
                "dataProvider":<?= json_encode($TablaEmbarazosRangoEdad, JSON_NUMERIC_CHECK) ?>,
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
