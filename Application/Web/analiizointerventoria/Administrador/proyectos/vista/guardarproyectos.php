<?php if (!defined('CONTROLADOR')) exit; ?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                document.fProyectos.cateproject.focus();
                document.fProyectos.removeEventListener('submit', validarFormulario);
                document.fProyectos.addEventListener('submit', validarFormulario);
            }
    
            function validarFormulario(evObject) {
                evObject.preventDefault();
                var formulario = document.fProyectos;
                validartexto(formulario);
            }  
            /**Funcion que trae los municipios por AJAX*/
            function lookup(inputString) {
                $.post("../vista/municipiosselect.php", {queryString: "" + inputString + ""}, 
                    function (data) {
                    $('#strCodTown').html(data);
                });
            }  
        </script>
        <title> Guardar Proyecto </title>

    </head>
    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
        <?php
            include("../../../Administrador/menu/controlador/menu.php");
            $categorias = Proyectos::getAllCategories();
            require_once '../../../ParametrosGenerales/departamento/modelo/classdepartamento.php';
            $departamentos = Departament::recuperarTodos();
        ?>

            <div id="page-wrapper">

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Administración Proyecto</h1>
                    </div>                    
                </div>
                <form method="post" action="../controlador/guardarproyectos.php" role="form" name="fProyectos">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Información General Proyecto
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-10">
                                            <div class="form-group">
                                                <label for="cateproject" class="col-lg-2 control-label">Categoría</label>
                                                <select name='cateproject' id='cateproject' class='form-control' onchange="lookup(this.value);" required>
                                                    <?php
                                                        $cat = $proyecto->getCategoria();
                                                        if($cat){ 
                                                            $catbyid = Proyectos::getCategoriesxId($cat);
                                                            foreach ($catbyid as $key) {
                                                            ?>
                                                            <option value="<?php echo $cat; ?>" selected="selected"><?php echo $key['nombre']; ?></option> 
                                                    <?php   } 
                                                        }else{
                                                    ?>
                                                    <option value="<?php echo '0'; ?>" selected="selected">SELECCIONE</option> 
                                                    <?php 
                                                        }
                                                        foreach ($categorias as $key): ?>
                                                        <option value="<?php echo $key['intidcategoria']; ?>"><?php echo $key['strnombre']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>   
                                            </div>
                                            <div class="form-group">
                                                <label>Nombre Proyecto</label>
                                                <input type="text" class="form-control" name="nombre" id="nombre"
                                                placeholder="Nombre Proyecto" value="<?php echo $proyecto->getNombre(); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Supervisor Proyecto</label>
                                                <input type="text" class="form-control" name="supervisor" id="supervisor"
                                                placeholder="Nombre supervisor" value="<?php echo $proyecto->getSupervisor(); ?>" required>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Plazo Inicial</label>
                                                    <input type="number" class="form-control" name="plazoIni" id="plazoIni" placeholder="Plazo Inicial en número de meses" 
                                                    min="0" value="<?php echo $proyecto->getPlazoInicial(); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Fecha Inicio</label>
                                                    <?php
                                                        $tem1 = $proyecto->getFechaInicial();
                                                        if($tem1 != null){
                                                            $dateini = date_create($tem1);
                                                            $dateini2 = date_format($dateini, 'Y-m-d');
                                                        }
                                                    ?>
                                                    <input type="date" class="form-control" name="fechaIni" id="fechaIni" value="<?php echo $dateini2; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Fecha Suspensión</label>
                                                    <?php
                                                        $tem2 = $proyecto->getFechaSuspension();
                                                        if($tem2 != null){
                                                            $dateSus = date_create($tem2);
                                                            $dateSus2 = date_format($dateSus, 'Y-m-d');
                                                        }
                                                    ?>
                                                    <input type="date" class="form-control" name="fechaSus" id="fechaSus" value="<?php echo $dateSus2; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Fecha Reinicio</label>
                                                    <?php
                                                        $tem3 = $proyecto->getFechaReinicio();
                                                        if($tem3 != null){
                                                            $dateRei = date_create($tem3);
                                                            $dateRei2 = date_format($dateRei, 'Y-m-d');
                                                        }
                                                    ?>
                                                    <input type="date" class="form-control" name="fechaReini" id="fechaReini" value="<?php echo $dateRei2; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Fecha Terminación</label>
                                                    <?php
                                                        $tem4 = $proyecto->getFechaFin();
                                                        if($tem4 != null){
                                                            $dateEnd = date_create($tem4);
                                                            $dateEnd2 = date_format($dateEnd, 'Y-m-d');
                                                        }
                                                    ?>
                                                    <input type="date" class="form-control" name="fechaFin" id="fechaFin" value="<?php echo $dateEnd2; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    Localización del Contrato
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="Departament" class="col-lg-2 control-label">Departamento</label>
                                                    <select name='strCodDepartament' id='strCodDepartament' class='form-control' onchange="lookup(this.value);" required>
                                                        <?php
                                                        $codMun = $proyecto->getcodTown();
                                                        if($codMun){ 
                                                            $munibycod = Proyectos::getDepMunbycod($codMun);
                                                            foreach ($munibycod as $key) {
                                                            ?>
                                                            <option value="<?php echo $key['strcoddepartament']; ?>" selected="selected"><?php echo $key['strdepartamentname']; ?></option> 
                                                    <?php   } 
                                                        }else{
                                                    ?>
                                                    <option value="<?php echo '0'; ?>" selected="selected">SELECCIONE</option> 
                                                    <?php 
                                                        } 
                                                            foreach ($departamentos as $item): ?>
                                                            <option value="<?php echo $item['strcoddepartament']; ?>"><?php echo $item['strdepartamentname']; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>   
                                                </div>
                                                <div class="form-group">
                                                    <label for="strCodTowns" class="col-lg-2 control-label">Municipio</label>
                                                    <select name='strCodTown' id='strCodTown' class='form-control' required>
                                                    <?php
                                                        $codMun = $proyecto->getcodTown();
                                                        if($codMun){ 
                                                            $munibycod = Proyectos::getDepMunbycod($codMun);
                                                            foreach ($munibycod as $key) {
                                                            ?>
                                                            <option value="<?php echo $key['strcodtown']; ?>" selected="selected"><?php echo $key['strtownname']; ?></option> 
                                                        <?php   } 
                                                        }else{
                                                    ?>
                                                            <option value="<?php echo '0'; ?>" selected="selected">SELECCIONE</option> 
                                                        <?php
                                                        }
                                                        ?>

                                                        </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Barrio/Vereda</label>
                                                    <input type="text" class="form-control" name="barrioVereda" id="barrioVereda"
                                                    placeholder="Barrio o Vereda" value="<?php echo $proyecto->getBarrioVereda(); ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    Contrato de Interventoría
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Número de contrato</label>
                                                    <input type="number" class="form-control" name="numContInter" id="planumContInterzoIni" placeholder="Número de contrato de Interventoría" 
                                                    min="0" value="<?php echo $proyecto->getNumContraInter(); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Interventor</label>
                                                    <input type="text" class="form-control" name="nominter" id="nominter"
                                                    placeholder="Nombre del interventor" value="<?php echo $proyecto->getInterName(); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Valor Inicial</label>
                                                    <input type="number" class="form-control" name="valorIniInter" id="valorIniInter" placeholder="Valor Inicial" 
                                                    min="0" value="<?php echo $proyecto->getValorIniInter(); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Valor Adición (es)</label>
                                                    <input type="number" class="form-control" name="valorAdiInter" id="valorAdiInter" placeholder="Valor Adición (es)" 
                                                    min="0" value="<?php echo $proyecto->getValorAdiInter(); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    Contrato de Obra
                                                </div>
                                            </div>
                                             <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Número de contrato</label>
                                                    <input type="number" class="form-control" name="numContratista" id="numContratista" placeholder="Número de contrato de Obra" 
                                                    min="0" value="<?php echo $proyecto->getNumContraObra(); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Contratista</label>
                                                    <input type="text" class="form-control" name="nomContratista" id="nomContratista"
                                                    placeholder="Nombre del nomContratista" value="<?php echo $proyecto->getObraName(); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Valor Inicial</label>
                                                    <input type="number" class="form-control" name="valorIniObra" id="valorIniObra" placeholder="Valor Inicial" 
                                                    min="0" value="<?php echo $proyecto->getValorIniObra(); ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Valor Adición (es)</label>
                                                    <input type="number" class="form-control" name="valorAdiObra" id="valorAdiObra" placeholder="Valor Adición (es)" 
                                                    min="0" value="<?php echo $proyecto->getValorAdiObra(); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    Etapa de preconstrucción
                                                </div>
                                            </div>
                                             <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Plazo: </label>
                                                    <input type="number" class="form-control" name="plazoEtaPre" id="plazoEtaPre" placeholder="Plazo en días" 
                                                    min="0" value="<?php echo $proyecto->getplazoEtapaPre(); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    Etapa de Construcción
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Plazo: </label>
                                                    <input type="number" class="form-control" name="plazoEtaCon" id="plazoEtaCon" placeholder="Plazo en días" 
                                                    min="0" value="<?php echo $proyecto->getplazoEtapaCons(); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Fecha Inicio Etapa</label>
                                                    <?php
                                                        $temfe = $proyecto->getdateIniCon();
                                                        if($temfe != null){
                                                            $dateStartEta = date_create($temfe);
                                                            $dateStartEta2 = date_format($dateStartEta, 'Y-m-d');
                                                        }
                                                    ?>
                                                    <input type="date" class="form-control" name="fechaIniCon" id="fechaIniCon" value="<?php echo $dateStartEta2; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <?php if ($proyecto->getintidproyecto()): ?>
                                                <input type="hidden" name="intidproyecto" value="<?php echo $proyecto->getintidproyecto(); ?>" />
                                            <?php endif; ?> 
                                            <input type="submit" class="btn btn-success" value="Guardar">                  
                                            <a href="../controlador/proyectos.php"><button type="button" class="btn btn-success">Cancelar</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php include("../../../sitemedia/html/scriptpie.php"); ?>    

    </body>

</html>
