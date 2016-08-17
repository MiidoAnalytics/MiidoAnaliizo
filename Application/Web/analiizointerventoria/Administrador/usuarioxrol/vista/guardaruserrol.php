<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fUserRol.idusuarios.focus();
                document.fUserRol.removeEventListener('submit', validarFormulario);
                document.fUserRol.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fUserRol;
                validartexto(formulario);
            }    
        </script>
        <title> Asignar Rol a Usuario </title>

    </head>

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php
            include("../../../Administrador/menu/controlador/menu.php");
            require_once '../../../Administrador/menu/modelo/classroles.php';
            $rolesAll = Roles::getAllRoles();
            require_once '../../../Administrador/usuarios/modelo/classusuarios.php';
            $usuariosAll = Usuarios::getAllUsuarios();
            ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Usuarios Rol</h1>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Asiganar Usuario Rol
                            </div>

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action="../controlador/guardaruserrol.php" role="form" name="fUserRol">
                                            <div class="form-group">
                                                <label>Login</label>
                                                <select name='idusuarios' id='idusuarios' class='form-control' required>
                                                    <option value="<?php echo ''; ?>">SELECCIONE</option>
                                                <?php foreach ($usuariosAll as $item): ?>
                                                    <option value="<?php echo $item['idusuarios']; ?>"><?php echo $item['strlogin']; ?></option>
                                                <?php endforeach; ?>
                                                </select> 
                                                
                                            </div>
                                            <div class="form-group">
                                                <label>Rol</label>
                                                <select name='role_id' id='role_id' class='form-control' required>
                                                <?php foreach ($rolesAll as $item): ?>
                                                        <option value="<?php echo $item['role_id']; ?>"><?php echo $item['role_name']; ?></option>
                                                <?php endforeach; ?>
                                                </select> 
                                            </div>
                                            <input type="submit" class="btn btn-success" value="Guardar">                  
                                            <a href="usuarioxrol.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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
