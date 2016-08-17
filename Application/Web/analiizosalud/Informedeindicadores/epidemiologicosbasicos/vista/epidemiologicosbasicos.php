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
                $.post("municpiosencuestas.php", {
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
                    window.location = ("epidemiologicosbasicos.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName + "&municipio=" + municipio+ "&muniName=" + muniName);
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
                        <h1 class="page-header">Informe de indicadores - Epidemiológicos Básicos</h1>
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
                                    Total personas por género
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip">Género
                                                <span>Género de los encuestados</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Total
                                                <span>Total de los encuestados por género</span>
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
                                                if (count($TablaPersonaGenero) > 0):
                                                    foreach ($TablaPersonaGenero as $item):
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
                                    Total personas por género - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="donutchartGenero" style="width: 500px; height: 300px;"></div>
                                </div>                                
                            </div>                            
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Total por Genero y edad
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>                                                    
                                                    <th>
                                            <div class="cssToolTip">Rango Edad
                                                <span>Grupos etareos</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Cantidad Masculino
                                                <span>Cantidad de encuestados de sexo masculino</span>
                                            </div>
                                            </th>
                                            <th> 
                                            <div class="cssToolTip">Cantidad Femenino
                                                <span>Cantidad de encuestados de sexo femenino</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaGeneroEdad">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaGeneroEdad) > 0):
                                                    for ($i=count($TablaGeneroEdad)-1; $i > 0; $i--) { 
                                                        $dAPiramidePoblacional[] = json_decode(
                                                            "{".
                                                            "\"rangoedad\":\"".$TablaGeneroEdad[$i]['rangoedad']."\",".
                                                            "\"masculino\":".$TablaGeneroEdad[$i]['masculino'].",".
                                                            "\"femenino\":".($TablaGeneroEdad[$i]['femenino'] * -1).
                                                            "}");
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $TablaGeneroEdad[$i]['rangoedad']; ?></td>
                                                            <td><?php echo $TablaGeneroEdad[$i]['masculino']; ?></td>  
                                                            <td><?php echo $TablaGeneroEdad[$i]['femenino']; ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                else:
                                                    $dAPiramidePoblacional[] = json_decode(
                                                            "{".
                                                            "\"rangoedad\":\"".$item[0]."\",".
                                                            "\"masculino\":".$item[0].",".
                                                            "\"femenino\":".($item[0] * -1).
                                                            "}");
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
                                    Piramide poblacional
                                </div>
                                <div class="panel-body">
                                    <div id="chart_div" style="width: 500px; height: 600px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Porcentaje de la población por municipio que pertenece a {$client_name}
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip">Nombre municipio
                                                            <span>Municipio encuestado</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Afiliado municipio
                                                            <span>Total encuestados afiliados al municipio</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Total poblacion
                                                            <span>Total población del municipio</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip">Porcentaje
                                                            <span>Porcentaje de afiliados con relación al total de la población</span>
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
                                                if (count($TablaPoblacionTownEps) > 0):
                                                    // matriz para rellenar títulos
                                                    $dAPoblacionPertenece[] = array('nombremunicipio', 'afiliadomunicipio', 'totalpoblacion', 'porcentaje');
                                                    foreach ($TablaPoblacionTownEps as $item):
                                                        // matriz para rellenar los datos
                                                        $TablaPoblacionTownEps[] = array($item[1], $item[2], $item[3], $item[4]);
                                                        ?>                                  
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['nombremunicipio']; ?></td>
                                                            <td><?php echo $item['afiliadomunicipio']; ?></td>        
                                                            <td><?php echo $item['totalpoblacion']; ?></td>        
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                echo $num;
                                                                ?></td>
                                                        </tr>
                                                        <?php
                                                    endforeach;
                                                else:
                                                    $dAPoblacionPertenece[] = array('nombremunicipio', 'afiliadomunicipio', 'totalpoblacion', 'porcentaje');
                                                    $dAPoblacionPertenece[] = array($item[0], $item[0], $item[0], $item[0]);
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
                                    Razón niños / mujer
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='1' onClick="reply_click(this.id)">numerador
                                                <span>Total niños de 0 a 4 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='2' onClick="reply_click(this.id)">denominador
                                                <span>Total mujeres de 15 a 49 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Razón
                                                <span>Porcentaje del (numerador entre el denominador) x 100 </span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TableChildWoman">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaChildWoman) > 0):
                                                    foreach ($TablaChildWoman as $item):
                                                        ?>                                  
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>              
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".");
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
                                    Índice de infancia
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='3' onClick="reply_click(this.id)">numerador
                                                <span>Total niños de 0 a 13 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total personas encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Indice
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaIndiceInfancia">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaIndiceInfancia) > 0):
                                                    foreach ($TablaIndiceInfancia as $item):
                                                        ?>                                  
                                                        <tr class="odd gradeX">

                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>              
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".");
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
                                    Índice de juventud
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='4' onClick="reply_click(this.id)">numerador
                                                <span>Total personas de 15 a 29 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total personas encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Indice
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaIndiceJuventud">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaIndiceJuventud) > 0):
                                                    foreach ($TablaIndiceJuventud as $item):
                                                        ?>                                  
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>              
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".");
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
                                    |
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Índice de vejez
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>

                                                    <th>
                                            <div class="cssToolTip" id='5' onClick="reply_click(this.id)">numerador
                                                <span>Total personas mayores de 65 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total personas encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Indice
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                            </div>
                                            </th>                                            

                                            </tr>
                                            </thead>
                                            <tbody id="TablaIndiceVejez">

                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaIndiceVejez) > 0):
                                                    foreach ($TablaIndiceVejez as $item):
                                                        ?>
                                                        <tr class="odd gradeX">

                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>              
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".");
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
                                    Índice de envejecimiento
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='5' onClick="reply_click(this.id)">numerador
                                                <span>Total personas mayores de 65 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='6' onClick="reply_click(this.id)">denominador
                                                <span>Total niños de 0 a 14 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Indice
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaIndiceEnvejecimiento">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaIndiceEnvejecimiento) > 0):
                                                    foreach ($TablaIndiceEnvejecimiento as $item):
                                                        ?>                                  
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>              
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".");
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
                                    Índice de dependencia
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='7' onClick="reply_click(this.id)">numerador
                                                <span>Total personas de 0 a 14 años y mayores de 65 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='8' onClick="reply_click(this.id)">denominador
                                                <span>Total personas de 15 a 64 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Indice
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaIndiceDependencia">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>

                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaIndiceDependencia) > 0):
                                                    foreach ($TablaIndiceDependencia as $item):
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
                                    Índice de dependencia del adulto mayor
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='5' onClick="reply_click(this.id)">numerador
                                                <span>Total personas mayores de 65 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='8' onClick="reply_click(this.id)">denominador
                                                <span>Total personas de 15 a 64 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Indice
                                                <span>Porcentaje del (numerador entre el denominador) x 100 </span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaIndiceDependAM">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaIndiceDependAM) > 0):
                                                    foreach ($TablaIndiceDependAM as $item):
                                                        ?>                                  
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>              
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".");
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
                                    Índice de Friz
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='9' onClick="reply_click(this.id)">numerador
                                                <span>Total personas de 0 a 19 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='10' onClick="reply_click(this.id)">denominador
                                                <span>Total personas de 30 a 49 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Indice
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaIndiceFriz">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>

                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaIndiceFriz) > 0):
                                                    foreach ($TablaIndiceFriz as $item):
                                                        ?>                                  
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['numerador']; ?></td>
                                                            <td><?php echo $item['denominador']; ?></td>              
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".");
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
                                    Número de personas en condición de discapacidad
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='11' onClick="reply_click(this.id)">Numero personas con discapacidad
                                                <span>Total personas con discapacidad encuestados de {$client_name}</span>
                                            </div>
                                            </th>                                                                                 
                                            </tr>
                                            </thead>
                                            <tbody id="TablaPersonasDiscapacidad">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>

                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaPersonasDiscapacidad) > 0):
                                                    foreach ($TablaPersonasDiscapacidad as $item):
                                                        ?>                                  
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['totalpersonas']; ?></td>        
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
                                    Tasa bruta de natalidad por año
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='12' onClick="reply_click(this.id)">numerador
                                                <span>Total niños menores de 1 año encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total personas encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Tasa
                                                <span>Porcentaje del (numerador entre el denominador) x 100 </span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaNatalidadAno">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>

                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaNatalidadAno) > 0):
                                                    foreach ($TablaNatalidadAno as $item):
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
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Tasa General de Fecundidad  por año
                                </div>

                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='12' onClick="reply_click(this.id)">numerador
                                                <span>Total niños menores de 1 año encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='2' onClick="reply_click(this.id)">denominador
                                                <span>Total mujeres entre 15 y 49 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Tasa
                                                <span>Porcentaje del (numerador entre el denominador) x 100 </span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaFecundidadAno">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>

                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaFecundidadAno) > 0):
                                                    foreach ($TablaFecundidadAno as $item):
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
                                    |
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Tasa de fecundidad en mujeres de 10 a 14 años
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='13' onClick="reply_click(this.id)">numerador
                                                <span>Total número de hijos de mujeres entre 10 y 14 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='14' onClick="reply_click(this.id)">denominador
                                                <span>Total mujeres entre 10 y 14 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th><div class="cssToolTip">Tasa
                                                <span>Porcentaje del (numerador entre el denominador) x 100 </span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaFecundidadMuj">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {


                                                if (count($TablaFecundidadMuj10a14) > 0):
                                                    foreach ($TablaFecundidadMuj10a14 as $item):
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
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Tasa de fecundidad en mujeres entre 15 y 19 años
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='15' onClick="reply_click(this.id)">numerador
                                                <span>Total número de hijos de mujeres entre 15 y 19 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip" id='16' onClick="reply_click(this.id)">denominador
                                                <span>Total mujeres entre 15 y 19 años encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Tasa
                                                <span>Porcentaje del (numerador entre el denominador) x 100</span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaFecundidadMuj15a19">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>

                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaFecundidadMuj15a19) > 0):
                                                    foreach ($TablaFecundidadMuj15a19 as $item):
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
                                    Porcentaje de afiliados que declaran ser desplazados
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='17' onClick="reply_click(this.id)">numerador
                                                <span>Total personas desplazadas encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total personas encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje del (numerador entre el denominador) x 100 </span>
                                            </div>
                                            </th>                                            
                                            </tr>
                                            </thead>
                                            <tbody id="TablaAfiliadosDesplazados">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>

                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaAfiliadosDesplazados) > 0):
                                                    foreach ($TablaAfiliadosDesplazados as $item):
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
                                    |
                                </div>
                                
                            </div>
                        </div>
                    </div>               

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Número de personas afiliadas por pertenencia étnica
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='18' onClick="reply_click(this.id)">Raza
                                                <span>Nombre de la raza</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Cantidad
                                                <span>Cantidad personas pertenecientes a la raza encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">Porcentaje
                                                <span>Porcentaje de afiliados que pertenecen a una raza</span>
                                            </div>
                                            </th>                                             
                                            </tr>
                                            </thead>
                                            <tbody id="TablaPertenenciaEtnica">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaPertenenciaEtnica) > 0):
                                                    foreach ($TablaPertenenciaEtnica as $item):
                                                        ?>                                  
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['raza']; ?></td>
                                                            <td><?php echo $item['cantidad']; ?></td>
                                                            <td><?php
                                                                $num = number_format($item['porcentaje'], 2, ",", ".") . " %";
                                                                echo $num;
                                                                ?></td>     
                                                        </tr>     
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
                                    Número de personas afiliadas por pertenencia étnica - Gráfico
                                </div>
                                <div class="panel-body">
                                    <div id="piechartRaza" style="width: 500px; height: 300px;"></div>
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

        <!--Total personas por género-->
        <script>
            var chartPerGen = AmCharts.makeChart("donutchartGenero", {
                "type": "pie",
                "startDuration": 1,
                "theme": "light",
                "addClassNames": true,
                "legend":{
                "position":"bottom",
                    "marginRight":100,
                    "markerType" : "circle",
                    "align" : "center",
                    "balloonText" : "[[genero]]<br><span style='font-size:14px'><b>[[total]]</b> ([[percents]]%)</span>",
                    "autoMargins":false
                  },
                  "innerRadius": "40%",
                  "gradientRatio": [-0.4, -0.4, -0.4, -0.4, -0.4, -0.4, 0, 0.1, 0.2, 0.1, 0, -0.2, -0.5],
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
                  "dataProvider": <?= json_encode($TablaPersonaGenero, JSON_NUMERIC_CHECK) ?>,
                  "labelRadius" : "3",
                  "labelText" : "[[genero]] : [[total]]",
                  "valueField": "total",
                  "titleField": "genero",
                  "export": {
                    "enabled": true
                  }
                });

                chartPerGen.addListener("init", handleInit);

                chartPerGen.addListener("rollOverSlice", function(e) {
                  handleRollOver(e);
                });

                function handleInit(){
                  chartPerGen.legend.addListener("rollOverItem", handleRollOver);
                }

                function handleRollOver(e){
                  var wedge = e.dataItem.wedge.node;
                  wedge.parentNode.appendChild(wedge);  
                }
        </script>

        <!--Piramide Poblacional--> 
        <script>
            var chart = AmCharts.makeChart("chart_div", {
              "type": "serial",
              "theme": "light",
              "rotate": true,
              "marginBottom": 50,
              "dataProvider": <?= json_encode($dAPiramidePoblacional, JSON_NUMERIC_CHECK) ?>,
              "startDuration": 1,
              "graphs": [{
                "fillAlphas": 0.8,
                "lineAlpha": 0.2,
                "type": "column",
                "valueField": "masculino",
                "title": "Masculino",
                "labelText": "[[value]]",
                "clustered": false,
                "labelFunction": function(item) {
                  return Math.abs(item.values.value);
                },
                "balloonFunction": function(item) {
                  return item.category + ": " + Math.abs(item.values.value) + "%";
                }
              }, {
                "fillAlphas": 0.8,
                "lineAlpha": 0.2,
                "type": "column",
                "valueField": "femenino",
                "title": "Femenino",
                "labelText": "[[value]]",
                "clustered": false,
                "labelFunction": function(item) {
                  return Math.abs(item.values.value);
                },
                "balloonFunction": function(item) {
                  return item.category + ": " + Math.abs(item.values.value) + "%";
                }
              }],
              "categoryField": "rangoedad",
              "categoryAxis": {
                "gridPosition": "start",
                "gridAlpha": 0.2,
                "axisAlpha": 0
              },
              "valueAxes": [{
                "gridAlpha": 0,
                "ignoreAxisWidth": true,
                "labelFunction": function(value) {
                  return Math.abs(value) + '%';
                },
                "guides": [{
                  "value": 0,
                  "lineAlpha": 0.2
                }]
              }],
              "balloon": {
                "fixedPosition": true
              },
              "chartCursor": {
                "valueBalloonsEnabled": false,
                "cursorAlpha": 0.05,
                "fullWidth": true
              },
              "allLabels": [{
                "text": "Femenino",
                "x": "28%",
                "y": "97%",
                "bold": true,
                "align": "middle"
              }, {
                "text": "Masculino",
                "x": "75%",
                "y": "97%",
                "bold": true,
                "align": "middle"
              }],
             "export": {
                "enabled": true
              }
            });
        </script>
        
        <!--Número de personas afiliadas por pertenencia étnica-->
        <script>
            var chart;
            AmCharts.ready(function () {
                var chart = AmCharts.makeChart("piechartRaza", {
                "type": "pie",
                "sequencedAnimation":true,
                "startEffect":"elastic",
                "startDuration": 1,
                "theme": "light",
                "addClassNames": true,
                "legend":{
                    "position":"bottom",
                    "marginRight":100,
                    "autoMargins":false
                  },
                "innerRadius": "40%",
                "gradientRatio": [-0.4, -0.4, -0.4, -0.4, -0.4, -0.4, 0, 0.1, 0.2, 0.1, 0, -0.2, -0.5],
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
                  "dataProvider": <?= json_encode($TablaPertenenciaEtnica, JSON_NUMERIC_CHECK) ?>,
                  "labelText" : "",
                  "valueField": "cantidad",
                  "titleField": "raza",
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