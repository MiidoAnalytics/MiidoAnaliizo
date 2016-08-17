<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            function eliminar(data){
                var r = confirm("¿Ralmente desea eliminar este registro? Si elimina este registro se eliminarán las relaciones del encuestador con la encuesta asignada.");
                if (r == true) {
                    window.location.href = 'deleteinterviewerencuesta.php?intidinterviewer='+document.getElementById('idint'+data).value+'&intidestructura='+document.getElementById('idEnc'+data).value+'&intidProyecto='+document.getElementById('idPro'+data).value;
                } else {
                    window.location = self.location;
                } 
            }

        </script>
        
        <title> Encuestas Asignadas </title>
    </head>

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Encuestas</h1>
                    </div>                    
                </div>

                <div class="row">

                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Encuestas por Encuestadores
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <p> <a href="../controlador/guardarinterencuesta.php"> Crear nueva Asignación </a> </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Id Encuestador</th>
                                                <th>Username</th>
                                                <th>Encuesta</th>
                                                <th>Proyecto</th>
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

                                            <?php if (count($allRegisters) > 0):
                                                $i = 1;
                                                foreach ($allRegisters as $item): ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['intidinterviewer']; ?></td>
                                                        <td><?php echo $item['strusername']; ?></td>
                                                        <td><?php echo $item['encuestaname']; ?></td>
                                                        <td><?php echo $item['proyectoname']; ?></td>
                                                        <td><?php echo $item['strlogin']; ?></td>
                                                        <td><?php echo $item['dtcreatedate']; ?></td>
                                                        <td><?php echo $item['dtmodifieddt']; ?></td>
                                                        <td>
                                                            <form name="fVerPre" action="javascript:eliminar(<?php echo $i; ?>)" method="GET"> 
                                                                <input type="hidden" id="idint<?php echo $i; ?>" value="<?php echo $item['intidinterviewer'] ?>">
                                                                <input type="hidden" id="idEnc<?php echo $i; ?>" value="<?php echo $item['intidestructura'] ?>">
                                                                <input type="hidden" id="idPro<?php echo $i; ?>" value="<?php echo $item['intidproyecto'] ?>">
                                                                <input type="submit" class="btn btn-success2" value="Eliminar" style="width:95%"/>     
                                                            </form>
                                                            <!-- <a href="../controlador/deleteusuarios.php?idusuarios=<?php //echo $item['idusuarios'] ?>"> Eliminar </a> -->
                                                        </td> 
                                                    </tr>
                                                <?php 
                                                    $i++;
                                                endforeach;
                                            else: ?>
                                                <p> No hay Registros para mostrar </p>
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
