<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fMachine.strNombreEquipo.focus();
                document.fMachine.removeEventListener('submit', validarFormulario);
                document.fMachine.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fMachine;
                validartexto(formulario);
            }    
        </script>
        <title> Guardar equipo </title>
    </head>
    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Equipos</h1>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Guardar Equipos
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action="../controlador/guardarequipo.php" role="form" name="fMachine">
                                            <div class="form-group">
                                                <label>Nombre</label>
                                                <input type="text" class="form-control" name="strNombreEquipo" id="strNombreEquipo" placeholder="Nombre Equipo" value="<?php echo $equipo->getNombreEquipo() ?>" required>
                                            </div>

                                            <?php if ($equipo->getIdEquipo()): ?>
                                                <input type="hidden" name="equipo_id" value="<?php echo $equipo->getIdEquipo() ?>" />
                                            <?php endif; ?>
                                            <input type="submit" class="btn btn-success" value="Guardar">                                   
                                            <a href="equipo.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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
