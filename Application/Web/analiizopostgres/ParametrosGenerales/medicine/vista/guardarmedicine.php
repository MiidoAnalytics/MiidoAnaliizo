<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>

        <title> Guardar Medicamento </title>

    </head>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div id="banner">

                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Medicamentos</h1>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Guardar Medicamentos
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action="../controlador/guardarmedicine.php" role="form">
                                            <div class="form-group">
                                                <label>Código</label>
                                                <input type="text" class="form-control" name="strCodMedicine" id="strCodMedicine" placeholder="Código Medicamento" value="<?php echo $medicine->getCodMedicine() ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Nombre</label>
                                                <input type="text" class="form-control" name="strMedicineName" id="strMedicineName" placeholder="Nombre Medicamento" value="<?php echo $medicine->getNombreMedicine() ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Clasificación</label>
                                                <input type="text" class="form-control" name="intClassification" id="intClassification" placeholder="Clasificación Medicamento" value="" disabled="disabled">       
                                            </div>

                                            <div class="form-group">
                                                <label>Complemento</label>
                                                <input type="text" class="form-control" name="intIdComplement" id="intIdComplement" placeholder="Clasificación Medicamento" value="" disabled="disabled">       
                                            </div>

                                            <?php if ($medicine->getIdMedicine()): ?>
                                                <input type="hidden" name="medicine_id" value="<?php echo $medicine->getIdMedicine() ?>" />
                                            <?php endif; ?>

                                            <button type="submit" class="btn btn-success" value="Guardar">Guardar</button>                    
                                            <a href="../controlador/medicine.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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
