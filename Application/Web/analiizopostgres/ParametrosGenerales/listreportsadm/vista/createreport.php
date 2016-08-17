<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>	

        <title>Crear reporte </title>

    </head>
    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div id="banner">

                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración - Reportes Excel</h1>
                    </div>                    
                </div>

                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Crear reporte Excel
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

                            
                            <form method="post" action="../controlador/createreport.php" class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label for="strReportName" class="col-lg-2 control-label">Nombre</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="strReportName" id="strReportName" placeholder="Nombre del Reporte" value="<?php echo $report->getReportName(); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="strDescription" class="col-lg-2 control-label">Descripción</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="strDescription" id="strDescription" placeholder="Detalle del Reporte" value="<?php echo $report->getDescription() ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="strQuery" class="col-lg-2 control-label" class="form-control">Consulta</label>
                                    <textarea name="strQuery" id="strQuery" required>
                                        <?php echo $report->getQuery(); ?>
                                    </textarea>
                                </div>
                                <?php if ($report->getIdReport()): ?>
                                    <input type="hidden" name="reporte_id" value="<?php echo $report->getIdReport(); ?>" />
                                <?php endif; ?>

                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button type="submit" class="btn btn-success" value="Guardar">Guardar</button>                    
                                        <a href="../controlador/listreportsadm.php"><button type="button" class="btn btn-success">Cancelar</button></a>
                                    </div>
                                </div>
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

