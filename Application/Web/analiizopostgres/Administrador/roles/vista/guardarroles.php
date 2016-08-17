<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fRol.rolName.focus();
                document.fRol.removeEventListener('submit', validarFormulario);
                document.fRol.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fRol;
                validartexto(formulario);
            }    
        </script>
        <title> Guardar Rol </title>

    </head>

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php
            include("../../../Administrador/menu/controlador/menu.php");
            
            ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Roles</h1>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Guardar Rol
                            </div>

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action="../controlador/guardarroles.php" role="form" name="fRol">
                                            <div class="form-group">
                                                <label>Nombre Rol</label>
                                                <input type="text" class="form-control" name="rolName" id="rolName" 
                                                placeholder="Nombre Rol" value="<?php echo $roles->getNameRol(); ?>" required>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label>Consultar</label>
                                                <input type="checkbox" class="form-control" name="Consult" id="Consult" 
                                                    value="1" required>
                                            </div> -->
                                            <?php if ($roles->getIdRol()): ?>
                                                <input type="hidden" name="role_id" value="<?php echo $roles->getIdRol() ?>" />
                                            <?php endif; ?>
                                            <input type="submit" class="btn btn-success" value="Guardar">                    
                                            <a href="../controlador/roles.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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
