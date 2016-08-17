<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            function eliminar(data){
                var r = confirm("¿Ralmente desea eliminar este registro?. Esto elimininará las relaciones de usuarios con este Rol.");
                if (r == true) {
                    window.location.href = 'deleteroles.php?role_id='+document.getElementById('idint'+data).value;
                } else {
                    window.location = self.location;
                } 
            }
        </script>

        <title> Listar Roles </title>
    </head>

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

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
                                Roles
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <p> <a href="../controlador/guardarroles.php"> Crear nuevo Rol </a> </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Id Rol</th>
                                                <th>Nombre Rol</th>
                                                <th>Consultar</th>
                                                <th>Usuario Registra</th>
                                                <th>Fecha Registro</th>
                                                <th>Fecha Modificación</th>
                                                <th>Editar</th>
                                                <th>Eliminar</th>
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

                                            <?php if (count($rolesAll) > 0):
                                                $j = 1;
                                                foreach ($rolesAll as $item): ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['role_id']; ?></td>
                                                        <td><?php echo $item['role_name']; ?></td>
                                                        <td><?php echo $item['consult']; ?></td>
                                                        <td><?php echo $item['strregistereduser']; ?></td>
                                                        <td><?php echo $item['dtcreatedate']; ?></td>
                                                        <td><?php echo $item['dtmodifieddate']; ?></td>
                                                        <td><a href="../controlador/guardarroles.php?role_id=<?php echo $item['role_id'] ?>"> Editar </a></td>
                                                        <td>
                                                            <form name="fVerPre" action="javascript:eliminar(<?php echo $j; ?>)" method="GET"> 
                                                                <input type="hidden" id="idint<?php echo $j; ?>" value="<?php echo $item['role_id'] ?>">
                                                                <input type="submit" class="btn btn-success2" value="Eliminar" style="width:95%"/>     
                                                            </form>
                                                            <!-- <a href="../controlador/deleteroles.php?role_id=<?php //echo $item['role_id'] ?>"> Eliminar </a> -->
                                                        </td> 
                                                    </tr>
                                                <?php 
                                                        $j++; 
                                                    endforeach;
                                                else: ?>
                                                <p> No hay Roles para mostrar </p>
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
