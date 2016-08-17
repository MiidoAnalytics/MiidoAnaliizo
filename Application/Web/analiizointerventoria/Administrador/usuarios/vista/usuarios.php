<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            function eliminar(data){
                var r = confirm("¿Ralmente desea eliminar este registro? Si elimina este registro se eliminarán las relaciones con el rol asignado");
                if (r == true) {
                    var iduser = document.getElementById('idint'+data).value;
                    if(iduser == 1){
                        alert('No es posible eliminar este usuario');
                    }else{
                        window.location.href = 'deleteusuarios.php?idusuarios='+document.getElementById(iduser).value;
                    }
                } else {
                    window.location = self.location;
                } 
            }

            function resetpw(data){
                var r = confirm("¿Ralmente desea restablecer la contraseña para este usuario? La nueva Contraseña asignada será '123'.");
                if (r == true) {
                    window.location.href = 'resetadm.php?idusuarios='+document.getElementById('idint'+data).value;
                } else {
                    window.location = self.location;
                } 
            }
        </script>
        
        <title> Listar Usuarios </title>
    </head>

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

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
                                Usuarios
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <p> <a href="../controlador/guardarusuarios.php"> Crear nuevo Usuario </a> </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Id Usuarios</th>
                                                <th>Nombre Usuario</th>
                                                <th>Apellido Usuario</th>
                                                <th>Telefono</th>
                                                <th>E-mail</th>
                                                <th>Login</th>
                                                <th>Usuario Registra</th>
                                                <th>Fecha Registro</th>
                                                <th>Fecha Modificación</th>
                                                <th>Editar</th>
                                                <th>Eliminar</th>
                                                <th>Reset Contraseña</th>
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
                                            //echo ($_SESSION['userId']);                                    
                                            ?>

                                            <?php if (count($usuariosAll) > 0):
                                                $i = 1;
                                                foreach ($usuariosAll as $item): ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['idusuarios']; ?></td>
                                                        <td><?php echo $item['strfirtsname']; ?></td>
                                                        <td><?php echo $item['strlastname']; ?></td>
                                                        <td><?php echo $item['strphone']; ?></td>
                                                        <td><?php echo $item['stremail']; ?></td>
                                                        <td><?php echo $item['strlogin']; ?></td>
                                                        <td><?php echo $item['strregistereduser']; ?></td>
                                                        <td><?php echo $item['dtcreatedate']; ?></td>
                                                        <td><?php echo $item['dtmodifieddate']; ?></td>
                                                        <td><a href="../controlador/guardarusuarios.php?idusuarios=<?php echo $item['idusuarios'] ?>"> Editar </a></td>
                                                        <td>
                                                            <form name="fVerPre" action="javascript:eliminar(<?php echo $i; ?>)" method="GET"> 
                                                                <input type="hidden" id="idint<?php echo $i; ?>" value="<?php echo $item['idusuarios'] ?>">
                                                                <input type="submit" class="btn btn-success2" value="Eliminar" style="width:95%"/>     
                                                            </form>
                                                            <!-- <a href="../controlador/deleteusuarios.php?idusuarios=<?php //echo $item['idusuarios'] ?>"> Eliminar </a> -->
                                                        </td> 
                                                        <td>
                                                            <form name="resetpw" action="javascript:resetpw(<?php echo $i; ?>)" method="GET"> 
                                                                <input type="hidden" id="id<?php echo $i; ?>" value="<?php echo $item['idusuarios'] ?>">
                                                                <input type="submit" class="btn btn-success2" value="Reset" style="width:95%"/>     
                                                            </form>
                                                            <!-- <a href="../controlador/resetadm.php?idusuarios=<?php //echo $item['idusuarios'] ?>"> Reset</a> -->
                                                        </td> 
                                                    </tr>
                                                <?php 
                                                    $i++;
                                                endforeach;
                                            else: ?>
                                                <p> No hay Usuarios para mostrar </p>
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
