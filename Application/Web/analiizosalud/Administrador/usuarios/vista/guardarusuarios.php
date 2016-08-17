<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fUsuarios.strFirstName.focus();
                document.fUsuarios.removeEventListener('submit', validarFormulario);
                document.fUsuarios.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fUsuarios;
                validartexto(formulario);
            }    
        </script>
        <title> Guardar Usuario </title>

    </head>

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php
            include("../../../Administrador/menu/controlador/menu.php");
            
            ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Usuarios</h1>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Guardar Usuario
                            </div>

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action="../controlador/guardarusuarios.php" role="form" name="fUsuarios">
                                            <div class="form-group">
                                                <label>Nombres</label>
                                                <input type="text" class="form-control" name="strFirstName" id="strFirstName"
                                                placeholder="Nombres Usuario" value="<?php echo $usuarios->getFirtsName(); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Apellidos</label>
                                                <input type="text" class="form-control" name="strLastName" id="strLastName" 
                                                placeholder="Apellidos Usuario" value="<?php echo $usuarios->getLatsName(); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Teléfono</label>
                                                <input type="number" class="form-control" name="strPhone" id="strPhone" 
                                                placeholder="Telefono Usuario" value="<?php echo $usuarios->getUserPhone(); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>E-mail</label>
                                                <input type="email" class="form-control" name="strEmail" id="strEmail" 
                                                placeholder="e-mail Usuario" value="<?php echo $usuarios->getUserEmail(); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Login</label>
                                                <input type="text" class="form-control" name="strLogin" id="strLogin" 
                                                placeholder="Nombre de Usuario" value="<?php echo $usuarios->getUserLogin(); ?>" required>
                                            </div>
                                            <?php if ($usuarios->getIdUsuarios()): ?>
                                                <input type="hidden" name="idusuarios" value="<?php echo $usuarios->getIdUsuarios(); ?>" />
                                            <?php endif; ?>
                                            <input type="submit" class="btn btn-success" value="Guardar">                  
                                            <a href="../controlador/usuarios.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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
