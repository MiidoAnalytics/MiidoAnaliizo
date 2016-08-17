<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            function eliminar(data){
                var r = confirm("¿Ralmente desea eliminar este registro? Si elimina este registro se eliminarán las relaciones con el encuestador asignado");
                if (r == true) {
                    window.location.href = 'deletetablets.php?tablet_id='+document.getElementById('idint'+data).value;
                } else {
                    window.location = self.location;
                } 
            }
        </script>
        <title> Listar Tablets </title>

    </head>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Tablets</h1>
                    </div>                    
                </div>

                <div class="row">

                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Tablets
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <p> <a href="../controlador/guardartablets.php"> Crear nueva Tablet </a> </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Id Tablet</th>
                                                <th>Nombre Tablet</th>
                                                <th>Llave Tablet</th>
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

                                            <?php if (count($tabletsAll) > 0):
                                                $i = 0;
                                                foreach ($tabletsAll as $item): ?>

                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['intidtablet']; ?></td>
                                                        <td><?php echo $item['strtabletname']; ?></td>
                                                        <td><?php echo $item['strtabletkey']; ?></td>
                                                        <td><?php echo $item['strregistereduser']; ?></td>
                                                        <td><?php echo $item['dtcreatedate']; ?></td>
                                                        <td><?php echo $item['dtmodifieddate']; ?></td>
                                                        <td><a href="../controlador/guardartablets.php?tablet_id=<?php echo $item['intidtablet'] ?>"> Editar </a></td>
                                                        <td>
                                                            <form name="fDelTab" action="javascript:eliminar(<?php echo $i; ?>)" method="GET"> 
                                                                <input type="hidden" id="idint<?php echo $i; ?>" value="<?php echo $item['intidtablet'] ?>">
                                                                <input type="submit" class="btn btn-success2" value="Eliminar" style="width:95%"/>     
                                                            </form>
                                                            <!-- <a href="../controlador/deletetablets.php?tablet_id=<?php //echo $item['intidtablet'] ?>"> Eliminar </a> -->
                                                        </td> 
                                                    </tr>
                                                <?php 
                                                    $i++;
                                                endforeach;
                                            else: ?>
                                                <p> No hay Cups para mostrar </p>
                                            <?php endif;
                                        } ?>
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
