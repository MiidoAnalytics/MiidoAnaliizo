<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>

        <title> Asignación de Tabletas </title>

    </head>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Asignación de Tabletas</h1>
                    </div>                    
                </div>

                <div class="row">

                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Asignación de Tabletas
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">

                                <p> <a href="../controlador/guardarinterviewersxtablets.php"> Crear nueva asignación </a> </p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Id Entrevistador</th>
                                                <th>Nombre Entrevistador</th>
                                                <th>Nombre Usuario</th>
                                                <th>Id Tableta</th>
                                                <th>Nombre Tableta</th>
                                                <th>Llave Tableta</th>
                                                <th>Usuario Registra</th>
                                                <th>Fecha Registro</th>
                                                <th>Liberar Tablet</th>
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

                                            <?php if (count($InterviewersxtabletsAll) > 0): ?>
                                                <?php foreach ($InterviewersxtabletsAll as $item): ?>

                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['intidinterviewer']; ?></td>
                                                        <td><?php echo $item['strnames']; ?></td>
                                                        <td><?php echo $item['strusername']; ?></td>
                                                        <td><?php echo $item['intidtablet']; ?></td>
                                                        <td><?php echo $item['strtabletname']; ?></td>
                                                        <td><?php echo $item['strtabletkey']; ?></td>
                                                        <td><?php echo $item['strregistereduser']; ?></td>
                                                        <td><?php echo $item['dtcreatedate']; ?></td>
                                                        <td><a href="../controlador/freeInterviewersxtablets.php?interviewer_id=<?php echo $item['intidinterviewer'] ?>&tablet_id=<?php echo $item['intidtablet'] ?>"> Liberar Tablet </a></td>
                                                    </tr>
        <?php endforeach; ?>
    <?php else: ?>
                                                <p>  </p>
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
