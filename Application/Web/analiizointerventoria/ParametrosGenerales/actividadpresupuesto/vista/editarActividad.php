<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fActivity.cantidad.focus();
                document.fActivity.removeEventListener('submit', validarFormulario);
                document.fActivity.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fActivity;
                validartexto(formulario);
            }    
        </script>
        <title> Editar Actividad </title>

    </head>

    <body onmousemove ="contador();"  style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
            <?php
            include("../../../Administrador/menu/controlador/menu.php");
            ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Actividades</h1>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Guardar Actividad - <?php echo $actpre->getStrNombreActividad();?>
                            </div>

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action="../controlador/editarActividad.php" role="form" name="fActivity">
                                            <div class="form-group">
                                                <label>Cantidad Contractual</label>
                                                <input type="number" class="form-control" name="cantidad" id="cantidad" placeholder="Cantidad Contractual" value="<?php echo $actpre->getCantidadContractual() ?>" min="0" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Valor Unitario</label>
                                                <input type="text" class="form-control" name="valoruni" id="valoruni" placeholder="Valor Unitario" value="<?php echo $actpre->getValorUnitario() ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Fecha programada de Finalización</label>
                                                <?php
                                                    $tem1 = $actpre->getFechaPrograTermi();
                                                    if($tem1 != null){
                                                        $dateini = date_create($tem1);
                                                        $dateini2 = date_format($dateini, 'Y-m-d');
                                                    }
                                                ?>
                                                <input type="date" class="form-control" name="fechafinaprogra" id="fechafinaprogra" value="<?php echo $dateini2; ?>" required>
                                            </div>
                                            <?php if ($actpre->getIntIdActividad()): ?>
                                                <input type="hidden" name="actividad_id" value="<?php echo $actpre->getIntIdActividad() ?>" />
                                                <input type="hidden" name="proyecto_id" value="<?php echo $actpre->getIdProyecto() ?>" />
                                                <input type="hidden" name="encuesta_id" value="<?php echo $actpre->getIdEncuesta() ?>" />
                                            <?php endif; ?>

                                            <button type="submit" class="btn btn-success" value="Guardar">Guardar</button>                    
                                            <a href="actividadpresupuesto.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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
