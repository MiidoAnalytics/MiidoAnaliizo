<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>

        <title> Guardar Municipio </title>

    </head>

    <body onmousemove ="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php
            include("../../../Administrador/menu/controlador/menu.php");
            require_once '../../departamento/modelo/classdepartamento.php';
            $departamentos = Departament::recuperarTodos();
            ?>

            <div id="page-wrapper">

                <div id="banner">

                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Municipios</h1>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Guardar Municipios
                            </div>

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action="../controlador/guardartown.php" role="form">
                                            <div class="form-group">
                                                <label>Departamento</label>
                                                <select name='strCodDepartament' id='strCodDepartament' class='form-control' required>
                                                    
                                                    <?php foreach ($departamentos as $item): ?>
                                                        <option value="<?php echo $item['strcoddepartament']; ?>"><?php echo $item['strdepartamentname']; ?></option>
<?php endforeach; ?>
                                                </select>  
                                            </div>
                                            <div class="form-group">
                                                <label>Código</label>
                                                <input type="text" class="form-control" name="strCodTown" id="strCodTown" placeholder="Código Municipiio" value="<?php echo $municipio->getCodTown() ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Nombre</label>
                                                <input type="text" class="form-control" name="strTownName" id="strTownName" placeholder="Nombre Municipio" value="<?php echo $municipio->getNombreTown() ?>" required>
                                            </div>

                                            <?php if ($municipio->getIdTown()): ?>
                                                <input type="hidden" name="municipio_id" value="<?php echo $municipio->getIdTown() ?>" />
<?php endif; ?>

                                            <button type="submit" class="btn btn-success" value="Guardar">Guardar</button>                    
                                            <a href="../controlador/town.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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
