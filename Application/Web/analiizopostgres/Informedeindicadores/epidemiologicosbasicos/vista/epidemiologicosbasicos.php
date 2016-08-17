<!DOCTYPE html>
<html lang="en">

    <head>

        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>

        <title>Miido - Analiizo</title>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <script>
            function showMunicipio() {
                t1 = document.getElementById('municipio').value;
                if (t1 == 0)
                {

                    window.location = ("epidemiologicosbasicos.php");
                }
                else
                {
                    window.location = ("epidemiologicosbasicos.php?municipio_id=" + t1);
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

    //include_once "conexion.php";

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
                        <h1 class="page-header">Informe de indicadores - {$client_name} - Epidemiológicos Básicos</h1>
                    </div>                    
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
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
                                                <input type="hidden" id="codTown" value="<?php echo $item['codmunicipio']; ?>" >                                                                                                                 
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
                                    Total personas por género
                                </div>
                                <!-- /.panel-heading -->
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
                                                    $dAGenero[] = array('genero', 'total');
                                                    foreach ($TablaPersonaGenero as $item):
                                                        $dAGenero[] = array($item[0] . '', $item[1]);
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['genero']; ?></td>
                                                            <td><?php echo $item['total']; ?></td>  
                                                        </tr>
                                                        <?php
                                                    endforeach;
                                                else:
                                                    $dAGenero[] = array('genero', 'total');
                                                    $dAGenero[] = array($item[0] . '', $item[0]);
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
                                    Total personas por género - Gráfico
                                </div>
                                <!-- /.panel-heading -->


                                <div class="panel-body">

                                    <div id="donutchartGenero" style="width: 500px; height: 300px;"></div>

                                </div>                                
                            </div>                            
                        </div>

                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Total por Genero y edad
                                </div>
                                <!-- /.panel-heading -->
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
                                                    $dAPiramidePoblacional[] = array('rangoedad', 'masculino', 'femenino');
                                                    foreach ($TablaGeneroEdad as $item):
                                                        $dAPiramidePoblacional[] = array($item[0], $item[1], ($item[2] * -1));
                                                        ?>
                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['rangoedad']; ?></td>
                                                            <td><?php echo $item['masculino']; ?></td>  
                                                            <td><?php echo $item['femenino']; ?></td>
                                                        </tr>
                                                        <?php
                                                    endforeach;
                                                else:
                                                    $dAPiramidePoblacional[] = array('rangoedad', 'masculino', 'femenino');
                                                    $dAPiramidePoblacional[] = array($item[0], $item[0], ($item[0] * -1));
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
                                    Piramide poblacional
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="chart_div" style="width: 500px; height: 600px;"></div>


                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>

                    </div>
                    <!-- /.row -->

                    <!--                    <div class="row">                       
                    
                                            <div class="col-lg-12">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        Porcentaje de la población por mucicipio que pertenece a comfasucre  - Gráfico
                                                    </div>
                                                     /.panel-heading 
                                                    <div class="panel-body">
                    
                    
                                                        <div id="columnchartPoblacionPertenece" style="width: 900px; height: 500px;"></div>
                    
                    
                                                    </div>
                                                     /.panel-body 
                                                </div>
                                                 /.panel 
                                            </div>
                    
                                        </div>-->

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Porcentaje de la población por municipio que pertenece a {$client_name}
                                </div>
                                <!-- /.panel-heading -->
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
                                                <span>Total pobleción del municipio</span>
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
                                                if (count($TablaPoblacionTownComfasucre) > 0):
                                                    // matriz para rellenar títulos
                                                    $dAPoblacionPertenece[] = array('nombremunicipio', 'afiliadomunicipio', 'totalpoblacion', 'porcentaje');
                                                    foreach ($TablaPoblacionTownComfasucre as $item):
                                                        // matriz para rellenar los datos
                                                        $TablaPoblacionTownComfasucre[] = array($item[1], $item[2], $item[3], $item[4]);
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>                       

                    </div>

                    <!-- /.row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Razón niños / mujer
                                </div>
                                <!-- /.panel-heading -->
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
                                    Índice de infancia
                                </div>
                                <!-- /.panel-heading -->


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
                                    <!-- /.table-responsive -->

                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->

                        </div>
                        <!-- /.col-lg-6 -->
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Índice de juventud
                                </div>
                                <!-- /.panel-heading -->
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
                                    Índice de vejez

                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-6 -->

                    </div>
                    <!-- /.row -->



                    <div class="row">

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Índice de envejecimiento
                                </div>
                                <!-- /.panel-heading -->
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
                                    Índice de dependencia
                                </div>
                                <!-- /.panel-heading -->

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
                                    <!-- /.table-responsive -->






                                </div>
                                <!-- /.panel-body -->

                            </div>
                            <!-- /.panel -->

                        </div>
                        <!-- /.col-lg-6 -->

                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Índice de dependencia del adulto mayor
                                </div>
                                <!-- /.panel-heading -->
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
                                    Índice de Friz
                                </div>
                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->






                                </div>
                                <!-- /.panel-body -->

                            </div>
                            <!-- /.panel -->

                        </div>
                        <!-- /.col-lg-6 -->

                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-lg-6">


                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Número de personas en condición de discapacidad
                                </div>


                                <!-- /.panel-heading -->
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
                                    Tasa bruta de natalidad por año
                                </div>


                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->

                                </div>
                                <!-- /.panel-body -->

                            </div>
                            <!-- /.panel -->

                        </div>
                        <!-- /.col-lg-6 -->

                    </div>
                    <!-- /.row -->
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Tasa General de Fecundidad  por año
                                </div>


                                <!-- /.panel-heading -->
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
                                    Tasa de fecundidad en mujeres de 10 a 14 años
                                </div>


                                <!-- /.panel-heading -->
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
                                    <!-- /.table-responsive -->






                                </div>
                                <!-- /.panel-body -->

                            </div>
                            <!-- /.panel -->

                        </div>
                        <!-- /.col-lg-6 -->

                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-lg-6">


                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Tasa de fecundidad en mujeres entre 15 y 19 años
                                </div>


                                <!-- /.panel-heading -->
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
                                <!-- /.panel-body -->
                            </div>
                            <!-- /.panel -->
                        </div>
                        <!-- /.col-lg-6 -->
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Porcentaje de afiliados que declaran ser desplazados
                                </div>


                                <!-- /.panel-heading -->
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
                                    Número de personas afiliadas por pertenencia étnica
                                </div>
                                <!-- /.panel-heading -->
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
                                                    // matriz para rellenar títulos
                                                    $dATipoRaza[] = array('Tipo de vivienda', 'Porcentaje');
                                                    foreach ($TablaPertenenciaEtnica as $item):
                                                        // matriz para rellenar los datos
                                                        $dATipoRaza[] = array($item[0], $item[2]);
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
                                                    $dATipoRaza[] = array('Tipo de vivienda', 'Porcentaje');
                                                    $dATipoRaza[] = array($item[0], $item[0]);
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
                                    Número de personas afiliadas por pertenencia étnica - Gráfico
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">


                                    <div id="piechartRaza" style="width: 500px; height: 300px;"></div>
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


            <!--Total personas por género-->


            <script type="text/javascript">
                google.load('visualization', '1', {packages: ['corechart', 'bar']});
                google.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data_array = <?= json_encode($dAGenero, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);
                    var options = {
                        title: 'Total personas por género',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchartGenero'));
                    chart.draw(data, options);
                }

            </script>

            <script type="text/javascript">
                google.load('visualization', '1', {packages: ['corechart', 'bar']});
                google.setOnLoadCallback(drawChart);


                function drawChart() {
                    var data_array = <?= json_encode($dAGenero, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);
                    var options = {
                        title: 'Total personas por género',
                        pieHole: 0.4,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchartGenero'));
                    chart.draw(data, options);
                }

            </script>

            <!--Piramide Poblacional--> 

            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});
                google.setOnLoadCallback(chart);

                function chart() {
                    //var data_array = new google.visualization.DataTable();
                    var data_array = <?= json_encode($dAPiramidePoblacional, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);
                    var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
                    var options = {
                        isStacked: true,
                        hAxis: {
                            format: ';'


                        },
                        vAxis: {
                            direction: -1

                        }
                    };

                    var formatter = new google.visualization.NumberFormat({
                        pattern: ';'
                    });

                    formatter.format(data, 2);
                    chart.draw(data, options);
                }

            </script>


            <!--Número de personas afiliadas por pertenencia étnica-->


            <script type="text/javascript">
                google.load("visualization", "1", {packages: ["corechart"]});

                google.setOnLoadCallback(drawChart);
                function drawChart() {



                    data_array = <?= json_encode($dATipoRaza, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Número de personas afiliadas por pertenencia étnica',
                        is3D: true,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechartRaza'));

                    chart.draw(data, options);
                }
            </script>
    </body>

</html>