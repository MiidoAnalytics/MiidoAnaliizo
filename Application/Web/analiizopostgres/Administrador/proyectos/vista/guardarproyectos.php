<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fProyectos.nombre.focus();
                document.fProyectos.removeEventListener('submit', validarFormulario);
                document.fProyectos.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fProyectos;
                validartexto(formulario);
            }    
        </script>
        <title> Guardar Proyecto </title>

    </head>

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php
            include("../../../Administrador/menu/controlador/menu.php");
            
            ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Proyecto</h1>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Guardar Proyecto
                            </div>

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action="../controlador/guardarproyectos.php" role="form" name="fProyectos">
                                            <div class="form-group">
                                                <label>Nombre Proyecto</label>
                                                <input type="text" class="form-control" name="nombre" id="nombre"
                                                placeholder="Nombre Proyecto" value="<?php echo $proyecto->getNombre(); ?>" required>
                                            </div>
                                            <?php if ($proyecto->getintidproyecto()): ?>
                                                <input type="hidden" name="intidproyecto" value="<?php echo $proyecto->getintidproyecto(); ?>" />
                                            <?php endif; ?>
                                            <input type="submit" class="btn btn-success" value="Guardar">                  
                                            <a href="../controlador/proyectos.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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
