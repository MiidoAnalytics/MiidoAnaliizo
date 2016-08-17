<!DOCTYPE html>
<html lang="en">

    <head>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <title>Miido - Analiizo</title>

        <script>
            function showMunicipio() {
                t1 = document.getElementById('municipio').value;
                if (t1 == 0)
                {
                    window.location = ("mujeryedadfertil.php");
                }
                else
                {
                    window.location = ("mujeryedadfertil.php?municipio_id=" + t1);
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
                        <h1 class="page-header">Informe de indicadores - {$client_name} - Mujer y Edad Fértil</h1>
                    </div>
                    <!-- /.col-lg-12 -->
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

                <div class="row">                    

                    <div class="row">

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Porcentaje de mujeres de 15 a 49 años con uso actual de algún método anticonceptivo
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="cssToolTip" id="64" onClick="reply_click(this.id)">Numerador
                                                            <span>Total mujeres entre 15 y 49 años con método planificación familiar encuestadas de {$client_name}</span>
                                                        </div>
                                                    </th>
                                                    <th>
                                                        <div class="cssToolTip" id="65" onClick="reply_click(this.id)">Denominador
                                                            <span>Total mujeres entre 15 y 49 años encuestadas de {$client_name}</span>
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
                                                if (count($TablaMujerAnticonceptivo) > 0):
                                                    foreach ($TablaMujerAnticonceptivo as $item):
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
                                    Edad promedio de inicio de la mestruación
                                </div>
                                <!-- /.panel-heading -->
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
                                    Edad promedio de retiro de la mestruación
                                </div>
                                <!-- /.panel-heading -->
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
                                    Edad promedio de inicio de vida sexual activa
                                </div>
                                <!-- /.panel-heading -->
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
                                    Porcentaje de mujeres en embarazo
                                </div>
                                <!-- /.panel-heading -->
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
                                    Porcentaje de mujeres embarazadas sin control de embarazo
                                </div>
                                <!-- /.panel-heading -->
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
                                    Numero promedio de consultas por embarazo
                                </div>
                                <!-- /.panel-heading -->
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
                                    Porcentaje de mujes embarazadas sin suplemento nutricional
                                </div>
                                <!-- /.panel-heading -->
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
                                    Porcentaje de mujes en edad fertil con planificacion familiar
                                </div>
                                <!-- /.panel-heading -->
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
                                    Metodos de planificación familiar
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Metodos de planificación familiar - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="piechartPlanificacion" style="width: 500px; height: 300px;"></div>


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
                                    Proveedor métodos de planificación familiar - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="piechartProveedorPlanificacion" style="width: 500px; height: 300px;"></div>


                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Proveedor métodos de planificación familiar
                                </div>
                                <!-- /.panel-heading -->
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
                                    Promedio de hijos por mujeres
                                </div>
                                <!-- /.panel-heading -->
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
                                    Porcentaje de mujeres que no realizan citología durante el último año
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-6 -->
                    </div>

                    <div class="row">
                        
<!--                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Porcentaje de mujeres suceptible de vacunación
                                </div>
                                 /.panel-heading 
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>                                            
                                                    <th>numerador</th>
                                                    <th>denominador</th>
                                                    <th>Porcentaje</th>                                            
                                                </tr>
                                            </thead>
                                            <tbody id="TablaMujerSusceptibleVacunacion">
                                                <?php
                                                if (empty($_SESSION['user'])) {
                                                    ?>
                                                <div class="alert alert-danger">
                                                    El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                                </div>
                                                <?php
                                            } else {
                                                if (count($TablaMujerSusceptibleVacunacion) > 0):
                                                    foreach ($TablaMujerSusceptibleVacunacion as $item):
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
                                     /.table-responsive 
                                </div>
                                 /.panel-body 
                            </div>
                             /.panel 
                        </div>-->

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Porcentaje de conocimiento exámen de mama
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Total de embarazos por rango de edad
                                    </div>
                                    <!-- /.panel-heading -->
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
                                        <!-- /.table-responsive -->
                                    </div>
                                    <!-- /.panel-body -->
                                </div>
                                <!-- /.panel -->
                            </div>

                            <div class="col-lg-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        Total de embarazos por rango de edad - Gráfico
                                    </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">


                                        <div id="chartembarazosEdad" style="width: 500px; height: 900px;"></div>


                                    </div>
                                    <!-- /.panel-body -->
                                </div>
                                <!-- /.panel -->
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

            <!--Metodos de planificación familiar-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dAPlanificacion, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Metodos de planificación familiar',
                        is3D: true,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechartPlanificacion'));
                    chart.draw(data, options);
                }
            </script>

            <!--Proveedor métodos de planificación familiar-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dAProveedorPlanificacion, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Proveedor métodos de planificación familiar',
                        is3D: true,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechartProveedorPlanificacion'));
                    chart.draw(data, options);
                }
            </script>            

            <!--Porcentaje de mujeres de 15 a 49 años con uso actual de algún método anticonceptivo-->

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {

                    data_array = <?= json_encode($dA1549Anticonceptivo, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Porcentaje de mujeres de 15 a 49 años con uso actual de algún método anticonceptivo',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart1549Anticonceptivo'));
                    chart.draw(data, options);
                }
            </script> 

            <!--Total de embarazos por rango de edad-->

            <script type="text/javascript">
                google.load('visualization', '1', {packages: ['corechart', 'bar']});
                google.setOnLoadCallback(drawBasic);

                function drawBasic() {

                    data_array = <?= json_encode($dAembarazosEdad, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Total de embarazos por rango de edad',
                        chartArea: {width: '50%'},
                        hAxis: {
                            title: 'Cantidad',
                            minValue: 0
                        },
                        vAxis: {
                            title: 'Rango de edad'
                        }
                    };

                    var chart = new google.visualization.BarChart(document.getElementById('chartembarazosEdad'));

                    chart.draw(data, options);
                }
            </script>

    </body>

</html>
