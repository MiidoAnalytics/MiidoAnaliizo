<!DOCTYPE html>
<html lang="en">

    <head>

        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>

        <title>Miido - Analiizo</title>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <script type="text/javascript">
            window.onload = function () {
                document.getElementById('proyecto').focus();
                var tot = document.getElementById('inmostable').value;
                if(tot > 0){
                    document.getElementById('dTablaSemanas').style.display = 'block'
                }else{
                    document.getElementById('dTablaSemanas').style.display = 'none'
                }
            }
            function encPro() {
                    $.post("../../../Administrador/asignacionencuestas/controlador/encuestaproyecto.php", {
                        proyecto:document.getElementById('proyecto').value 
                }, function (data) {
                        $('#encuesta').html(data);
                    });
                } 

            function mostrarEncuestas() {
                proyecto = document.getElementById('proyecto').value;
                proyectoName = $('#proyecto option:selected').text();
                encuesta = document.getElementById('encuesta').value;
                var encuestaName= $('#encuesta option:selected').text();

                if (proyecto == '')
                {
                    alert('Por favor seleccione un proyecto para continuar.');
                    document.getElementById('proyecto').focus();
                }/*else if(encuesta == ''){
                    alert('Por favor seleccione una encuesta para continuar.');
                    document.getElementById('encuesta').focus();
                }*/
                else
                {
                    window.location = ("etapaconstruccion.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName + "&nameProj=" + proyectoName);
                }
            };
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
                        <h1 class="page-header">Parámetros Generales - Etapa de Construcción</h1>
                    </div>                    
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
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
                    </div>
                    <div class="row" style="display:none">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="encuesta" class="col-lg-2 control-label">Formato: </label>
                                <select name='encuesta' id='encuesta' class='form-control'>
                                     <option value="" selected="selected">SELECCIONE</option>
                                </select> 
                            </div>
                        </div> 
                    </div> 
                    <div class="row" style="margin-left:15em; margin-bottom: 1em">
                        <div class="form-group; col-lg-4">
                            <button type="button" class="btn btn-success" onclick="mostrarEncuestas()">Buscar</button>
                        </div>
                    </div>
                    <!-- Tabla donde se muestra el informe seleccionado -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                        Formato Seleccionado
                                </div>                            
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table" id="ExportarExcelInforme">
                                            <thead>
                                                <tr>
                                                    <th>Nombre Contrato</th>                                                                                           
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if ($nameProj != ''):
                                                ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo $nameProj; ?></td> 
                                                    </tr>
                                            <?php
                                                else:
                                                    ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo('POR FAVOR SELECCIONE UN PROYECTO'); ?></td>                                                                                                                  
                                                    </tr>
                                                <?php
                                                endif;
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="inmostable" value="<?php echo count($semanasProgra); ?>">
                    <!-- Tabla donde se muestra los informes del formato seleccionado -->
                    <div class="row" id="dTablaSemanas" style="display:none">
                        
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                        Programación por Semana - Etapa de Construcción
                                </div>                            
                                <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                                <tr>
                                                    <th>Indice</th>
                                                    <th>Semana</th>
                                                    <th>Fecha de Inicio</th>
                                                    <th>Fecha de Fin</th>
                                                    <th>Fecha de Registro</th>
                                                    <th>Programar semana</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if ($semanasProgra > 0):
                                                        $i = 1;
                                                        foreach ($semanasProgra as $item):
                                                            ?>
                                                            <tr class="odd gradeX" >
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo $item['nombre']; ?></td>
                                                                <td><?php echo $item['dtfechainicio']; ?></td>
                                                                <td><?php echo $item['dtfechafin']; ?></td>
                                                                <td><?php echo $item['dtcreatedate'];?></td>
                                                                <td><a href="guardarsemana.php?semana_id=<?php echo $item['intidsemana']?>"> Programar Actividades</a></td>
                                                            </tr>
                                                            <?php
                                                            $i++;
                                                        endforeach;
                                                    else:
                                                        ?>
                                                        <p> No hay Semanas para mostrar </p>
                                                    <?php
                                                    endif;
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
</html>