<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fInterEnc.proyecto.focus();
                document.fInterEnc.removeEventListener('submit', validarFormulario);
                document.fInterEnc.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fInterEnc;
                validartexto(formulario);
            }  

            function encPro(inputString) {
                $.post("../controlador/encuestaproyecto.php", {
                    proyecto:document.getElementById('proyecto').value 
            }, function (data) {
                    $('#encuesta').html(data);
                });
            }  
        </script>
        <title> Asignar Encuestas </title>

    </head>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Asignación de Encuestas</h1>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Asignar Encuestas
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action="../controlador/guardarinterencuesta.php" role="form" name="fInterEnc" >
                                            <div class="form-group">
                                                <label for="proyecto" class="col-lg-2 control-label">Proyecto: </label>
                                                <select name='proyecto' id='proyecto' class='form-control' required onchange="encPro();">
                                                    <option value="" selected="selected">SELECCIONE</option> 
                                                    <?php foreach ($proyectos as $item): ?>
                                                        <option value="<?php echo $item['intidproyecto']; ?>"><?php echo $item['nombre']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="encuesta" class="col-lg-2 control-label">Encuesta: </label>
                                                <select name='encuesta' id='encuesta' class='form-control' required>

                                                </select> 
                                            </div>
                                            <div class="form-group">
                                                <label for="encuestador" class="col-lg-2 control-label">Encuestador: </label>
                                                 <select name='encuestador' id='encuestador' class='form-control' required>
                                                    <option value="" selected="selected">SELECCIONE</option> 
                                                    <?php foreach ($encuestadores as $item): ?>
                                                        <option value="<?php echo $item['intidinterviewer']; ?>"><?php echo $item['strusername']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <input type="submit" class="btn btn-success" value="Guardar" >               
                                            <a href="../controlador/asignacionencuestas.php"><button type="button" class="btn btn-success">Cancelar</button></a>
                                        </form>                                      
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
