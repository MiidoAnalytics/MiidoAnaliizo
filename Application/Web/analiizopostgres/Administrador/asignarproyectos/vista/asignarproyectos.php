<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            function eliminar(data){
                var r = confirm("¿Ralmente desea eliminar este registro?");
                if (r == true) {
                    window.location.href = 'deleteproyectouser.php?idusuarios='+document.getElementById('iduser'+data).value+'&intidproyecto='+document.getElementById('idpro'+data).value;
                } else {
                    window.location = self.location;
                } 
            }
        </script>
        <title> Listar Proyectos por Usuario </title>

    </head>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Proyecto Ususario</h1>
                    </div>                    
                </div>

                <div class="row">

                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Proyecto por Usuario
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <p> <a href="../controlador/asignaruserproyectos.php"> Crear Nueva Asignación </a> </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Id Usuario</th>
                                                <th>Username</th>
                                                <th>Id Proyecto</th>
                                                <th>Nombre Proyecto</th>
                                                <th>Usuario Registra</th>
                                                <th>Fecha Registro</th>
                                                <th>Fecha Modificación</th>
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

                                            <?php if (count($proyectosuser) > 0):
                                                $i = 1;
                                                foreach ($proyectosuser as $item): ?>

                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['idusuarios']; ?></td>
                                                        <td><?php echo $item['strlogin']; ?></td>
                                                        <td><?php echo $item['intidproyecto']; ?></td>
                                                        <td><?php echo $item['nombre']; ?></td>
                                                        <td><?php echo $item['strlogin']; ?></td>
                                                        <td><?php echo $item['dtcreatedate']; ?></td>
                                                        <td><?php echo $item['dtmodifieddate']; ?></td>
                                                        <td>
                                                            <form name="fVerPre" action="javascript:eliminar(<?php echo $i; ?>)" method="GET"> 
                                                                <input type="hidden" id="iduser<?php echo $i; ?>" value="<?php echo $item['idusuarios'] ?>">
                                                                <input type="hidden" id="idpro<?php echo $i; ?>" value="<?php echo $item['intidproyecto'] ?>">
                                                                <input type="submit" class="btn btn-success2" value="Eliminar" style="width:95%"/>     
                                                            </form>
                                                        </td> 
                                                    </tr>
                                                <?php
                                                    $i++; 
                                                endforeach; ?>
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
        </div>

        <?php include("../../../sitemedia/html/scriptpie.php"); ?>    

    </body>

</html>
