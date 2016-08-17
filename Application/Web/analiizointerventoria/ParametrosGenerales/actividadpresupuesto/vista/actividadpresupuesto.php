<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <title> Listar Actividades y Presupuesto</title>
        <script type="text/javascript">
            window.onload = function () {
                document.getElementById('proyecto').focus();
                var tot = document.getElementById('inmostable').value;
                if(tot > 0){
                    document.getElementById('dActividades').style.display = 'block'
                }else{
                    document.getElementById('dActividades').style.display = 'none'
                }
            }
            function encPro() {
                    $.post("../../../Administrador/asignacionencuestas/controlador/encuestaproyecto.php", {
                        proyecto:document.getElementById('proyecto').value 
                }, function (data) {
                        $('#encuesta').html(data);
                    });
            }
            function mostrarActividades() {
                proyecto = document.getElementById('proyecto').value;
                proyectoName = $('#proyecto option:selected').text();

                if (proyecto == '')
                {
                    alert('Por favor seleccione un proyecto para continuar.');
                    document.getElementById('proyecto').focus();
                }else
                {
                    window.location = ("actividadpresupuesto.php?proyecto=" + proyecto);
                }
            };
        </script>
    </head>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Actividades y Presupuesto</h1>
                    </div>                    
                </div>

                <div class="row">
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
                        <div class="row" style="margin-left:15em; margin-bottom: 1em">
                            <div class="form-group; col-lg-4">
                                <button type="button" class="btn btn-success" onclick="mostrarActividades()">Mostar</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="inmostable" value="<?php echo count($actividadesDos); ?>">
                    <div class="col-lg-12" id="dActividades" style="dysplay:none">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Actividades y Presupuesto
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <!-- <p> <a href="../controlador/guardardepartamento.php"> Crear nuevo departamento </a> </p> -->
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Indice</th>
                                                <th>Nombre</th>
                                                <th>Fecha Programada</th>
                                                <th>Usuario Registra</th>
                                                <th>Fecha Registro</th>
                                                <th>Fecha Modificado</th>
                                                <th>Editar</th>
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
                                            ?>

                                            <?php if (count($actividadesDos) > 0): ?>
                                                <?php foreach ($actividadesDos as $item): ?>

                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['intidactividad']; ?></td>
                                                        <td><?php echo $item['strdescripcion']; ?></td>
                                                        <td><?php echo $item['strdescripcion']; ?></td>
                                                        <td><?php echo $item['strlogin']; ?></td>
                                                        <td><?php echo $item['dtcreatedate']; ?></td>
                                                        <td><?php echo $item['dtmodifieddate']; ?></td>
                                                        <td><a href="../controlador/editarActividad.php?actividad_id=<?php echo $item['intidactividad'] ?>&proyecto_id=<?php echo $proyecto?>&encuesta_id=<?php echo $idpollstru ;?>"> Editar </a></td>
                                                    </tr>
        <?php endforeach; ?>
    <?php else: ?>
                                                <p> No hay registros para mostrar </p>
                                            <?php endif; ?>
                                        <?php } ?>
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

    </body>

</html>
