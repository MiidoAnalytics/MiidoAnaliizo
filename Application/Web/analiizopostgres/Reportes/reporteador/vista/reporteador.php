<!DOCTYPE html>
<html lang="en">

    <head>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>

        <title>Miido - Analiizo</title>
        <script type='text/javascript' src="../../../sitemedia/js/jquery-1.7.0.min.js"></script>
        <script type="text/javascript" src="../../../sitemedia/js/ActFilterControl.js"></script>
    </head>

    <?php
    @session_start();

    if (empty($_SESSION['user'])) {
        ?>

        <div class="alert alert-danger">
            El usuario no iniciado sesión, por favor ingresar por la página de login. <a href="login.php" class="alert-link">Login</a>.
        </div>
        <?php
    }
    ?>
    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('/analiizo/images/background_03.png');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Reporteador Excel - {$client_name}</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->	
                <form id="formFilter" action="descargarreporte.php" method="post" name="reporteadorForm">
                    <div class="panel-body">
                        <div class="row">
                            <div class="panel-heading">
                                <h3 class="panel-title">Filtro por:</h3>
                            </div>
                            <div class="panel panel-default">
                                    <div class="panel-body">
                            <div class="col-lg-8">
                                <select name='SelFilter' id='SelFilter' class='form-control'>                
                                    <option value="" class='form-control' selected="selected">NINGUNO</option> 
                                    <option value="DivFecEnc">POR FECHA DE ENCUESTA</option>
                                    <option value="divTownFil">POR MUNICIPIO ENCUESTADO</option> 
                                    <option value="DivGender">POR GENERO</option> 
                                    <option value="DivEncuestador">POR ENCUESTADOR</option> 
                                    <option value="DivMedicine">POR MEDICAMENTO</option> 
                                    <option value="DivDespla">POR DESPLAZADO</option> 
                                    <option value="DivMinus">POR DISCAPACITADO</option> 
                                    <option value="DivVivienda">POR VIVIENDA ES</option> 
                                    <option value="DivAgua">POR SER. ACUEDUCTO</option> 
                                    <option value="DivAlca">POR SER. ALCANTARILLADO</option> 
                                    <option value="divAge">POR EDAD</option> 
                                    <option value="divDisease">POR PATOLOGIA</option> 
                                    <option value="divEtnia">POR PERTENENCIA ETNICA</option> 
                                    <option value="divEducativo">POR NIVEL EDUCATIVO</option> 
                                    <option value="divOdont">POR CONTROL ODONTOLOGICO</option> 
                                    <option value="divGasNat">POR SER. GAS NATURAL</option> 
                                    <option value="divElec">POR SER. ENERGIA ELECTRICA</option> 
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <input type="button" name="BAddFilter" id="BAddFilter" value="Agregar" class="btn btn-lg btn-success btn-block">
                            </div>
                            </div>
                            </div>                            
                            <div class="col-lg-6">
                                <div class="panel panel-default" name='DivFecEnc' id ='DivFecEnc' style='display:none;'>
                                    <div class="panel-body">
                                        <label>Fecha encuesta:
                                            <input type="button" name="hideFecEncu" id="hideFecEncu" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                        </label>
                                        <label for="since" class="col-lg-2 control-label">Desde:</label>
                                        <input class='form-control' placeholder="dd/mm/aaaa" name="since" id="since" type="date" disabled="true" required>
                                        <label for="until" class="col-lg-2 control-label">Hasta:</label>
                                        <input class="form-control" placeholder="dd/mm/aaaa" name="until" id="until" type="date" disabled="true" required>
                                    </div>
                                </div>

                                <div class="panel panel-default" id ='DivGender' style='display:none;'>  
                                    <div class="panel-body" >
                                        <div class="checkbox">
                                            <label>Genero:
                                                <input type="button" name="hideGender" id="hideGender" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            <label>    
                                        </div>    
                                        <select name='gender' id='gender' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >SELECCIONE</option> 
                                            <option value="FEMENINO">FEMENINO</option>
                                            <option value="MASCULINO">MASCULINO</option>    
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-default" id ='DivEncuestador' style='display:none;'>
                                    <div class="panel-body" >
                                        <div class="checkbox">
                                            <label>Encuestador:
                                                <input type="button" name="hideEncues" id="hideEncues" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='interFilter' id='interFilter' class='form-control' disabled="true">                
                                            <option value="-1" class='form-control' selected="selected" >TODOS LOS ENCUESTADORES</option> 
                                            <?php
                                            if (count($TablaEncuestadorxEncuesta) > 0):
                                                foreach ($TablaEncuestadorxEncuesta as $item):
                                                    ?>
                                                    <option value="<?php echo $item['id_encuestador']; ?>"><?php echo $item['username']; ?></option>
                                                    <?php
                                                endforeach;
                                            else:
                                                ?>
                                                <p> No hay registros para mostrar </p>
                                            <?php
                                            endif;
                                            ?>   
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-default" id ='DivMedicine' style='display:none;'>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Medicamento:
                                                <input type="button" name="hideMedicine" id="hideMedicine" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='medicineFilter' id='medicineFilter' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >TODOS LOS MEDICAMENTOS</option> 
                                            <?php
                                            if (count($TablaMedicinaReportada) > 0):
                                                foreach ($TablaMedicinaReportada as $item):
                                                    ?>
                                                    <option value="<?php echo $item['descripcion_medicamento']; ?>"><?php echo $item['descripcion_medicamento']; ?></option>
                                                    <?php
                                                endforeach;
                                            else:
                                                ?>
                                                <p> No hay registros para mostrar </p>
                                            <?php
                                            endif;
                                            ?>   
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-default" id ='DivDespla' style='display:none;'>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Desplazado:
                                                <input type="button" name="hideDes" id="hideDes" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='desplazadoFilter' id='desplazadoFilter' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >TODOS</option> 
                                            <option value="SI">SI</option>
                                            <option value="NO">NO</option>  
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-default" id ='DivMinus' style='display:none;'>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Discapacitado:
                                                <input type="button" name="hideMinus" id="hideMinus" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='minusvalidoFilter' id='minusvalidoFilter' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >TODOS</option> 
                                            <option value="SI">SI</option>
                                            <option value="NO">NO</option>  
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-default" id ='DivVivienda' style='display:none;'>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Vivienda es:
                                                <input type="button" name="hideVivi" id="hideVivi" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='viviendaFilter' id='viviendaFilter' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >TODOS</option> 
                                            <?php
                                            if (count($TablaViviendaEs) > 0):
                                                foreach ($TablaViviendaEs as $item):
                                                    ?>
                                                    <option value="<?php echo $item['tipo_casa_encuesta']; ?>"><?php echo $item['tipo_casa_encuesta']; ?></option>
                                                    <?php
                                                endforeach;
                                            else:
                                                ?>
                                                <p> No hay registros para mostrar </p>
                                            <?php
                                            endif;
                                            ?>   
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-default" id ='DivAgua' style='display:none;'>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Servicio Agua:
                                                <input type="button" name="hideAgua" id="hideAgua" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='aguaFilter' id='aguaFilter' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >TODOS</option> 
                                            <option value="on">SI</option>
                                            <option value="off">NO</option>  
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-default" id ='DivAlca' style='display:none;'>    
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Servicio Alcantarillado:
                                                <input type="button" name="hideAlcan" id="hideAlcan" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='alcantarilladoFilter' id='alcantarilladoFilter' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >TODOS</option> 
                                            <option value="on">SI</option>
                                            <option value="off">NO</option>  
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="panel panel-default" id="divTownFil" style='display:none;'>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Municipio:
                                                <input type="button" name="hideTown" id="hideTown" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='town' id='town' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >TODOS LOS MUNICIPIOS</option> 
                                            <?php
                                            if (count($MunicipiosEncuestados) > 0):
                                                foreach ($MunicipiosEncuestados as $item):
                                                    ?>
                                                    <option value="<?php echo $item['codmunicipio']; ?>"><?php echo $item['nombremunicipio']; ?></option>
                                                    <?php
                                                endforeach;
                                            else:
                                                ?>
                                                <p> No hay registros para mostrar </p>
                                            <?php
                                            endif;
                                            ?>   
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-default" id="divAge" style='display:none;'>    
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Edad:
                                                <input type="button" name="hideAge" id="hideAge" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <label for="ageSince" class="col-lg-2 control-label">Desde:</label>
                                        <select name='ageSince' id='ageSince' class='form-control' disabled="true">                
                                            <option value="-1" class='form-control' selected="selected" >SELECCIONE</option> 
                                            <?php
                                            $i = -1;
                                            do {
                                                ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i+1; ?></option>
                                                <?php
                                                $i++;
                                            } while ($i <= 120);
                                            ?>   
                                        </select>
                                        <label for="ageUntil" class="col-lg-2 control-label">Desde:</label>
                                        <select name='ageUntil' id='ageUntil' class='form-control' disabled="true">                
                                            <option value="200" class='form-control' selected="selected" >SELECCIONE</option> 
                                            <?php
                                            $i = 0;
                                            do {
                                                ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                <?php
                                                $i++;
                                            } while ($i <= 120);
                                            ?>   
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-default" id="divDisease" style='display:none;'>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Enfermedad:
                                                <input type="button" name="hideDisease" id="hideDisease" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='diseaseFilter' id='diseaseFilter' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >TODOS LAS ENFERMEDADES</option> 
                                            <?php
                                            if (count($TablaEnfermedadesReportadas) > 0):
                                                foreach ($TablaEnfermedadesReportadas as $item):
                                                    $aux =$item['descripcion'];
                                                    $codEnf = explode(' ', $aux, 2);
                                                    //die($codEnf[0]);
                                                    ?>
                                                    <option value="<?php echo $codEnf[0]; ?>"><?php echo $item['descripcion']; ?></option>
                                                    <?php
                                                endforeach;
                                            else:
                                                ?>
                                                <p> No hay registros para mostrar </p>
                                            <?php
                                            endif;
                                            ?>   
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-default" id="divEtnia" style='display:none;'>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Pertenecia Etnica:
                                                <input type="button" name="hideEtnia" id="hideEtnia" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='etniaFilter' id='etniaFilter' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >TODAS</option> 
                                            <?php
                                            if (count($TablaRazaReportada) > 0):
                                                foreach ($TablaRazaReportada as $item):
                                                    ?>
                                                    <option value="<?php echo $item['raza']; ?>"><?php echo $item['raza']; ?></option>
                                                    <?php
                                                endforeach;
                                            else:
                                                ?>
                                                <p> No hay registros para mostrar </p>
                                            <?php
                                            endif;
                                            ?>   
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-default" id="divEducativo" style='display:none;'>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Nivel Educativo:
                                                <input type="button" name="hideEducativo" id="hideEducativo" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='estudiosFilter' id='estudiosFilter' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >TODOS</option> 
                                            <?php
                                            if (count($TablaAfiliadosEducacion) > 0):
                                                foreach ($TablaAfiliadosEducacion as $item):
                                                    ?>
                                                    <option value="<?php echo $item['estudios']; ?>"><?php echo $item['estudios']; ?></option>
                                                    <?php
                                                endforeach;
                                            else:
                                                ?>
                                                <p> No hay registros para mostrar </p>
                                            <?php
                                            endif;
                                            ?>   
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-default" id="divOdont" style='display:none;'>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Consulta Odontologogica:
                                                <input type="button" name="hideOdon" id="hideOdon" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='odontologiaFilter' id='odontologiaFilter' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >TODOS</option> 
                                            <option value="SI">SI</option>
                                            <option value="NO">NO</option>  
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-default" id="divGasNat" style='display:none;'>
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Servicio Gas Natural:
                                                <input type="button" name="hideGasNat" id="hideGasNat" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='gasNatFilter' id='gasNatFilter' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >TODOS</option> 
                                            <option value="on">SI</option>
                                            <option value="off">NO</option>  
                                        </select>
                                    </div>
                                </div>   
                                <div class="panel panel-default" id="divElec" style='display:none;'> 
                                    <div class="panel-body">
                                        <div class="checkbox">
                                            <label>Servicio energia Electrica:
                                                <input type="button" name="hideElec" id="hideElec" value="x" style="border-radius: 5em; right:2em; top:0em; position:absolute;">
                                            </label>
                                        </div>
                                        <select name='eneElecFilter' id='eneElecFilter' class='form-control' disabled="true">                
                                            <option value="%" class='form-control' selected="selected" >TODOS</option> 
                                            <option value="on">SI</option>
                                            <option value="off">NO</option>  
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div style="width: 100%"  class="panel-title2" align="center">
                                <button style="width: 400px;" type="submit" class="btn btn-lg btn-success btn-block">Descargar Reporte</button>    
                            </div>
                </form>
            </div>
        </div>
        <!-- /#wrapper -->

        <?php include("../../../sitemedia/html/scriptpie.php"); ?>

        <!-- jQuery -->
        <script src="../../../sitemedia/js/jquery.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="../../../sitemedia/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="../../../sitemedia/js/plugins/metisMenu/metisMenu.min.js"></script>

        <!-- DataTables JavaScript -->
        <script src="../../../sitemedia/js/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="../../../sitemedia/js/plugins/dataTables/dataTables.bootstrap.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="../../../sitemedia/js/sb-admin-2.js"></script>

    </body>

</html>
