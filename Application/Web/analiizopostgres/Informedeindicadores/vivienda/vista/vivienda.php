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

                    window.location = ("vivienda.php");
                }
                else
                {
                    window.location = ("vivienda.php?municipio_id=" + t1);
                }
            }
        </script>
        <script type="text/javascript">
            function reply_click(clicked_id)
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
                        <h1 class="page-header">Informe de indicadores - {$client_name} - Vivienda</h1>

                    </div>
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

                </div>

                <!-- /.row -->
                <div class="row">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    La vivienda es
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    La vivienda es - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="piechartVivienda" style="width: 500px; height: 300px;"></div>


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
                                    Que servicios posee (Agua) - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="donutchartSerAgua" style="width: 500px; height: 300px;"></div>


                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que servicios posee (Agua)
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>                   
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que servicios posee (Alcantarillado)
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div> 
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que servicios posee (Alcantarillado) - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="donutchartSerAlcantarillado" style="width: 500px; height: 300px;"></div>


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
                                    Que servicios posee (Gas Natural) - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="donutchartSerGasNatural" style="width: 500px; height: 300px;"></div>


                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que servicios posee (Gas Natural)
                                </div>
                                <!-- /.panel-heading -->
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
                                    Que servicios posee (Energía Eléctrica)
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que servicios posee (Energía Eléctrica) - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="donutchartEnergiaElectrica" style="width: 500px; height: 300px;"></div>


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
                                    Que servicios posee (Ducha) - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="donutchartDucha" style="width: 500px; height: 300px;"></div>


                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    La vivienda posee (Ducha)
                                </div>
                                <!-- /.panel-heading -->
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
                                    La vivienda posee (Letrina)
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que servicios posee (Letrina) - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="donutchartLetrina" style="width: 500px; height: 300px;"></div>


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
                                    Que servicios posee (Cocina dentro del dormitorio) - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="donutchartCocina" style="width: 500px; height: 300px;"></div>


                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    La vivienda posee (Cocina dentro del dormitorio)
                                </div>
                                <!-- /.panel-heading -->
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
                                    Cuantos dormitorios posee
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Cuantos dormitorios posee - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="chart_div" style="width: 500px; height: 300px;"></div>


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
                                    Presencia de animales - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="donutchartAnimales" style="width: 500px; height: 300px;"></div>


                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Presencia de animales
                                </div>
                                <!-- /.panel-heading -->
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
                                    Tiene piso en tierra
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Tiene piso en tierra - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="donutchartTierra" style="width: 500px; height: 300px;"></div>


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
                                    Que mascota posee - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="piechartMascota" style="width: 500px; height: 300px;"></div>


                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Que mascota posee
                                </div>
                                <!-- /.panel-heading -->
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
                                    El agua que consume es
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>                        

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    El agua que consume es - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="piechartEstadoAgua" style="width: 500px; height: 300px;"></div>


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
                                    Tratamiento de la basura - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="chartBasuras" style="width: 500px; height: 300px;"></div>


                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Tratamiento de la basura
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>

                            </div>

                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Estado del techo - Gráfico
                                </div>

                                <div class="panel-body">


                                    <div id="donutchartTecho" style="width: 500px; height: 300px;"></div>


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


                                    <div id="donutchartParedes" style="width: 500px; height: 300px;"></div>


                                </div>                                
                            </div>                            
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Estado de las paredes
                                </div>
                                <!-- /.panel-heading -->
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
                                    Hay suficiente luz
                                </div>
                                <!-- /.panel-heading -->
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
                                    Hay precencia de aguas negras
                                </div>
                                <!-- /.panel-heading -->
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
                                    Hay precencia de roedores
                                </div>
                                <!-- /.panel-heading -->
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
                                    Materiales predominantes de las paredes
                                </div>
                                <!-- /.panel-heading -->
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
                                    Materiales predominantes de los pisos
                                </div>
                                <!-- /.panel-heading -->
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
                                    Materiales predominantes del techo
                                </div>
                                <!-- /.panel-heading -->
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
                                    Que tipo de alumbrado utiliza
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id="108" onClick="reply_click(this.id)">Iluminación
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
                                                            <td><?php echo $item['iluminacion']; ?></td>
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

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Promedio de Grupos Familiares por Vivienda
                                </div>
                                <!-- /.panel-heading -->
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
                                    Promedio de Personas por Vivienda
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>   

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Personas por viviendad Disgregado
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>     

                    </div>

                </div>
                <!-- /#page-wrapper -->

            </div>
            <!-- /#wrapper -->

            <?php include("../../../sitemedia/html/scriptpie.php"); ?>

            <!--        
            *****************************************************************************
            Graficos de la página
            *****************************************************************************            
            -->

            <!--Grafico de "La vivienda es"-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dATipoVivienda, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'La vivienda es',
                        is3D: true,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechartVivienda'));
                    chart.draw(data, options);
                }
            </script>

            <!--Que servicios posee (Agua)"-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dAServicioAgua, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Que servicios posee (Agua)',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchartSerAgua'));
                    chart.draw(data, options);
                }
            </script>

            <!--Que servicios posee (Alcantarillado)"-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dAServicioAlcantarillado, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Que servicios posee (Alcantarillado)',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchartSerAlcantarillado'));
                    chart.draw(data, options);
                }
            </script>

            <!--Que servicios posee (Gas Natural)"-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dAServicioGasNatural, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Que servicios posee (Gas Natural)',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchartSerGasNatural'));
                    chart.draw(data, options);
                }
            </script>

            <!--Que servicios posee (Energía Eléctrica)"-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dAServicioEnergiaElectrica, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Que servicios posee (Energía Eléctrica)',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchartEnergiaElectrica'));
                    chart.draw(data, options);
                }
            </script>

            <!--Que servicios posee (Ducha)"-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dAServicioDucha, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'La vivienda posee (Ducha)',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchartDucha'));
                    chart.draw(data, options);
                }
            </script>

            <!--Que servicios posee (Letrina)"-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dAServicioLetrina, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'La vivienda posee (Letrina)',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchartLetrina'));
                    chart.draw(data, options);
                }
            </script>

            <!--Que servicios posee (Cocina dentro del dormitorio)"-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dAServicioCocina, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'La vivienda posee (Cocina dentro del dormitorio)',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchartCocina'));
                    chart.draw(data, options);
                }
            </script> 

            <!--Cuantos dormitorios posee-->           

            <script type="text/javascript">
                google.load('visualization', '1', {packages: ['corechart', 'bar']});
                google.setOnLoadCallback(drawBasic);

                function drawBasic() {

                    data_array = <?= json_encode($dADormitorios, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Cuantos dormitorios posee',
                        chartArea: {width: '50%'},
                        hAxis: {
                            title: 'Porcentaje',
                            minValue: 0
                        },
                        vAxis: {
                            title: 'Dormitorios'
                        }
                    };

                    var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

                    chart.draw(data, options);
                }
            </script>

            <!--Presencia de animales-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dAAnimales, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Presencia de animales',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchartAnimales'));
                    chart.draw(data, options);
                }
            </script>

            <!--Tiene piso en tierra-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dATierra, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Tiene piso en tierra',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchartTierra'));
                    chart.draw(data, options);
                }
            </script>

            <!--Que mascota posee-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dAMascota, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Posee Mascota',
                        is3D: true,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechartMascota'));
                    chart.draw(data, options);
                }
            </script>

            <!--El agua que consume es-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dAEstadoAgua, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'La vivienda es',
                        is3D: true,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechartEstadoAgua'));
                    chart.draw(data, options);
                }
            </script>

            <!--Tratamiento de la basura-->           

            <script type="text/javascript">
                google.load('visualization', '1', {packages: ['corechart', 'bar']});
                google.setOnLoadCallback(drawBasic);

                function drawBasic() {

                    data_array = <?= json_encode($dABasuras, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Tratamiento de la basura',
                        chartArea: {width: '50%'},
                        hAxis: {
                            title: 'Porcentaje',
                            minValue: 0
                        },
                        vAxis: {
                            title: 'Tratamiento Basura'
                        }
                    };

                    var chart = new google.visualization.BarChart(document.getElementById('chartBasuras'));

                    chart.draw(data, options);
                }
            </script>

            <!--Estado del techo-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dATecho, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Estado del techo',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchartTecho'));
                    chart.draw(data, options);
                }
            </script>

            <!--Estado de las paredes-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dAParedes, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Estado de las paredes',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchartParedes'));
                    chart.draw(data, options);
                }
            </script>

    </body>

</html>
