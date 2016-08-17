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
                    document.getElementById('dTablaInformes').style.display = 'block'
                }else{
                    document.getElementById('dTablaInformes').style.display = 'none'
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
                }else if(encuesta == ''){
                    alert('Por favor seleccione una encuesta para continuar.');
                    document.getElementById('encuesta').focus();
                }
                else
                {
                    window.location = ("informecampo.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName + "&nameProj=" + proyectoName);
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
                        <h1 class="page-header">Gestión de Proyectos - Informes de Campo</h1>
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
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="encuesta" class="col-lg-2 control-label">Formato: </label>
                                <select name='encuesta' id='encuesta' class='form-control' required>
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
                                                    <th>Nombre Formato</th>                                                                                              
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if ($namePoll != ''):
                                                ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo $nameProj; ?></td> 
                                                        <td><?php echo $namePoll; ?></td>
                                                        <td><a target="_blank" href="verinformeacumu.php?project_id=<?php echo $proyecto ?>">Ver Acumulado</a></td>                                                                                                             
                                                    </tr>
                                            <?php
                                                else:
                                                    ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo('POR FAVOR SELECCIONE UN INFORME'); ?></td>                                                                                                                  
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
                    <input type="hidden" id="inmostable" value="<?php echo count($informes); ?>">
                    <!-- Tabla donde se muestra los informes del formato seleccionado -->
                    <div class="row" id="dTablaInformes" style="display:none">
                        
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                        Listado de Informes
                                </div>                            
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Id Informe</th>
                                                    <th>Nombre Informe</th>
                                                    <th>Fecha de creación</th>
                                                    <th>Id Interventor</th>
                                                    <th>Username Interventor</th>
                                                    <th>Ver Gráficas</th>
                                                    <th>Descargar</th>
                                                    <th>Ingresar Observaciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (count($informes) > 0):
                                                        $i = 1;
                                                        foreach ($informes as $item):
                                                            ?>
                                                            <tr class="odd gradeX" id="count($informes)">
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo $namePoll; ?></td>
                                                                <td><?php echo $item['fechacreacion']; ?></td>
                                                                <td><?php echo $item['interventor']; ?></td>
                                                                <?php
                                                                    $strInterventor = $item['interventor'];
                                                                    $interventorData = InformeCampo::ObtenerInterventorporInforme($strInterventor);
                                                                ?>
                                                                <td><?php echo  $interventorData[0]['strusername'];?></td>
                                                                <td><a target="_blank" href="verinforme.php?poll_id=<?php echo $item['idpoll'] ?>&project_id=<?php echo $proyecto ?>&pollstr_id=<?php echo $encuesta ?>&descripcion=<?php echo $namePoll; ?>"> Ver </a></td>
                                                                <td><a target="_blank" href="descargarinforme.php?poll_id=<?php echo $item['idpoll'] ?>&project_id=<?php echo $proyecto ?>&pollstr_id=<?php echo $encuesta ?>&descripcion=<?php echo $namePoll; ?>"> Descargar </a></td>
                                                                <!-- <td><a target="_blank" href="verinformeacumu.php?project_id=<?php //echo $proyecto ?>&pollstr_id=<?php //echo $encuesta ?>">Ver Acumulado</a></td> -->
                                                                <td><a target="_blank" href="ingresarobservaciones.php?project_id=<?php echo $proyecto ?>&pollstr_id=<?php echo $item['idpoll'] ?>">Observaciones</a></td>
                                                            </tr>
                                                            <?php
                                                            $i++;
                                                        endforeach;
                                                    else:
                                                        ?>
                                                        <p> No hay informes para mostrar </p>
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