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
                    window.location = ("promocionyprevencion.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName + "&municipio=" + municipio+ "&muniName=" + muniName);
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
                        <h1 class="page-header">Informe de indicadores - Promoción y Prevención</h1>
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
                    <!-- gráficas y tablas de la página -->
                    <div class="row" id="divGraTablas" style="display: none;">                   

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Porcentaje de nacidos vivos con cuatro o mas consultas de control prenatal
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip" id='36' onClick="reply_click(this.id)">Numerador
                                                                <span>Total niños menores de un año y numero de controles medicos mayores a 4 encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip" id='37' onClick="reply_click(this.id)">Denominador
                                                                <span>Total niños menores de un año encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaNacidoVivoCuatroConsultas">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaNacidoVivoCuatroConsultas) > 0):
                                                        foreach ($TablaNacidoVivoCuatroConsultas as $item):
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
                                        Número de controles prenatales en menores de un año
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip" id='38' onClick="reply_click(this.id)">Número de controles
                                                                <span>Número de controles</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">numerador
                                                                <span>Cantidad de niños menores de un año que se realizaron un determinado número de controles encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">denominador
                                                                <span>Total niños menores de un año encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaControlesPrenatalesUnAno">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaControlesPrenatalesUnAno) > 0):
                                                        $dAControlPrenatal[] = array('control_medico', 'Porcentaje');
                                                        foreach ($TablaControlesPrenatalesUnAno as $item):
                                                            $dAControlPrenatal[] = array($item[0] . '', $item[3]);
                                                            ?>
                                                            <tr class="odd gradeX">
                                                                <td><?php echo $item['control_medico']; ?></td>
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
                                                        $dAControlPrenatal[] = array('control_medico', 'Porcentaje');
                                                        $dAControlPrenatal[] = array($item[0] . '', $item[0]);
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
                                        Número de controles prenatales en menores de una año - Gráfico
                                    </div>
                                    
                                    <div class="panel-body">


                                        <div id="piechartControlPrenatal" style="width: 100%; height: 66ex;"></div>


                                    </div>
                                    
                                </div>
                                
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Afiliados por nivel de educación - Gráfico
                                    </div>
                                    
                                    <div class="panel-body">

                                        <div id="piechartNivelEducacion" style="width: 100%; height: 85ex;"></div>

                                    </div>
                                </div>                            
                            </div>                        

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Afiliados por nivel de educación
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip" id='39' onClick="reply_click(this.id)">Nivel Estudio
                                                                <span>Nivel de estudios</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">numerador
                                                                <span>Cantidad de personas que cursan un determinado nivel de estudios encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">denominador
                                                                <span>Total encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaAfiliadosEducacion">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaAfiliadosEducacion) > 0):
                                                        $dANivelEducacion[] = array('estudios', 'Porcentaje');
                                                        foreach ($TablaAfiliadosEducacion as $item):
                                                            $dANivelEducacion[] = array($item[0] . '', $item[3]);
                                                            ?>
                                                            <tr class="odd gradeX">
                                                                <td><?php echo $item['estudios']; ?></td>
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
                                                        $dANivelEducacion[] = array('estudios', 'Porcentaje');
                                                        $dANivelEducacion[] = array($item[0] . '', $item[0]);
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
                                        Numero de niños que asisten a control de crecimiento y desarrollo
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>                                            
                                                        <th>
                                                            <div class="cssToolTip" id='40' onClick="reply_click(this.id)">Numerador
                                                                <span>Total niños que asisten a crecimiento y desarrollo encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip" id='41' onClick="reply_click(this.id)">Denominador
                                                                <span>Total niños entre 0 y 15 años encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaNinosCreciDesa">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaNinosCreciDesa) > 0):
                                                        foreach ($TablaNinosCreciDesa as $item):
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
                                        Número de niños que asisten al control de lenguaje, motor y de conducta
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip" id='42' onClick="reply_click(this.id)">Detalle
                                                                <span>Detalle indicador</span>
                                                            </div>
                                                        </th>                                            
                                                        <th>
                                                            <div class="cssToolTip" id='43' onClick="reply_click(this.id)">Numerador
                                                                <span>Total niños asisten a evaluación de lenguaje, movimiento, comportamiento encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">denominador
                                                                <span>Total niños entre 0 y 15 años encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaNinosLenguajeConducta">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaNinosLenguajeConducta) > 0):
                                                        $dAControlLenguaje[] = array('detalle', 'Porcentaje');
                                                        foreach ($TablaNinosLenguajeConducta as $item):
                                                            $dAControlLenguaje[] = array($item[0], $item[3]);
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
                                                        $dAControlLenguaje[] = array('detalle', 'Porcentaje');
                                                        $dAControlLenguaje[] = array($item[0] . '', $item[0]);
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
                                        Número de niños que asisten al control de lenguaje, motor y de conducta - Gráfico
                                    </div>
                                    
                                    <div class="panel-body">

                                        <div id="piechartControlLenguaje" style="width: 100%; height: 50ex;"></div>

                                    </div>
                                </div>                            
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Número de niños que presentan problemas visual, auditivo de conducta - Gráfico
                                    </div>
                                    
                                    <div class="panel-body">

                                        <div id="piechartControlVision" style="width: 100%; height: 50ex;"></div>

                                    </div>
                                </div>                            
                            </div>

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Número de niños que presentan problemas visual, auditivo de conducta
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip" id='44' onClick="reply_click(this.id)">Detalle
                                                                <span>Detalle indicador</span>
                                                            </div>
                                                        </th>                                            
                                                        <th>
                                                            <div class="cssToolTip" >Numerador
                                                                <span>Total niños asisten un problema de visión, auditivo, conducta encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                             <div class="cssToolTip" id='45' onClick="reply_click(this.id)">Denominador
                                                                <span>Total niños entre 0 y 15 años encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaNinosProblemaVisualAudi">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaNinosProblemaVisualAudi) > 0):
                                                        $dAControlVision[] = array('detalle', 'Porcentaje');
                                                        foreach ($TablaNinosProblemaVisualAudi as $item):
                                                            $dAControlVision[] = array($item[0], $item[3]);
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
                                                        $dAControlVision[] = array('detalle', 'Porcentaje');
                                                        $dAControlVision[] = array($item[0] . '', $item[0]);
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
                                        Número de niños que recibieron desparacitación
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>                                            
                                                        <th>
                                                            <div class="cssToolTip" id="46" onClick="reply_click(this.id)">Numerador
                                                                <span>Total niños recibieron tratamiento antiparasitario encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip" id="47" onClick="reply_click(this.id)">Denominador
                                                                <span>Total niños entre 1 y 15 años encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>    
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaNinosDesparacitados">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaNinosDesparacitados) > 0):
                                                        foreach ($TablaNinosDesparacitados as $item):
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
                                        Porcentaje de esquema completo de vacunación
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip" id="48" onClick="reply_click(this.id)">Numerador
                                                                <span>Total niños entre 0 y 10 años, con esquema de vacunación completa encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip" id="49" onClick="reply_click(this.id)">Denominador
                                                                <span>Total niños entre 0 y 10 años encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>Porcentaje</th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaEsquemaCompletoVacunacion">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaEsquemaCompletoVacunacion) > 0):
                                                        foreach ($TablaEsquemaCompletoVacunacion as $item):
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
                                        Bajo peso al nacer, población menor de un año
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip" id="50" onClick="reply_click(this.id)">Numerador
                                                                <span>Total niños menores de un año y con peso menor de 2499 gramos encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip" id="51" onClick="reply_click(this.id)">Denominador
                                                                <span>Total niños menores de un año encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaBajoPesoMenorAno">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaBajoPesoMenorAno) > 0):
                                                        foreach ($TablaBajoPesoMenorAno as $item):
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
                                        Edad promedio de ablactación (edad en meses)
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip" id="52" onClick="reply_click(this.id)">Numerador
                                                                <span>Edad Promedio De Ablactación (Edad En Meses)</span>
                                                            </div>
                                                        </th>                                                                                     
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaEdadPromAblactacion">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaEdadPromAblactacion) > 0):
                                                        foreach ($TablaEdadPromAblactacion as $item):
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
                                        Promedio de consultas prenatales
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip" id="53" onClick="reply_click(this.id)">Numerador
                                                                <span>Promedio De Consultas Prenatales</span>
                                                            </div>
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaPromedioConsultaPrenatal">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaPromedioConsultaPrenatal) > 0):
                                                        foreach ($TablaPromedioConsultaPrenatal as $item):
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
                                        Porcentaje de infante con refuerzo nutricional
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip" id="54" onClick="reply_click(this.id)">Numerador
                                                                <span>Total niños entre 1 y 11 años, con refuerzo de alimentos encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip" id="55" onClick="reply_click(this.id)">Denominador
                                                                <span>Total niños entre 1 y 11 años encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaInfanteRefuerzoNutricional">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaInfanteRefuerzoNutricional) > 0):
                                                        foreach ($TablaInfanteRefuerzoNutricional as $item):
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
                                        Proveedor de refuerzo nutricional
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip" id="56" onClick="reply_click(this.id)">Proveedor Refuerzo
                                                                <span>Quien provee el refuerzo</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">denominador
                                                                <span>Cantidad de personas que tienen un proveedor de refuerzo nutricional encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">denominador
                                                                <span>Total personas con refuerzo nutricional encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaProveedorRefuerzoNutrional">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaProveedorRefuerzoNutrional) > 0):
                                                        $dAProveedorRefuerzo[] = array('proveedor_refuerzo', 'Porcentaje');
                                                        foreach ($TablaProveedorRefuerzoNutrional as $item):
                                                            $dAProveedorRefuerzo[] = array($item[0], $item[3]);
                                                            ?>
                                                            <tr class="odd gradeX">
                                                                <td><?php echo $item['proveedor_refuerzo']; ?></td>
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
                                                        $dAProveedorRefuerzo[] = array('proveedor_refuerzo', 'Porcentaje');
                                                        $dAProveedorRefuerzo[] = array($item[0] . '', $item[0]);
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
                                        Proveedor de refuerzo nutricional - Gráfico
                                    </div>
                                    
                                    <div class="panel-body">

                                        <div id="piechartProveedorRefuerzo" style="width: 100%; height: 50ex;"></div>

                                    </div>
                                </div>                            
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Ha realizado control de placa - Gráfico
                                    </div>                                
                                    <div class="panel-body">

                                        <div id="donutchartControlPlaca" style="width: 100%; height: 50ex;"></div>

                                    </div>                                
                                </div>                            
                            </div>

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Ha realizado control de placa
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip">descripcion
                                                                <span>Descripción Si/No</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">numerador
                                                                <span>Cantidad de persona que se han realizado/ no realizado control de placa encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">denominador
                                                                <span>Total personas que se han realizado/ no realizado control de placa encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaControlPlaca">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaControlPlaca) > 0):
                                                        $dAControlPlaca[] = array('descripcion', 'Porcentaje');
                                                        $i = 1;
                                                        foreach ($TablaControlPlaca as $item):
                                                            $dAControlPlaca[] = array($item[0], $item[3]);
                                                            ?>
                                                            <tr class="odd gradeX">
                                                                <?php if($i == 1): ?>
                                                                <td class="cssToolTip" id="57" onClick="reply_click(this.id)"><?php echo $item['descripcion']; ?></td>
                                                                <?php $i++; else: ?>
                                                                <td class="cssToolTip" id="58" onClick="reply_click(this.id)"><?php echo $item['descripcion']; ?></td>
                                                                <?php endif; ?>
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
                                                        $dAControlPlaca[] = array('descripcion', 'Porcentaje');
                                                        $dAControlPlaca[] = array($item[0] . '', $item[0]);
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
                                        Ha asistido a consulta odontológica en los últimos seis meses
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip">descripcion
                                                                <span>Descripción Si/No</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">numerador
                                                                <span>Cantidad de persona que se han realizado/ no realizado control de placa ultimo semestre encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">denominador
                                                                <span>Total personas que se han realizado/ no realizado control de placa ultimo semestre encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaConsultaOdotologica">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaConsultaOdotologica) > 0):
                                                        $dAConsultaOdontologica[] = array('descripcion', 'Porcentaje');
                                                        $i=1;
                                                        foreach ($TablaConsultaOdotologica as $item):
                                                            $dAConsultaOdontologica[] = array($item[0], $item[3]);
                                                            ?>
                                                            <tr class="odd gradeX">
                                                                <?php if($i == 1): ?>
                                                                <td class="cssToolTip" id="59" onClick="reply_click(this.id)"><?php echo $item['descripcion']; ?></td>
                                                                <?php $i++; else: ?>
                                                                <td class="cssToolTip" id="60" onClick="reply_click(this.id)"><?php echo $item['descripcion']; ?></td>
                                                                <?php endif; ?>
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
                                                        $dAConsultaOdontologica[] = array('descripcion', 'Porcentaje');
                                                        $dAConsultaOdontologica[] = array($item[0] . '', $item[0]);
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
                                        Ha asistido a consulta odontológica en los últimos seis meses - Gráfico
                                    </div>                                
                                    <div class="panel-body">

                                        <div id="donutchartConsultaOdontologica" style="width: 100%; height: 50ex;"></div>

                                    </div>                                
                                </div>                            
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Cuentas veces se cepilla al día - Gráfico
                                    </div>                                
                                    <div class="panel-body">

                                        <div id="piechartCepillaDia" style="width: 100%; height: 50ex;"></div>

                                    </div>                                
                                </div>                            
                            </div>

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Cuentas veces se cepilla al día
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip" id="61" onClick="reply_click(this.id)">descripcion
                                                                <span>Número de cepilladas diarias</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">numerador
                                                                <span>Cantidad de personas que se cepillan determinado número de veces encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">denominador
                                                                <span>Total de personas que se cepillan encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaCepilladoDiario">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaCepilladoDiario) > 0):
                                                        $dACepillaDia[] = array('cepillado_diario', 'Porcentaje');
                                                        foreach ($TablaCepilladoDiario as $item):
                                                            $dACepillaDia[] = array($item[0], $item[3]);
                                                            ?>
                                                            <tr class="odd gradeX">
                                                                <td><?php echo $item['cepillado_diario']; ?></td>
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
                                                        $dACepillaDia[] = array('cepillado_diario', 'Porcentaje');
                                                        $dACepillaDia[] = array($item[0] . '', $item[0]);
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
                                        Ha presentado caries
                                    </div>
                                    
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="cssToolTip">descripcion
                                                                <span>Descripción Si/No</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">numerador
                                                                <span>Cantidad de personas que han presentado caries encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">denominador
                                                                <span>Total de personas que han presentado/ no presentado caries encuestados de {$client_name}</span>
                                                            </div>
                                                        </th>
                                                        <th>
                                                            <div class="cssToolTip">Porcentaje
                                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                                            </div>
                                                        </th>                                            
                                                    </tr>
                                                </thead>
                                                <tbody id="TablaCaries">
                                                    <?php
                                                    if (empty($_SESSION['user'])) {
                                                        ?>
                                                    <div class="alert alert-danger">
                                                        El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                    </div>
                                                    <?php
                                                } else {
                                                    if (count($TablaCaries) > 0):
                                                        $dACaries[] = array('descripcion', 'Porcentaje');
                                                        $i=1;
                                                        foreach ($TablaCaries as $item):
                                                            $dACaries[] = array($item[0], $item[3]);
                                                            ?>
                                                            <tr class="odd gradeX">
                                                                <?php if($i == 1): ?>
                                                                <td class="cssToolTip" id="62" onClick="reply_click(this.id)"><?php echo $item['descripcion']; ?></td>
                                                                <?php $i++; else: ?>
                                                                <td class="cssToolTip" id="63" onClick="reply_click(this.id)"><?php echo $item['descripcion']; ?></td>
                                                                <?php endif; ?>
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
                                                        $dACaries[] = array('descripcion', 'Porcentaje');
                                                        $dACaries[] = array($item[0] . '', $item[0]);
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
                                        Ha presentado caries - Gráfico
                                    </div>                                
                                    <div class="panel-body">

                                        <div id="donutchartCaries" style="width: 100%; height: 50ex;"></div>

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
                <!--Número de controles prenatales en menores de una año-->

                <script>
                    var chart = AmCharts.makeChart("piechartControlPrenatal", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"bottom",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "autoMargins":false
                      },
                      "innerRadius": "0%",
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
                      "dataProvider": <?= json_encode($TablaControlesPrenatalesUnAno, JSON_NUMERIC_CHECK) ?>,
                      "labelText" : "",
                      "valueField": "porcentaje",
                      "titleField": "control_medico",
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
                </script>

                <!--Afiliados por nivel de educación-->

                <script>
                    var chartNivelEdu = AmCharts.makeChart("piechartNivelEducacion", {
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
                      "dataProvider": <?= json_encode($TablaAfiliadosEducacion, JSON_NUMERIC_CHECK) ?>,
                      "labelText" : "",
                      "valueField": "porcentaje",
                      "titleField": "estudios",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartNivelEdu.addListener("init", handleInit);

                    chartNivelEdu.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartNivelEdu.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
                </script>

                <!--Número de niños que asisten al control de lenguaje, motor y de conducta-->

                <script>
                    var chartControlLMC = AmCharts.makeChart("piechartControlLenguaje", {
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
                      "dataProvider": <?= json_encode($TablaNinosLenguajeConducta, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-40",
                      "labelText" : "[[porcentaje]]%",
                      "valueField": "porcentaje",
                      "titleField": "detalle",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartControlLMC.addListener("init", handleInit);

                    chartControlLMC.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartControlLMC.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
                </script>
                <!--Número de niños que presentan problemas visual, auditivo de conducta-->

                <script>
                    var chartControlVis = AmCharts.makeChart("piechartControlVision", {
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
                      "dataProvider": <?= json_encode($TablaNinosProblemaVisualAudi, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-40",
                      "labelText" : "[[porcentaje]]%",
                      "valueField": "porcentaje",
                      "titleField": "detalle",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartControlVis.addListener("init", handleInit);

                    chartControlVis.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartControlVis.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
                </script>

                <!--Proveedor de refuerzo nutricional-->

                <script>
                    var chartRefNut = AmCharts.makeChart("piechartProveedorRefuerzo", {
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
                      "dataProvider": <?= json_encode($TablaProveedorRefuerzoNutrional, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-40",
                      "labelText" : "[[porcentaje]]%",
                      "valueField": "porcentaje",
                      "titleField": "proveedor_refuerzo",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartRefNut.addListener("init", handleInit);

                    chartRefNut.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartRefNut.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
                </script>

                <!--Ha realizado control de placa-->

                <script>
                    var chartConPla = AmCharts.makeChart("donutchartControlPlaca", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"bottom",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[descripcion]]<br><span style='font-size:14px'></span>",
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
                      "dataProvider": <?= json_encode($TablaControlPlaca, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-40",
                      "labelText" : "[[porcentaje]]%",
                      "valueField": "porcentaje",
                      "titleField": "descripcion",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartConPla.addListener("init", handleInit);

                    chartConPla.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartConPla.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
                </script>

                <!--Ha asistido a consulta odontológica en los últimos seis meses-->

                <script>
                    var chartConOdo = AmCharts.makeChart("donutchartConsultaOdontologica", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"bottom",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[descripcion]]<br><span style='font-size:14px'></span>",
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
                      "dataProvider": <?= json_encode($TablaConsultaOdotologica, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-40",
                      "labelText" : "[[porcentaje]]%",
                      "valueField": "porcentaje",
                      "titleField": "descripcion",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartConOdo.addListener("init", handleInit);

                    chartConOdo.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartConOdo.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
                </script>

                <!--Cuentas veces se cepilla al día-->

                <script>
                    var chartCepDia = AmCharts.makeChart("piechartCepillaDia", {
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
                      "dataProvider": <?= json_encode($TablaCepilladoDiario, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-40",
                      "labelText" : "",
                      "valueField": "porcentaje",
                      "titleField": "cepillado_diario",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartCepDia.addListener("init", handleInit);

                    chartCepDia.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartCepDia.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
                </script>

                <!--Ha presentado caries-->

                <script>
                    var chartCaries = AmCharts.makeChart("donutchartCaries", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"bottom",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[descripcion]]<br><span style='font-size:14px'></span>",
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
                      "dataProvider": <?= json_encode($TablaCaries, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-40",
                      "labelText" : "[[porcentaje]]%",
                      "valueField": "porcentaje",
                      "titleField": "descripcion",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartCaries.addListener("init", handleInit);

                    chartCaries.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartCaries.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
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