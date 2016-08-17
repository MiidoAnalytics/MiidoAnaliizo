<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>    
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>	

        <title>Reportes Excel </title>

    </head>
    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración - Reportes Excel</h1>
                    </div>                    
                </div>
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Reportes Excel
                        </div>
                        <div class="panel-body">
                            <p> <a href="../controlador/createreport.php"> Crear nuevo Reporte </a> </p>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Nombre Reporte</th>
                                            <th>Descripción</th>
                                            <th>Consulta</th>
                                            <th>Usuario Registra</th>
                                            <th>Fecha Registro</th>
                                            <th>Fecha Modificado</th>
                                            <th>Editar</th>
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
                                        <?php if (count($reports) > 0): ?>
                                            <?php foreach ($reports as $item): ?>
                                                <tr class="odd gradeX">
                                                    <td><?php echo $item['intidreport']; ?></td>
                                                    <td><?php echo $item['strreportname']; ?></td>
                                                    <td><?php echo $item['strdescription']; ?></td>
                                                    <td><?php echo $item['strquery']; ?></td>
                                                    <td><?php echo $item['strregistereduser']; ?></td>
                                                    <td><?php echo $item['dtcreatedate']; ?></td>
                                                    <td><?php echo $item['dtmodifieddate']; ?></td>
                                                    <td><a href="../controlador/createreport.php?reporte_id=<?php echo $item['intidreport'] ?>"> Editar </a></td>
                                                </tr>
        <?php endforeach; ?>
    <?php else: ?>
                                            <p> No hay Reportes para mostrar </p>
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

