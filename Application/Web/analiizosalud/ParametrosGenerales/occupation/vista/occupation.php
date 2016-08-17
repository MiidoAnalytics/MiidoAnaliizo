<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <title> Listar Ocupaciones </title>
    </head>
    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Ocupaciones</h1>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Ocupaciones
                            </div>
                            <div class="panel-body">
                                <p> <a href="../controlador/guardaroccupation.php"> Crear nueva Ocupación </a> </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Id Ocupación</th>
                                                <th>Código Ocupación</th>
                                                <th>Nombre Ocupación</th>
                                                <th>Usuario Registra</th>
                                                <th>Fecha Registro</th>
                                                <th>Fecha Modificado</th>
                                                <th>Editar</th>
                                                <th>Eliminar</th>
                                            </tr>
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

                                            <?php if (count($Occupations) > 0): ?>
                                                <?php foreach ($Occupations as $item): ?>

                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['intidoccupation']; ?></td>
                                                        <td><?php echo $item['strcodeoccupation']; ?></td>
                                                        <td><?php echo $item['strnameoccupation']; ?></td>
                                                        <td><?php echo $item['strregistereduser']; ?></td>
                                                        <td><?php echo $item['dtcreatedate']; ?></td>
                                                        <td><?php echo $item['dtmodifieddate']; ?></td>
                                                        <td><a href="../controlador/guardaroccupation.php?Occupation_id=<?php echo $item['intidoccupation'] ?>"> Editar </a></td>
                                                        <td><a href="../controlador/deleteoccupation.php?Occupation_id=<?php echo $item['intidoccupation'] ?>"> Eliminar </a></td> 
                                                    </tr>
        <?php endforeach; ?>
    <?php else: ?>
                                                <p> No hay ocupaciones para mostrar </p>
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
