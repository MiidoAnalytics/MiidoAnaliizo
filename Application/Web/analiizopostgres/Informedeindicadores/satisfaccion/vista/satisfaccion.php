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

                    window.location = ("satisfaccion.php");
                }
                else
                {
                    window.location = ("satisfaccion.php?municipio_id=" + t1);
                }
            }

        </script>
    </head>   

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_01.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">
                <div id="banner">

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Informe de indicadores - {$client_name} - Satisfacción</h1>
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
                </div>
                <!-- /.row -->

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
                                                    </tr>
                                                    <?php
                                                endforeach;
                                            else:
                                                ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo('TODOS LOS MUNICIPIOS'); ?></td>                                                                                                                 
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
                                    La antención en la EPS fue?
                                </div>
                                <!-- /.panel-heading -->
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
                                    La respuesta a la antención fue?
                                </div>
                                <!-- /.panel-heading -->
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
                                    La respuesta por parte del personal de la EPS fue?
                                </div>
                                <!-- /.panel-heading -->
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
                                    La información para solicitar un servicio fue?
                                </div>
                                <!-- /.panel-heading -->
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
                                    Usted cree que las instalaciones físicas son?
                                </div>
                                <!-- /.panel-heading -->
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
                                    En general la atención usted cree que ha sido?
                                </div>
                                <!-- /.panel-heading -->
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
                                    Usted considera que la EPS es?
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-6 -->
                    </div>
                </div>
                <!-- /#page-wrapper -->

            </div>
            <!-- /#wrapper -->

<?php include("../../../sitemedia/html/scriptpie.php"); ?>

    </body>

</html>
