<!DOCTYPE html>
<html lang="en">

    <head>

        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <title>Miido - Analiizo</title>
        <script type="text/javascript">
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

            function mostrarEncuestas() {
                proyecto = document.getElementById('proyecto').value;
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
                    window.location = ("baseencuestados.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName);
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

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <?php include("../../../Administrador/menu/controlador/menu.php"); ?>
        <div id="page-wrapper">
            <div class="row">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Relación de personas encuestadas</h1>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-4">
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
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="encuesta" class="col-lg-2 control-label">Encuesta: </label>
                                        <select name='encuesta' id='encuesta' class='form-control' required>
                                             <option value="" selected="selected">SELECCIONE</option>
                                        </select> 
                                    </div>
                                </div> 
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success" onclick="mostrarEncuestas()">Aceptar</button>
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
                                                            <th><?php echo $namePoll; ?></th>                                                                                              
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
                    <div class="panel-body" id="divTableEnc" style="displsy: none;">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>                                            
                                        <th>area</th>
                                        <th>latitud</th>
                                        <th>longitud</th>                                            
                                        <th>tipo_casa</th>
                                        <th>nombre_familia</th>
                                        <th>nombres y apellidos</th>
                                        <th>direccion</th>
                                        <th>telefono</th>
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
                                    foreach ($TablaRelacionPersonasEncuestadas as $item):
                                        ?>
                                        <tr class="odd gradeX">                                                    
                                            <td><?php echo $item['area']; ?></td>
                                            <td><?php echo $item['latitud']; ?></td>
                                            <td><?php echo $item['longitud']; ?></td>                                                    
                                            <td><?php echo $item['tipo_casa']; ?></td>                                                    
                                            <td><?php echo $item['nombre_familia']; ?></td>
                                            <td><?php echo $item['nombres']." ".$item['apellidos']; ?></td>
                                            <td><?php echo $item['direccion']; ?></td>
                                            <td><?php echo $item['telefono']; ?></td>
                                        </tr>
                                        <?php
                                    endforeach;
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                </div>
            </div>
        </div>            
    </div>
    <?php include("../../../sitemedia/html/scriptpie.php"); ?>    
</body>
<?php 
    if ($namePoll != ''): ?>
        ?><script type="text/javascript">document.getElementById('divEncSel').style.display="block";</script><?php
    else:
         ?>
        ?><script type="text/javascript">document.getElementById('divEncSel').style.display="none";</script><?php
    endif;

    if (count($TablaRelacionPersonasEncuestadas) > 0): 
        ?><script type="text/javascript">document.getElementById('divTableEnc').style.display="block";</script><?php
    else:
    ?>
        ?><script type="text/javascript">document.getElementById('divTableEnc').style.display="none";</script>
        <p>No hay resultados para mostrar</p>
<?php
    endif;
    ?>
</html>
