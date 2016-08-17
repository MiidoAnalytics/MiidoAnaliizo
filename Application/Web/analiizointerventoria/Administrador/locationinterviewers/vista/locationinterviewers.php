<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>

        <title> Asignación de Lugares</title>

    </head>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Asignación de Lugares</h1>
                    </div>                    
                </div>

                <div class="row">

                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Asignación de Lugares
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <p> <a href="../controlador/guardarlocationinterviewers.php"> Asignar Lugar a un Encuestador </a> </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Id Registro</th>
                                                <th>Id Entrevistador</th>
                                                <th>Usuario Entrevistador</th>
                                                <th>Cod Municipio</th>
                                                <th>Nombre Municipio</th>
                                                <th>Cod Departamento</th>
                                                <th>Usuario Registra</th>
                                                <th>Fecha Registro</th>
                                                <th>Fecha Modificación</th>
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
                                            ?>

                                            <?php if (count($LocationInterviewersAll) > 0): ?>
                                                <?php foreach ($LocationInterviewersAll as $item): ?>

                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['intidlocationinterviewer']; ?></td>
                                                        <td><?php echo $item['intidinterviewer']; ?></td>
                                                        <td><?php echo $item['strusername']; ?></td>
                                                        <td><?php echo $item['strcodtown']; ?></td>
                                                        <td><?php echo $item['strtownname']; ?></td>
                                                        <td><?php echo $item['strcoddepartament']; ?></td>
                                                        <td><?php echo $item['strregistereduser']; ?></td>
                                                        <td><?php echo $item['dtcreatedate']; ?></td>
                                                        <td><?php echo $item['dtmodifieddate']; ?></td>
                                                    </tr>
        <?php endforeach; ?>
    <?php else: ?>
                                                <p> No hay municipios para mostrar </p>
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
