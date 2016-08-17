<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>

        <title> Guardar enfermedad </title>

    </head>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>

            <div id="page-wrapper">

                <div id="banner">

                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Enfermedades</h1>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Guardar Enfermedad
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action="../controlador/guardardisease.php" role="form">
                                            <div class="form-group">
                                                <label>Código</label>
                                                <input type="text" class="form-control" name="strCodDisease" id="strCodDisease" placeholder="Código Enfermedad" value="<?php echo $disease->getCodDisease(); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Nombre</label>
                                                <input type="text" class="form-control" name="strNombreDisease" id="strNombreDisease" placeholder="Nombre Enfermedad" value="<?php echo $disease->getNombreDisease(); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Clasificación</label>
                                                <input type="text" class="form-control" name="intIdclassification" id="intIdclassification" placeholder="Nombre Clasificación Enfermedad" value="" disabled="disabled">
                                            </div>
                                            <div class="form-group">
                                                <label>Complemento</label>
                                                <input type="text" class="form-control" name="intIdclassification" id="intIdclassification" placeholder="Nombre Complemento Enfermedad" value=""disabled="disabled">
                                            </div>

                                            <?php if ($disease->getIdDisease()): ?>
                                                <input type="hidden" name="disease_id" value="<?php echo $disease->getIdDisease() ?>" />
                                            <?php endif; ?>

                                            <button type="submit" class="btn btn-success" value="Guardar">Guardar</button>                    
                                            <a href="../controlador/disease.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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
