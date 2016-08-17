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
                    window.location = ("satisfaccion.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName + "&municipio=" + municipio+ "&muniName=" + muniName);
                }
            }
        </script>
    </head>   

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_01.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Informe de indicadores - Satisfacción</h1>
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
                                    La antención en la EPS fue?
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip">Atención
                                                            <span>La Antención En La EPS</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">numerador
                                                            <span>Total de casas encuestadas por tipo de indicadaor</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">denominador
                                                            <span>Total casas encuestadas</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje de casas encuestadas sobre el indicador de la atención</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaAtencionEPS">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaAtencionEPS) > 0):
                                                    foreach ($TablaAtencionEPS as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['atencion']; ?></td>
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                echo $num;
                                                                ?>
                                                            </td>     
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
                                    La respuesta a la antención fue?
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip">Respuesta
                                                            <span>La Respuesta A La Antención</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">numerador
                                                            <span>Total de casas encuestadas por tipo de indicadaor</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">denominador
                                                            <span>Total casas encuestadas</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje de casas encuestadas sobre el indicador de la atención</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaRespuestaAtencion">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaRespuestaAtencion) > 0):
                                                    foreach ($TablaRespuestaAtencion as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['respuesta']; ?></td>
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
                                    La respuesta por parte del personal de la EPS fue?
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip">Personal atención
                                                            <span>La Respuesta Por Parte Del Personal De La EPS</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">numerador
                                                            <span>Total de casas encuestadas por tipo de indicador</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">denominador
                                                            <span>Total casas encuestadas</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje de casas encuestadas sobre el indicador de la respuesta del personal</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaRespuestaPersonalEPS">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaRespuestaPersonalEPS) > 0):
                                                    foreach ($TablaRespuestaPersonalEPS as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['personal_atencion']; ?></td>
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
                                    La información para solicitar un servicio fue?
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip">Información disponible
                                                            <span>La Información Para Solicitar Un Servicio </span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">numerador
                                                            <span>Total de casas encuestadas por tipo de indicador</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">denominador
                                                            <span>Total casas encuestadas</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje de casas encuestadas sobre el indicador de la información de solicitud de servicio</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaInformacionServicio">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaInformacionServicio) > 0):
                                                    foreach ($TablaInformacionServicio as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['informacion_disponible']; ?></td>
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
                                    Usted cree que las instalaciones físicas son?
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip">Comodidades
                                                            <span>Usted Cree Que Las Instalaciones Físicas</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">numerador
                                                            <span>Total de casas encuestadas por tipo de indicador</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">denominador
                                                            <span>Total casas encuestadas</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje de casas encuestadas sobre el indicador de las instalaciones físicas</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablasInstalacionesFisicas">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaInstalacionesFisicas) > 0):
                                                    foreach ($TablaInstalacionesFisicas as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['comodidades']; ?></td>
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
                                    En general la atención usted cree que ha sido?
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip">Concepto general
                                                            <span>En General La Atención Usted Cree Que Ha Sido</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">numerador
                                                            <span>Total de casas encuestadas por tipo de indicador</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">denominador
                                                            <span>Total casas encuestadas</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje de casas encuestadas sobre el indicador de la atención general</span>
                                                        </div>
                                                    </th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaAtencionGeneral">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaAtencionGeneral) > 0):
                                                    foreach ($TablaAtencionGeneral as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['concepto_general']; ?></td>
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
                                    Usted considera que la EPS es?
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip">Percepción general
                                                            <span>Usted Considera Que La EPS</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">numerador
                                                            <span>Total de casas encuestadas por tipo de indicador</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">denominador
                                                            <span>Total casas encuestadas</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje de casas encuestadas sobre el indicador de como considera la EPS</span>
                                                        </div>
                                                    </th>                                           
                                                </tr>
                                            </thead>
                                            <tbody id="TablaEpsPercepcion">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaEpsPercepcion) > 0):
                                                    foreach ($TablaEpsPercepcion as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['percepcion_general']; ?></td>
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
            </div>
        </div>

        <?php include("../../../sitemedia/html/scriptpie.php"); ?>  

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
