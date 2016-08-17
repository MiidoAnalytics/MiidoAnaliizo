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
                    window.location = ("morbilidad.php");
                }
                else
                {
                    window.location = ("morbilidad.php?municipio_id=" + t1);
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
                        <h1 class="page-header">Informe de indicadores - {$client_name} - Morbilidad</h1>
                    </div>                    
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

                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Principales medicamentos usados por los afiliados - Gráfico
                            </div>                           
                            <div class="panel-body">

                                <div id="chartMedicamentos" style="width: 1200px; height: 1200px;"></div>

                            </div>                            
                        </div>                       
                    </div>

                    <div class="row">

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Principales medicamentos usados por los afiliados
                                </div>                               
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='19' onClick="reply_click(this.id)">Nombre Medicamento
                                                <span>Nombre del medicamento</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total de personas que toman un medicamento específico encuestados de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de personas que toman medicamentos encuestados de {$client_name}</span>
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
                                                if (count($TablaMedicamentosAfiliados) > 0):
                                                    $dAMedicamentos[] = array('descripcion_medicamento', 'porcentaje');
                                                    foreach ($TablaMedicamentosAfiliados as $item):
                                                        $dAMedicamentos[] = array($item[0], $item[3]);
                                                        ?>

                                                        <tr class="odd gradeX">
                                                            <td><?php echo $item['descripcion_medicamento']; ?></td>
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
                                                    $dAMedicamentos[] = array('descripcion_medicamento', 'porcentaje');
                                                    $dAMedicamentos[] = array([], []);
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

                <div class="row">                                        

                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Incidencia enfermedades personales - Gráfico
                            </div>                            
                            <div class="panel-body">
                                <div id="chartEnfermedadesPersonales" style="width: 900px; height: 900px;"></div>                                
                            </div>                           
                        </div>                        
                    </div>

                    <div class="row">

                        <div class="col-lg-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Incidencia enfermedades personales
                                </div>
                                <!-- /.panel-heading -->
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                            <div class="cssToolTip" id='20' onClick="reply_click(this.id)">Nombre Enfermedad
                                                <span>Nombre de la patología</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">numerador
                                                <span>Total de personas que presentan una patología específica encuestadas de {$client_name}</span>
                                            </div>
                                            </th>
                                            <th>
                                            <div class="cssToolTip">denominador
                                                <span>Total de personas presentan alguna patología encuestadas de {$client_name}</span>
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
                                                if (count($TablaEnfermedadesPersonales) > 0):
                                                    $dAEnfermedadesPersonales[] = array('detalle', 'porcentaje');
                                                    foreach ($TablaEnfermedadesPersonales as $item):
                                                        $dAEnfermedadesPersonales[] = array($item[0], $item[3]);
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
                                                    $dAEnfermedadesPersonales[] = array('detalle', 'porcentaje');
                                                    $dAEnfermedadesPersonales[] = array([], []);
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

                <div class="row">

                    <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Prevalencia de anemia en mujeres de 10 a 13 años
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                        <div class="cssToolTip" id='21' onClick="reply_click(this.id)">Numerador
                                            <span>Total mujeres de 10 a 13 años, con código enfernmedad D50, D64 encuestados de {$client_name}</span>
                                        </div>
                                        </th>
                                        <th>
                                        <div class="cssToolTip" id='22' onClick="reply_click(this.id)">Denominador
                                            <span>Total mujeres de 10 a 13 años encuestados de {$client_name}</span>
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
//include_once "conexion.php";

                                            if (empty($_SESSION['user'])) {
                                                ?>
                                            <div class="alert alert-danger">
                                                El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
                                            </div>
                                            <?php
                                        } else {
                                            if (count($TablaPrevaAnemiMuj3a10) > 0):
                                                foreach ($TablaPrevaAnemiMuj3a10 as $item):
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
                                Prevalencia de Diabetes Mellitus en personas de 18 a 69 años
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                        <div class="cssToolTip" id='23' onClick="reply_click(this.id)">Numerador
                                            <span>Total personas de 18 a 69 años, con código enfernmedad E10, E64, O24 encuestados de {$client_name}</span>
                                        </div>
                                        </th>
                                        <th>
                                        <div class="cssToolTip" id='24' onClick="reply_click(this.id)">Denominador
                                            <span>Total personas de 18 a 69 años encuestados de {$client_name}</span>
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
                                            if (count($TablaDiabetesMellitus18a69) > 0):
                                                foreach ($TablaDiabetesMellitus18a69 as $item):
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
                                Prevalencia de enfermedad renal crónica
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                        <div class="cssToolTip" id='25' onClick="reply_click(this.id)">Numerador
                                            <span>Total personas con código enfernmedad N17, N19 encuestados de {$client_name}</span>
                                        </div>
                                        </th>
                                        <th>
                                        <div class="cssToolTip">denominador
                                            <span>Total personas encuestados de {$client_name}</span>
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
                                            if (count($TablaEnfermedadRenal) > 0):
                                                foreach ($TablaEnfermedadRenal as $item):
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
                                Prevalencia de Hipertensión Arterial en personas de 18 a 69 años
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                        <div class="cssToolTip" id='26' onClick="reply_click(this.id)">Numerador
                                            <span>Total personas de 18 a 69 años, con código enfernmedad I10,I15,O10,O14,I16, presión arterial diastólica mayor de 80 y presión arterial sistólica mayor de 120 encuestados de Comfasucre</span>
                                        </div>
                                        </th>
                                        <th>
                                        <div class="cssToolTip" id='24' onClick="reply_click(this.id)">Denominador
                                            <span>Total personas de 18 a 69 años encuestados de {$client_name}</span>
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
                                            if (count($TablaHipertensionArterial18a69) > 0):
                                                foreach ($TablaHipertensionArterial18a69 as $item):
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
                                Prevalencia de obesidad en mujeres de 18 a 64 años
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                        <div class="cssToolTip" id='27' onClick="reply_click(this.id)">Numerador
                                            <span>Total mujeres de 18 a 64 años con indice de masa corporal mayor a 30 encuestados de {$client_name}</span>
                                        </div>
                                        </th>
                                        <th>
                                        <div class="cssToolTip" id='28' onClick="reply_click(this.id)">Denominador
                                            <span>Total mujeres de 18 a 64 años encuestadas de {$client_name}</span>
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
                                            if (count($TablaObesidadMuj18a64) > 0):
                                                foreach ($TablaObesidadMuj18a64 as $item):
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
                                Prevalencia de obesidad en personas de 18 a 64 años
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                        <div class="cssToolTip" id='29' onClick="reply_click(this.id)">Numerador
                                            <span>Total personas de 18 a 64 años con indice de masa corporal mayor a 30 encuestados de {$client_name}</span>
                                        </div>
                                        </th>
                                        <th>
                                        <div class="cssToolTip" id='30' onClick="reply_click(this.id)">Denominador
                                            <span>Total personas de 18 a 64 años encuestados de {$client_name}</span>
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
                                            if (count($TablaObesidad18a64) > 0):
                                                foreach ($TablaObesidad18a64 as $item):
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
                                Prevalencia de obesidad en hombres de 18 a 64 años
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                        <div class="cssToolTip" id='31' onClick="reply_click(this.id)">Numerador
                                            <span>Total hombres de 18 a 64 años con indice de masa corporal mayor a 30 encuestados de {$client_name}</span>
                                        </div>
                                        </th>
                                        <th>
                                        <div class="cssToolTip" id='32' onClick="reply_click(this.id)">Denominador
                                            <span>Total hombres de 18 a 64 años encuestados de {$client_name}</span>
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
                                            if (count($TablaObesidadHom18a64) > 0):
                                                foreach ($TablaObesidadHom18a64 as $item):
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
                                Prevalencia registrada de VIH/Sida
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                        <div class="cssToolTip" id='33' onClick="reply_click(this.id)">Numerador
                                            <span>Total personas con código enfernmedad B20,B24,R75,Z21, encuestados de {$client_name}</span>
                                        </div>
                                        </th>
                                        <th>
                                        <div class="cssToolTip">denominador
                                            <span>Total personas encuestados de {$client_name}</span>
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
                                            if (count($TablaVIHSida) > 0):
                                                foreach ($TablaVIHSida as $item):
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
                                Prevalencia registrada de VIH/Sida en personas de 15 a 49 años
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                        <div class="cssToolTip" id='34' onClick="reply_click(this.id)">Numerador
                                            <span>Total personas de 15 a 49 años con código enfernmedad B20,B24,R75,Z21, encuestados de {$client_name}</span>
                                        </div>
                                        </th>
                                        <th>
                                        <div class="cssToolTip" id='35' onClick="reply_click(this.id)">Denominador
                                            <span>Total personas encuestados de {$client_name}</span>
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
                                            if (count($TablaVIHPersonas15a49) > 0):
                                                foreach ($TablaVIHPersonas15a49 as $item):
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

            </div>
            <!-- /#wrapper -->

            <?php include("../../../sitemedia/html/scriptpie.php"); ?>




            <!--        
            *****************************************************************************
            Graficos de la página
            *****************************************************************************            
            -->

            <!--Incidencia medicamentos personales-->

            <script type="text/javascript">
                google.load('visualization', '1', {packages: ['corechart', 'bar']});
                google.setOnLoadCallback(drawBasic);

                function drawBasic() {

                    data_array = <?= json_encode($dAMedicamentos, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Principales medicamentos usados por los afiliados',
                        chartArea: {width: '50%'},
                        hAxis: {
                            title: 'Porcentaje',
                            minValue: 0
                        },
                        vAxis: {
                            title: 'Medicamentos'
                        }
                    };

                    var chart = new google.visualization.BarChart(document.getElementById('chartMedicamentos'));

                    chart.draw(data, options);
                }
            </script>


            <!--Incidencia enfermedades personales-->

            <script type="text/javascript">
                google.load('visualization', '1', {packages: ['corechart', 'bar']});
                google.setOnLoadCallback(drawBasic);

                function drawBasic() {

                    data_array = <?= json_encode($dAEnfermedadesPersonales, JSON_NUMERIC_CHECK) ?>;
                    var data = google.visualization.arrayToDataTable(data_array);

                    var options = {
                        title: 'Incidencia enfermedades personales',
                        chartArea: {width: '50%'},
                        hAxis: {
                            title: 'Porcentaje total encuestados COMFASUCRE',
                            minValue: 0
                        },
                        vAxis: {
                            title: 'Enfermedades'
                        }
                    };

                    var chart = new google.visualization.BarChart(document.getElementById('chartEnfermedadesPersonales'));

                    chart.draw(data, options);
                }
            </script>

    </body>

</html>
