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
                    window.location = ("vivienda.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName + "&municipio=" + municipio+ "&muniName=" + muniName);
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
                        <h1 class="page-header">Informe de indicadores - Vivienda</h1>
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
                                    La vivienda es
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="86" onClick="reply_click(this.id)">Tipo de vivienda
                                                <span>Tipo de vivienda</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares por cada tipo de vivienda encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaViviendaEs">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaViviendaEs) > 0):
                                                    $dATipoVivienda[] = array('Tipo de vivienda', 'Porcentaje');
                                                    foreach ($TablaViviendaEs as $item):
                                                        $dATipoVivienda[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['tipo_casa_encuesta']; ?></td>
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
                                                    $dATipoVivienda[] = array('Tipo de vivienda', 'Porcentaje');
                                                    $dATipoVivienda[] = array($item[0] . '', $item[0]);
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
                                    La vivienda es - Gráfico
                                </div>
                                
                                <div class="panel-body">


                                    <div id="piechartVivienda" style="width: 100%; height: 50ex;"></div>


                                </div>
                                
                            </div>
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que servicios posee (Agua) - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartSerAgua" style="width: 100%; height: 40ex;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que servicios posee (Agua)
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>                                            
                                                    <th>
                                            <div class="cssToolTip" id="87" onClick="reply_click(this.id)">Pertenencia
                                                <span>pertenencia del servicio</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares que tiene/no tiene un servicio encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaServicioAgua">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaServicioAgua) > 0):
                                                    $dAServicioAgua[] = array('pertenencia', 'Porcentaje');
                                                    foreach ($TablaServicioAgua as $item):
                                                        $dAServicioAgua[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">        
                                                            <td><?php echo $item['pertenencia']; ?></td>
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
                                                    $dAServicioAgua[] = array('pertenencia', 'Porcentaje');
                                                    $dAServicioAgua[] = array($item[1] . '', $item[0]);
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
                                    Que servicios posee (Alcantarillado)
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>                                            
                                                    <th>
                                            <div class="cssToolTip" id="88" onClick="reply_click(this.id)">Pertenencia
                                                <span>pertenencia del servicio</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares que tiene/no tiene un servicio encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                             
                                            </tr>
                                            </thead>
                                            <tbody id="TablaServiciosAlcantarillado"> 
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaServiciosAlcantarillado) > 0):
                                                    $dAServicioAlcantarillado[] = array('pertenencia', 'Porcentaje');
                                                    foreach ($TablaServiciosAlcantarillado as $item):
                                                        $dAServicioAlcantarillado[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">        
                                                            <td><?php echo $item['pertenencia']; ?></td>
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
                                                    $dAServicioAlcantarillado[] = array('pertenencia', 'Porcentaje');
                                                    $dAServicioAlcantarillado[] = array($item[0] . '', $item[0]);
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
                                    Que servicios posee (Alcantarillado) - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartSerAlcantarillado" style="width: 100%; height: 40ex;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que servicios posee (Gas Natural) - Gráfico
                                </div>
                                
                                <div class="panel-body">


                                    <div id="donutchartSerGasNatural" style="width: 100%; height: 40ex;"></div>


                                </div>
                                
                            </div>
                            
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que servicios posee (Gas Natural)
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>                                            
                                                    <th>
                                            <div class="cssToolTip" id="89" onClick="reply_click(this.id)">Pertenencia
                                                <span>pertenencia del servicio</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares que tiene/no tiene un servicio encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                             
                                            </tr>
                                            </thead>
                                            <tbody id="TablaServiciosGasNatural">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaServiciosGasNatural) > 0):
                                                    $dAServicioGasNatural[] = array('pertenencia', 'Porcentaje');
                                                    foreach ($TablaServiciosGasNatural as $item):
                                                        $dAServicioGasNatural[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">        
                                                            <td><?php echo $item['pertenencia']; ?></td>
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
                                                    $dAServicioGasNatural[] = array('pertenencia', 'Porcentaje');
                                                    $dAServicioGasNatural[] = array($item[0] . '', $item[0]);
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
                                    Que servicios posee (Energía Eléctrica)
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr> 
                                                    <th>
                                            <div class="cssToolTip" id="90" onClick="reply_click(this.id)">Pertenencia
                                                <span>pertenencia del servicio</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares que tiene/no tiene un servicio encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                             
                                            </tr>
                                            </thead>
                                            <tbody id="TablaServiciosElectricidad">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaServiciosElectricidad) > 0):
                                                    $dAServicioEnergiaElectrica[] = array('pertenencia', 'Porcentaje');
                                                    foreach ($TablaServiciosElectricidad as $item):
                                                        $dAServicioEnergiaElectrica[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">   
                                                            <td><?php echo $item['pertenencia']; ?></td>
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
                                                    $dAServicioEnergiaElectrica[] = array('pertenencia', 'Porcentaje');
                                                    $dAServicioEnergiaElectrica[] = array($item[0] . '', $item[0]);
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
                                    Que servicios posee (Energía Eléctrica) - Gráfico
                                </div>
                                
                                <div class="panel-body">


                                    <div id="donutchartEnergiaElectrica" style="width: 100%; height: 40ex;"></div>


                                </div>
                                
                            </div>
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que servicios posee (Ducha) - Gráfico
                                </div>
                                
                                <div class="panel-body">


                                    <div id="donutchartDucha" style="width: 100%; height: 40ex;"></div>


                                </div>
                                
                            </div>
                            
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    La vivienda posee (Ducha)
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>                                            
                                                    <th>
                                            <div class="cssToolTip" id="91" onClick="reply_click(this.id)">Pertenencia
                                                <span>pertenencia del servicio</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares que tiene/no tiene un servicio encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaServiciosDucha">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaServiciosDucha) > 0):
                                                    $dAServicioDucha[] = array('pertenencia', 'Porcentaje');
                                                    foreach ($TablaServiciosDucha as $item):
                                                        $dAServicioDucha[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['pertenencia']; ?></td>
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
                                                    $dAServicioDucha[] = array('pertenencia', 'Porcentaje');
                                                    $dAServicioDucha[] = array($item[0] . '', $item[0]);
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
                                    La vivienda posee (Letrina)
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="92" onClick="reply_click(this.id)">Pertenencia
                                                <span>pertenencia del servicio</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares que tiene/no tiene un servicio encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                             
                                            </tr>
                                            </thead>
                                            <tbody id="TablaCasaLetrina">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaCasaLetrina) > 0):
                                                    $dAServicioLetrina[] = array('pertenencia', 'Porcentaje');
                                                    foreach ($TablaCasaLetrina as $item):
                                                        $dAServicioLetrina[] = array($item[1] . '', $item[4]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['pertenencia']; ?></td>
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
                                                    $dAServicioLetrina[] = array('pertenencia', 'Porcentaje');
                                                    $dAServicioLetrina[] = array($item[0] . '', $item[0]);
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
                                    Que servicios posee (Letrina) - Gráfico
                                </div>
                                
                                <div class="panel-body">


                                    <div id="donutchartLetrina" style="width: 100%; height: 40ex;"></div>


                                </div>
                                
                            </div>
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que servicios posee (Cocina dentro del dormitorio) - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartCocina" style="width: 100%; height: 40ex;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    La vivienda posee (Cocina dentro del dormitorio)
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="93" onClick="reply_click(this.id)">Pertenencia
                                                <span>pertenencia del servicio</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares que tiene/no tiene un servicio encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                           
                                            </tr>
                                            </thead>
                                            <tbody id="TablaCocinaDormitorio">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaCocinaDormitorio) > 0):
                                                    $dAServicioCocina[] = array('pertenencia', 'Porcentaje');
                                                    foreach ($TablaCocinaDormitorio as $item):
                                                        $dAServicioCocina[] = array($item[1] . '', $item[4]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['pertenencia']; ?></td>
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
                                                    $dAServicioCocina[] = array('pertenencia', 'Porcentaje');
                                                    $dAServicioCocina[] = array($item[0] . '', $item[0]);
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
                                    Cuantos dormitorios posee
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="94" onClick="reply_click(this.id)">Dormitorios
                                                <span>Numero de dormitorios</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total dormitorios por cada grupo familiar encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaNumDormitorios">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaNumDormitorios) > 0):
                                                    $dADormitorios[] = array('dormitorios', 'Porcentaje');
                                                    foreach ($TablaNumDormitorios as $item):
                                                        $dADormitorios[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['dormitorios']; ?></td>
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
                                                    $dADormitorios[] = array('dormitorios', 'Porcentaje');
                                                    $dADormitorios[] = array($item[0] . '', $item[0]);
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
                                    Cuantos dormitorios posee - Gráfico
                                </div>
                                
                                <div class="panel-body">


                                    <div id="chart_div" style="width: 100%; height: 40ex;"></div>


                                </div>
                                
                            </div>
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Presencia de animales - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartAnimales" style="width: 100%; height: 40ex;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Presencia de animales
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="95" onClick="reply_click(this.id)">Pertenencia
                                                <span>pertenencia del indicador</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares que tiene/no tiene animales en la casa, encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaAnimales">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaAnimales) > 0):
                                                    $dAAnimales[] = array('pertenencia', 'Porcentaje');
                                                    foreach ($TablaAnimales as $item):
                                                        $dAAnimales[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['pertenencia']; ?></td>
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
                                                    $dAAnimales[] = array('pertenencia', 'Porcentaje');
                                                    $dAAnimales[] = array($item[0] . '', $item[0]);
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
                                    Tiene piso en tierra
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="96" onClick="reply_click(this.id)">Pertenencia
                                                <span>pertenencia del indicador</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares que tiene/no tiene un servicio encuestados de {$client_name}/span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaPisoTierra">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaPisoTierra) > 0):
                                                    $dATierra[] = array('pertenencia', 'Porcentaje');
                                                    foreach ($TablaPisoTierra as $item):
                                                        $dATierra[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['pertenencia']; ?></td>
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
                                                    $dATierra[] = array('pertenencia', 'Porcentaje');
                                                    $dATierra[] = array($item[0] . '', $item[0]);
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
                                    Tiene piso en tierra - Gráfico
                                </div>
                                
                                <div class="panel-body">


                                    <div id="donutchartTierra" style="width: 100%; height: 40ex;"></div>


                                </div>
                                
                            </div>
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que mascota posee - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="piechartMascota" style="width: 100%; height: 40ex;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que mascota posee
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="97" onClick="reply_click(this.id)">Mascota
                                                <span>Tipos de mascotas</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares que tiene/no tiene mascotas, encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaPoseeMascota">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaPoseeMascota) > 0):
                                                    $dAMascota[] = array('mascotas', 'Porcentaje');
                                                    foreach ($TablaPoseeMascota as $item):
                                                        $dAMascota[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['mascotas']; ?></td>
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
                                                    $dAMascota[] = array('mascotas', 'Porcentaje');
                                                    $dAMascota[] = array($item[0] . '', $item[0]);
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
                                    El agua que consume es
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="98" onClick="reply_click(this.id)">Estado del agua
                                                <span>Tipo de agua de consumo</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares por cada tipo de consumo de agua encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                             
                                            </tr>
                                            </thead>
                                            <tbody id="TablaAguaConsume">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaAguaConsume) > 0):
                                                    $dAEstadoAgua[] = array('estado_agua', 'Porcentaje');
                                                    foreach ($TablaAguaConsume as $item):
                                                        $dAEstadoAgua[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['estado_agua']; ?></td>
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
                                                    $dAEstadoAgua[] = array('estado_agua', 'Porcentaje');
                                                    $dAEstadoAgua[] = array($item[0] . '', $item[0]);
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
                                    El agua que consume es - Gráfico
                                </div>
                                
                                <div class="panel-body">


                                    <div id="piechartEstadoAgua" style="width: 100%; height: 40ex;"></div>


                                </div>
                                
                            </div>
                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Tratamiento de la basura - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="chartBasuras" style="width: 100%; height: 40ex;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Tratamiento de la basura
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="99" onClick="reply_click(this.id)">Tratamiento de basuras
                                                <span>Tratamiento de basuras</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares por cada tipo tratamiento de basura encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                          
                                            </tr>
                                            </thead>
                                            <tbody id="TablaTratamientoBasura">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaTratamientoBasura) > 0):
                                                    $dABasuras[] = array('tratamiento_basuras', 'Porcentaje');
                                                    foreach ($TablaTratamientoBasura as $item):
                                                        $dABasuras[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['tratamiento_basuras']; ?></td>
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
                                                    $dABasuras[] = array('tratamiento_basuras', 'Porcentaje');
                                                    $dABasuras[] = array($item[0] . '', $item[0]);
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
                                    Estado del techo
                                </div>

                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="100" onClick="reply_click(this.id)">Estado del techo
                                                <span>Estado del techo de la vivienda</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares por cada tipo estado del techo encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                             
                                            </tr>
                                            </thead>
                                            <tbody id="TablaEstadoTecho">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaEstadoTecho) > 0):
                                                    $dATecho[] = array('estado_techo', 'Porcentaje');
                                                    foreach ($TablaEstadoTecho as $item):
                                                        $dATecho[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['estado_techo']; ?></td>
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
                                                    $dATecho[] = array('estado_techo', 'Porcentaje');
                                                    $dATecho[] = array($item[0] . '', $item[0]);
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
                                    Estado del techo - Gráfico
                                </div>

                                <div class="panel-body">


                                    <div id="donutchartTecho" style="width: 100%; height: 40ex;"></div>


                                </div>                                
                            </div>                            
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Estado de las paredes - Gráfico
                                </div>

                                <div class="panel-body">
                                    <div id="donutchartParedes" style="width: 100%; height: 40ex;"></div>
                                </div>                                
                            </div>                            
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Estado de las paredes
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="101" onClick="reply_click(this.id)">Estado de las paredes
                                                <span>Estado de las paredes de la vivienda</span>
                                            </div> 
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares por cada tipo estado de las paredes encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaEstadoParedes">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaEstadoParedes) > 0):
                                                    $dAParedes[] = array('estado_paredes', 'Porcentaje');
                                                    foreach ($TablaEstadoParedes as $item):
                                                        $dAParedes[] = array($item[0] . '', $item[3]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['estado_paredes']; ?></td>
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
                                                    $dAParedes[] = array('estado_paredes', 'Porcentaje');
                                                    $dAParedes[] = array($item[0] . '', $item[0]);
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
                                    Hay suficiente luz
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="102" onClick="reply_click(this.id)">Pertenencia
                                                <span>pertenencia del indicador</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares que tiene/no tiene un servicio encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                             
                                            </tr>
                                            </thead>
                                            <tbody id="TablaSuficienteLuz">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaSuficienteLuz) > 0):
                                                    foreach ($TablaSuficienteLuz as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['pertenencia']; ?></td>
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
                                    Hay precencia de aguas negras
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="103" onClick="reply_click(this.id)">Pertenencia
                                                <span>pertenencia del indicador</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares que tiene/no tiene un servicio encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                           
                                            </tr>
                                            </thead>
                                            <tbody id="TablaAguasNegras">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaAguasNegras) > 0):
                                                    foreach ($TablaAguasNegras as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['pertenencia']; ?></td>
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
                                    Hay precencia de roedores
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="104" onClick="reply_click(this.id)">Pertenencia
                                                <span>pertenencia del indicador</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares que tiene/no tiene un servicio encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                           
                                            </tr>
                                            </thead>
                                            <tbody id="TablaRoedores">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaRoedores) > 0):
                                                    foreach ($TablaRoedores as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['pertenencia']; ?></td>
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
                        <!-- /.col-lg-6 -->
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Materiales predominantes de las paredes
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="105" onClick="reply_click(this.id)">Material paredes
                                                <span>Materiales de las paredes</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares por cada tipo del estado de las paredes encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaMaterialParedes">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaMaterialParedes) > 0):
                                                    foreach ($TablaMaterialParedes as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['material_paredes']; ?></td>
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
                        <!-- /.col-lg-6 -->
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Materiales predominantes de los pisos
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="106" onClick="reply_click(this.id)">Material pisos
                                                <span>Materiales de los pisos</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares por cada tipo del estado de los pisos, encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                              
                                            </tr>
                                            </thead>
                                            <tbody id="TablaMaterialPiso">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaMaterialPiso) > 0):
                                                    foreach ($TablaMaterialPiso as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['material_pisos']; ?></td>
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
                        <!-- /.col-lg-6 -->
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Materiales predominantes del techo
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="107" onClick="reply_click(this.id)">Materiales techo
                                                <span>Materiales del techo</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares por cada tipo del estado del techo, encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                              
                                            </tr>
                                            </thead>
                                            <tbody id="TablaMaterialTecho">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaMaterialTecho) > 0):
                                                    foreach ($TablaMaterialTecho as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['materiales_techo']; ?></td>
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
                        <!-- /.col-lg-6 -->
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que tipo de alumbrado utiliza
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="108" onClick="reply_click(this.id)">Alumbrado
                                                <span>Tipo de alumbrado utilizado</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total grupos familiares por cada tipo del estado del techo, encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de viviendas encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de casas encuestadas sobre el indicador</span>
                                            </div>
                                            </th>                                           
                                            </tr>
                                            </thead>
                                            <tbody id="TablaAlumbrado">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaAlumbrado) > 0):
                                                    foreach ($TablaAlumbrado as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['alumbrado']; ?></td>
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
                                    Promedio de Grupos Familiares por Vivienda
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>                                                    
                                                    <th>
                                            <div class="cssToolTip">Promedio
                                                <span>Promedio De Grupos Familiares Por Vivienda</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaAlumbrado">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaspGruposFamiliaresVivienda) > 0):
                                                    foreach ($TablaspGruposFamiliaresVivienda as $item):
                                                        ?>
                                                        <tr class="odd gradeX">                                                            
                                                            <td><?php echo $item['promedio']; ?></td>                                                            
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
                                    Promedio de Personas por Vivienda
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>                                                    
                                                    <th>
                                            <div class="cssToolTip">Promedio
                                                <span>Promedio De Personas Por Vivienda</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaAlumbrado">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaPersonasVivienda) > 0):
                                                    foreach ($TablaPersonasVivienda as $item):
                                                        ?>
                                                        <tr class="odd gradeX">                                                            
                                                            <td><?php echo $item['promedio']; ?></td>                                                            
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
                                    Personas por viviendad Disgregado
                                </div>
                                
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Num. de Personas</th>
                                                    <th>Cantidad de casas</th>                                                    
                                                </tr>
                                            </thead>
                                            <tbody id="TablaAlumbrado">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaPersonasViviendaSeparado) > 0):
                                                    foreach ($TablaPersonasViviendaSeparado as $item):
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['promedio']; ?></td>
                                                            <td><?php echo $item['detalle']; ?></td>                                                            
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
            

            <?php include("../../../sitemedia/html/scriptpie.php"); ?>

            <!--        
            *****************************************************************************
            Graficos de la página
            *****************************************************************************            
            -->

           <!--Grafico de "La vivienda es"-->
            <script>
                var chartVivTip = AmCharts.makeChart("piechartVivienda", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"bottom",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[porcentaje]]<br><span style='font-size:14px'></span>",
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
                      "dataProvider": <?= json_encode($TablaViviendaEs, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-40",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "tipo_casa_encuesta",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartVivTip.addListener("init", handleInit);

                    chartVivTip.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartVivTip.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
            
            <!--Que servicios posee (Agua)"-->

            <script>
                var chartSerAgua = AmCharts.makeChart("donutchartSerAgua", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[pertenencia]]<br><span style='font-size:14px'></span>",
                        "autoMargins":false
                      },
                      "innerRadius": "30%",
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
                      "dataProvider": <?= json_encode($TablaServicioAgua, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-30",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "pertenencia",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartSerAgua.addListener("init", handleInit);

                    chartSerAgua.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartSerAgua.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
            
            <!--Que servicios posee (Alcantarillado)"-->
            <script>
                var chartSerAlc = AmCharts.makeChart("donutchartSerAlcantarillado", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[pertenencia]]<br><span style='font-size:14px'></span>",
                        "autoMargins":false
                      },
                      "innerRadius": "30%",
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
                      "dataProvider": <?= json_encode($TablaServiciosAlcantarillado, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-30",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "pertenencia",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartSerAlc.addListener("init", handleInit);

                    chartSerAlc.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartSerAlc.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
            
            <!--Que servicios posee (Gas Natural)"-->

            <script>
                var chartSerGas = AmCharts.makeChart("donutchartSerGasNatural", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[pertenencia]]<br><span style='font-size:14px'></span>",
                        "autoMargins":false
                      },
                      "innerRadius": "30%",
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
                      "dataProvider": <?= json_encode($TablaServiciosGasNatural, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-30",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "pertenencia",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartSerGas.addListener("init", handleInit);

                    chartSerGas.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartSerGas.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
            
            <!--Que servicios posee (Energía Eléctrica)"-->

            <script>
                var chartSerEE = AmCharts.makeChart("donutchartEnergiaElectrica", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[pertenencia]]<br><span style='font-size:14px'></span>",
                        "autoMargins":false
                      },
                      "innerRadius": "30%",
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
                      "dataProvider": <?= json_encode($TablaServiciosElectricidad, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-30",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "pertenencia",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartSerEE.addListener("init", handleInit);

                    chartSerEE.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartSerEE.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
            
            <!--Que servicios posee (Ducha)"-->

            <script>
                var chartSerDucha = AmCharts.makeChart("donutchartDucha", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[pertenencia]]<br><span style='font-size:14px'></span>",
                        "autoMargins":false
                      },
                      "innerRadius": "30%",
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
                      "dataProvider": <?= json_encode($TablaServiciosDucha, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-30",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "pertenencia",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartSerDucha.addListener("init", handleInit);

                    chartSerDucha.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartSerDucha.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
           
            <!--Que servicios posee (Letrina)"-->

            <script>
                var chartSerLetrina = AmCharts.makeChart("donutchartLetrina", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[pertenencia]]<br><span style='font-size:14px'></span>",
                        "autoMargins":false
                      },
                      "innerRadius": "30%",
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
                      "dataProvider": <?= json_encode($TablaCasaLetrina, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-30",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "pertenencia",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartSerLetrina.addListener("init", handleInit);

                    chartSerLetrina.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartSerLetrina.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
            
            <!--Que servicios posee (Cocina dentro del dormitorio)"-->

            <script>
                var chartSerCodDenDor = AmCharts.makeChart("donutchartCocina", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[pertenencia]]<br><span style='font-size:14px'></span>",
                        "autoMargins":false
                      },
                      "innerRadius": "30%",
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
                      "dataProvider": <?= json_encode($TablaCocinaDormitorio, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-30",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "pertenencia",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartSerCodDenDor.addListener("init", handleInit);

                    chartSerCodDenDor.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartSerCodDenDor.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
          
            <!--Cuantos dormitorios posee-->           
            <script>
                var chartDorPos = AmCharts.makeChart("chart_div", {
                "type": "serial",
                 "theme": "light",
                "categoryField": "dormitorios",
                "rotate": true,
                "startDuration": 1,
                "categoryAxis": {
                    "gridPosition": "start",
                    "position": "left"
                },
                "trendLines": [],
                "graphs": [
                    {
                        "balloonText": "Total:[[porcentaje]]",
                        "fillAlphas": 0.8,
                        "id": "AmGraph-1",
                        "lineAlpha": 0.2,
                        "title": "porcentaje",
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
                "dataProvider":<?= json_encode($TablaNumDormitorios, JSON_NUMERIC_CHECK) ?>,
                "export": {
                    "enabled": true
                 }
            });
            </script>
            
            <!--Presencia de animales-->

            <script>
                var chartAnimales = AmCharts.makeChart("donutchartAnimales", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[pertenencia]]<br><span style='font-size:14px'></span>",
                        "autoMargins":false
                      },
                      "innerRadius": "30%",
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
                      "dataProvider": <?= json_encode($TablaAnimales, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-30",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "pertenencia",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartAnimales.addListener("init", handleInit);

                    chartAnimales.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartAnimales.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
           
            <!--Tiene piso en tierra-->

            <script>
                var chartTierra = AmCharts.makeChart("donutchartTierra", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[pertenencia]]<br><span style='font-size:14px'></span>",
                        "autoMargins":false
                      },
                      "innerRadius": "30%",
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
                      "dataProvider": <?= json_encode($TablaPisoTierra, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-30",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "pertenencia",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartTierra.addListener("init", handleInit);

                    chartTierra.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartTierra.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
            
            <!--Que mascota posee-->

            <script>
                var chartMascota = AmCharts.makeChart("piechartMascota", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
                        "marginRight":100,
                        "markerType" : "circle",
                        "align" : "center",
                        "balloonText" : "[[mascotas]]<br><span style='font-size:14px'></span>",
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
                      "dataProvider": <?= json_encode($TablaPoseeMascota, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-30",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "mascotas",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartMascota.addListener("init", handleInit);

                    chartMascota.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartMascota.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
            

            <!--El agua que consume es-->

            <script>
                var chartEstadoAgua = AmCharts.makeChart("piechartEstadoAgua", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
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
                      "dataProvider": <?= json_encode($TablaAguaConsume, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-30",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "estado_agua",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartEstadoAgua.addListener("init", handleInit);

                    chartEstadoAgua.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartEstadoAgua.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
           

            <!--Tratamiento de la basura-->           

            <script>
                var chartBasura = AmCharts.makeChart("chartBasuras", {
                "type": "serial",
                 "theme": "light",
                "categoryField": "tratamiento_basuras",
                "rotate": true,
                "startDuration": 1,
                "categoryAxis": {
                    "gridPosition": "start",
                    "position": "left"
                },
                "trendLines": [],
                "graphs": [
                    {
                        "balloonText": "Total:[[porcentaje]]",
                        "fillAlphas": 0.8,
                        "id": "AmGraph-1",
                        "lineAlpha": 0.2,
                        "title": "porcentaje",
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
                "dataProvider":<?= json_encode($TablaTratamientoBasura, JSON_NUMERIC_CHECK) ?>,
                "export": {
                    "enabled": true
                 }
            });
            </script>
            

            <!--Estado del techo-->

            <script>
                var chartEstadoTecho = AmCharts.makeChart("donutchartTecho", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
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
                      "dataProvider": <?= json_encode($TablaEstadoTecho, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-30",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "estado_techo",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartEstadoTecho.addListener("init", handleInit);

                    chartEstadoTecho.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartEstadoTecho.legend.addListener("rollOverItem", handleRollOver);
                    }

                    function handleRollOver(e){
                      var wedge = e.dataItem.wedge.node;
                      wedge.parentNode.appendChild(wedge);  
                    }
            </script>
            
            <!--Estado de las paredes-->

            <script>
                var chartEstadoPared = AmCharts.makeChart("donutchartParedes", {
                      "type": "pie",
                      "startDuration": 1,
                       "theme": "light",
                      "addClassNames": true,
                      "legend":{
                        "position":"right",
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
                      "dataProvider": <?= json_encode($TablaEstadoParedes, JSON_NUMERIC_CHECK) ?>,
                      "labelRadius" : "-30",
                      "labelText" : "[[porcentaje]]",
                      "valueField": "porcentaje",
                      "titleField": "estado_paredes",
                      "export": {
                        "enabled": true
                      }
                    });

                    chartEstadoPared.addListener("init", handleInit);

                    chartEstadoPared.addListener("rollOverSlice", function(e) {
                      handleRollOver(e);
                    });

                    function handleInit(){
                      chartEstadoPared.legend.addListener("rollOverItem", handleRollOver);
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
