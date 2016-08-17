<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>

        <title> Guardar departamento </title>

    </head>

    <body onmousemove ="contador();"  style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">

        <div id="wrapper">

            <?php
            include("../../../Administrador/menu/controlador/menu.php");
            require_once '../../pais/modelo/classpais.php';
            $paises = Pais::recuperarTodos();
            ?>

            <div id="page-wrapper">

                <div id="banner">

                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Departamentos</h1>
                    </div>                    
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Guardar Departamentos
                            </div>

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <form method="post" action="../controlador/guardardepartamento.php" role="form">
                                            <div class="form-group">
                                                <label>País</label>
                                                <select name='strCodCountry' id='strCodCountry' class='form-control' required>
                                                    <?php if ($countryName and $codCountry): ?>
                                                        <option value="<?php echo $countryName; ?>" selected="selected"><?php echo $codCountry ?></option>
                                                    <?php endif; ?>     
                                                    <?php foreach ($paises as $item): ?>
                                                        <option value="<?php echo $item['strcodcountry']; ?>"><?php echo $item['strcountryname']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Código</label>
                                                <input type="text" class="form-control" name="strCodDepartamento" id="strCodDepartamento" placeholder="Código Departamento" value="<?php echo $departamento->getStrCodDepartament() ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Nombre</label>
                                                <input type="text" class="form-control" name="strNombreDepartamento" id="strNombreDepartamento" placeholder="Nombre Departamento" value="<?php echo $departamento->getStrNombreDepartament() ?>" required>
                                            </div>

                                            <?php if ($departamento->getIntIdDepartament()): ?>
                                                <input type="hidden" name="departamento_id" value="<?php echo $departamento->getIntIdDepartament() ?>" />
                                            <?php endif; ?>

                                            <button type="submit" class="btn btn-success" value="Guardar">Guardar</button>                    
                                            <a href="../controlador/departamento.php"><button type="button" class="btn btn-success">Cancelar</button></a>
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
