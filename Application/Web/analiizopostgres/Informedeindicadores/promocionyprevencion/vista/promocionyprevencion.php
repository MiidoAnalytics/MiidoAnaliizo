<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <title>Miido - Analiizo</title>
        <script>
            function showMunicipio() {
                t1 = document.getElementById('municipio').value;
                if (t1 == 0)
                {

                    window.location = ("promocionyprevencion.php");
                }
                else
                {
                    window.location = ("promocionyprevencion.php?municipio_id=" + t1);
                }
            }

        </script>
        <script type="text/javascript">
            function reply_click(clicked_id)
            {
                
                reportid = clicked_id;
                var town = document.getElementById('codTown').value;
                if(town > 0){
                    town;
                    window.location = ("reportExcel.php?intIdDiv=" + reportid + "&town_id=" + town);
                }else{
                    //town = 0;
                    window.location = ("reportExcel.php?intIdDiv=" + reportid + "&town_id=" + town);
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
                <div id="banner">

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Informe de indicadores - {$client_name} - Promoción y Prevención</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <div class="panel-body">
                        <div class="row">
                            <label for="Municipio" class="col-lg-2 control-label">Seleccione el Municipio:</label>
                            <div class="col-lg-6">
                                <select name='municipio' id='municipio' class='form-control' >                
                                    <option value="<?php echo '0'; ?>" selected="selected">TODOS LOS MUNICIPIOS</option> 
                                    <?php
                                    if (count($MunicipiosEncuestados) > 0):
                                        foreach ($MunicipiosEncuestados as $item):
                                            ?>
                                            <option value="<?php echo $item['codmunicipio']; ?>"><?php echo $item['nombremunicipio']; ?></option>
                                            <?php
                                        endforeach;
                                    else:
                                        ?>
                                        <p> No hay registros para mostrar </p>
                                    <?php
                                    endif;
                                    ?>  
                                </select>

                            </div>

                            <button type="button" class="btn btn-success" onclick="showMunicipio()">Aceptar</button>

                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Municipio Seleccionado
                                </div>                               
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nombre Municipio</th>                                                                                              
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (count($MunicipiosEncuestadosxMun) > 0):
                                                    foreach ($MunicipiosEncuestadosxMun as $item):
                                                        ?>

                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['nombremunicipio']; ?></td> 
                                                            <input type="hidden" id="codTown" value="<?php echo $item['codMunicipio']; ?>" >                                                                                                                 
                                                        </tr>
                                                        <?php
                                                    endforeach;
                                                else:
                                                    ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo('TODOS LOS MUNICIPIOS'); ?></td>  
                                                        <input type="hidden" id="codTown" value="0" >                                                                                                                
                                                    </tr>
                                                <?php
                                                endif;
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

                        <div class="row">

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Porcentaje de nacidos vivos con cuatro o mas consultas de control prenatal
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.panel-body -->
                                </div>                   
                            </div>
                            <!-- /.col-lg-6 -->
                        </div>

                        <div class="row">                

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Número de controles prenatales en menores de un año
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.panel-body -->
                                </div>                   
                            </div>

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Número de controles prenatales en menores de una año - Gráfico
                                    </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">


                                        <div id="piechartControlPrenatal" style="width: 500px; height: 300px;"></div>


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
                                        Afiliados por nivel de educación - Gráfico
                                    </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">

                                        <div id="piechartNivelEducacion" style="width: 500px; height: 300px;"></div>

                                    </div>
                                </div>                            
                            </div>                        

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Afiliados por nivel de educación
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        Numero de niños que asisten a control de crecimiento y desarrollo
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        Número de niños que asisten al control de lenguaje, motor y de conducta
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.panel-body -->
                                </div>
                                <!-- /.panel -->
                            </div>

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Número de niños que asisten al control de lenguaje, motor y de conducta - Gráfico
                                    </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">

                                        <div id="piechartControlLenguaje" style="width: 500px; height: 300px;"></div>

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
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">

                                        <div id="piechartControlVision" style="width: 500px; height: 300px;"></div>

                                    </div>
                                </div>                            
                            </div>

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Número de niños que presentan problemas visual, auditivo de conducta
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        Número de niños que recibieron desparacitación
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        Porcentaje de esquema completo de vacunación
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        Bajo peso al nacer, población menor de un año
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        Edad promedio de ablactación (edad en meses)
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        Promedio de consultas prenatales
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        Porcentaje de infante con refuerzo nutricional
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        Proveedor de refuerzo nutricional
                                    </div>
                                    <!-- /.panel-heading -->
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
                                                                <span>Total personas con refuerso nutricional encuestados de {$client_name}</span>
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
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.panel-body -->
                                </div>
                                <!-- /.panel -->
                            </div>

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Proveedor de refuerzo nutricional - Gráfico
                                    </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">

                                        <div id="piechartProveedorRefuerzo" style="width: 500px; height: 300px;"></div>

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

                                        <div id="donutchartControlPlaca" style="width: 500px; height: 300px;"></div>

                                    </div>                                
                                </div>                            
                            </div>

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Ha realizado control de placa
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        Ha asistido a consulta odontológica en los últimos seis meses
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.panel-body -->
                                </div>
                                <!-- /.panel -->
                            </div>

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Ha asistido a consulta odontológica en los últimos seis meses - Gráfico
                                    </div>                                
                                    <div class="panel-body">

                                        <div id="donutchartConsultaOdontologica" style="width: 500px; height: 300px;"></div>

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

                                        <div id="piechartCepillaDia" style="width: 500px; height: 300px;"></div>

                                    </div>                                
                                </div>                            
                            </div>

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Cuentas veces se cepilla al día
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        Ha presentado caries
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.panel-body -->
                                </div>
                                <!-- /.panel -->
                            </div>

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Ha presentado caries - Gráfico
                                    </div>                                
                                    <div class="panel-body">

                                        <div id="donutchartCaries" style="width: 500px; height: 300px;"></div>

                                    </div>                                
                                </div>                            
                            </div>

                        </div>

                    </div>

                </div>
                <!-- /#wrapper -->

                <?php include("../../../sitemedia/html/scriptpie.php"); ?>

                <!--        
               *****************************************************************************
               Graficos de la página
               *****************************************************************************            
                -->  
                <!--Número de controles prenatales en menores de una año-->

                <script type="text/javascript">
                    google.load("visualization", "1", {packages: ["corechart"]});
                    google.setOnLoadCallback(drawChart);
                    function drawChart() {

                        data_array = <?= json_encode($dAControlPrenatal, JSON_NUMERIC_CHECK) ?>;
                        var data = google.visualization.arrayToDataTable(data_array);

                        var options = {
                            title: 'Número de controles prenatales en menores de una año',
                            is3D: true,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('piechartControlPrenatal'));
                        chart.draw(data, options);
                    }
                </script>

                <!--Afiliados por nivel de educación-->

                <script type="text/javascript">
                    google.load("visualization", "1", {packages: ["corechart"]});
                    google.setOnLoadCallback(drawChart);
                    function drawChart() {

                        data_array = <?= json_encode($dANivelEducacion, JSON_NUMERIC_CHECK) ?>;
                        var data = google.visualization.arrayToDataTable(data_array);

                        var options = {
                            title: 'Afiliados por nivel de educación',
                            is3D: true,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('piechartNivelEducacion'));
                        chart.draw(data, options);
                    }
                </script>

                <!--Número de niños que asisten al control de lenguaje, motor y de conducta-->

                <script type="text/javascript">
                    google.load("visualization", "1", {packages: ["corechart"]});
                    google.setOnLoadCallback(drawChart);
                    function drawChart() {

                        data_array = <?= json_encode($dAControlLenguaje, JSON_NUMERIC_CHECK) ?>;
                        var data = google.visualization.arrayToDataTable(data_array);

                        var options = {
                            title: 'Número de niños que asisten al control de lenguaje, motor y de conducta',
                            is3D: true,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('piechartControlLenguaje'));
                        chart.draw(data, options);
                    }
                </script>

                <!--Número de niños que presentan problemas visual, auditivo de conducta-->

                <script type="text/javascript">
                    google.load("visualization", "1", {packages: ["corechart"]});
                    google.setOnLoadCallback(drawChart);
                    function drawChart() {

                        data_array = <?= json_encode($dAControlVision, JSON_NUMERIC_CHECK) ?>;
                        var data = google.visualization.arrayToDataTable(data_array);

                        var options = {
                            title: 'Número de niños que presentan problemas visual, auditivo de conducta',
                            is3D: true,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('piechartControlVision'));
                        chart.draw(data, options);
                    }
                </script>

                <!--Proveedor de refuerzo nutricional-->

                <script type="text/javascript">
                    google.load("visualization", "1", {packages: ["corechart"]});
                    google.setOnLoadCallback(drawChart);
                    function drawChart() {

                        data_array = <?= json_encode($dAProveedorRefuerzo, JSON_NUMERIC_CHECK) ?>;
                        var data = google.visualization.arrayToDataTable(data_array);

                        var options = {
                            title: 'Proveedor de refuerzo nutricional',
                            is3D: true,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('piechartProveedorRefuerzo'));
                        chart.draw(data, options);
                    }
                </script>

                <!--Ha realizado control de placa-->

                <script type="text/javascript">
                    google.load("visualization", "1", {packages: ["corechart"]});
                    google.setOnLoadCallback(drawChart);
                    function drawChart() {

                        data_array = <?= json_encode($dAControlPlaca, JSON_NUMERIC_CHECK) ?>;
                        var data = google.visualization.arrayToDataTable(data_array);

                        var options = {
                            title: 'Ha realizado control de placa',
                            pieHole: 0.4,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('donutchartControlPlaca'));
                        chart.draw(data, options);
                    }
                </script>

                <!--Ha asistido a consulta odontológica en los últimos seis meses-->

                <script type="text/javascript">
                    google.load("visualization", "1", {packages: ["corechart"]});
                    google.setOnLoadCallback(drawChart);
                    function drawChart() {

                        data_array = <?= json_encode($dAConsultaOdontologica, JSON_NUMERIC_CHECK) ?>;
                        var data = google.visualization.arrayToDataTable(data_array);

                        var options = {
                            title: 'Ha asistido a consulta odontológica en los últimos seis meses',
                            pieHole: 0.4,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('donutchartConsultaOdontologica'));
                        chart.draw(data, options);
                    }
                </script>

                <!--Cuentas veces se cepilla al día-->

                <script type="text/javascript">
                    google.load("visualization", "1", {packages: ["corechart"]});
                    google.setOnLoadCallback(drawChart);
                    function drawChart() {

                        data_array = <?= json_encode($dACepillaDia, JSON_NUMERIC_CHECK) ?>;
                        var data = google.visualization.arrayToDataTable(data_array);

                        var options = {
                            title: 'Cuentas veces se cepilla al día',
                            is3D: true,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('piechartCepillaDia'));
                        chart.draw(data, options);
                    }
                </script>

                <!--Ha presentado caries-->

                <script type="text/javascript">
                    google.load("visualization", "1", {packages: ["corechart"]});
                    google.setOnLoadCallback(drawChart);
                    function drawChart() {

                        data_array = <?= json_encode($dACaries, JSON_NUMERIC_CHECK) ?>;
                        var data = google.visualization.arrayToDataTable(data_array);

                        var options = {
                            title: 'Ha presentado caries',
                            pieHole: 0.4,
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('donutchartCaries'));
                        chart.draw(data, options);
                    }
                </script>

                <!--Cuentas veces se cepilla al día-->

                </body>

                </html>
