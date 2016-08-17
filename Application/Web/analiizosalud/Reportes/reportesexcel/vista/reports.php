<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Miido - Analiizo</title>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>        
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <script>
            window.onload = function () {
                document.getElementById('proyecto').focus();
            }
            function encPro(inputString) {
                $.post("../../../Administrador/asignacionencuestas/controlador/encuestaproyecto.php", {
                    proyecto:document.getElementById('proyecto').value 
                }, function (data) {
                    $('#encuesta').html(data);
                });
            } 

            function munEncuestados(inputString) {
                $.post("../../../Informedeindicadores/epidemiologicosbasicos/controlador/municpiosencuestas.php", {
                    proyecto:document.getElementById('proyecto').value,
                    encuesta:document.getElementById('encuesta').value
                }, function (data) {
                    $('#municipio').html(data);
                });
            } 

            function mostrarEncuestas() {
                proyecto = document.getElementById('proyecto').value;
                encuesta = document.getElementById('encuesta').value;
                municipio = document.getElementById('municipio').value;
                var encuestaName= $('#encuesta option:selected').text();
                var muniName= $('#municipio option:selected').text();
                if (proyecto == '')
                {
                    alert('Por favor seleccione un proyecto para continuar.');
                    document.getElementById('proyecto').focus();
                }else if(encuesta == ''){
                    alert('Por favor seleccione una encuesta para continuar.');
                    document.getElementById('encuesta').focus();
                }
                else
                {
                    window.location = ("reportesexcel.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName + "&municipio=" + municipio+ "&muniName=" + muniName);
                }
            }
            function enviar(inputString) {
            console.log(inputString);
                for (var i = 0; i < 200; i++) {
                    document.getElementById('D'+i).value = inputString;
                    document.getElementById('M'+i).value = inputString;
                }
            }
            function MostrarPreloader () {
                document.getElementById('preload').style.display = 'block';
            }
            function descargar(data){
                var intervalo = setInterval(function(){
                var cookie = document.cookie;
                if (cookie.search('desIni') != -1) {
                    document.getElementById('preload').style.display = 'none';    
                    document.cookie = 'desIni=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
                    clearInterval(intervalo);       
                };    
                },1000)
                    encuesta:document.getElementById('encuesta').value
                location.href = 'reportExcel.php?town_id='+document.getElementById('D'+data).value+'&intIdReport='+document.getElementById('D_'+data).value+'&proyecto='+document.getElementById('proje').value+'&encuesta='+document.getElementById('encu').value;
                
                return false;       
            }
        </script>

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

    <body onmousemove ="contador();" onkeydown="contador();" style="background-image: url('');background-repeat: no-repeat; background-position: left bottom; background-size: 100% auto;">
        <div id="wrapper">
            <?php include("../../../Administrador/menu/controlador/menu.php"); ?>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Informes Excel</h1>
                    </div>
                </div>   
                <!-- seleccionar proyecto encuesta -->
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="proyecto" class="col-lg-2 control-label">Proyecto: </label>
                                        <select name='proyecto' id='proyecto' class='form-control' required onchange="encPro();">
                                            <option value="" selected="selected">SELECCIONE</option> 
                                            <?php foreach ($proyectos as $item): ?>
                                                <option value="<?php echo $item['intidproyecto']; ?>"><?php echo $item['nombre']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="encuesta" class="col-lg-2 control-label">Encuesta: </label>
                                        <select name='encuesta' id='encuesta' class='form-control' required required onchange="munEncuestados();">
                                             <option value="" selected="selected">SELECCIONE</option>
                                        </select> 
                                    </div>
                                </div>
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="municpio" class="col-lg-2 control-label">Municpio: </label>
                                        <select name='municipio' id='municipio' class='form-control'> 
                                            <option value="<?php echo '0'; ?>" selected="selected">TODOS LOS MUNICIPIOS</option> 
                                        </select> 
                                    </div>
                                </div> 
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success" onclick="mostrarEncuestas()" style="margin: 2rem; margin-left: 12rem;">Aceptar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="divEncSel" style="display: none;">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            Encuesta Seleccionada
                                        </div>                               
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo strtoupper($namePoll); ?></th>
                                                            <th><?php echo $muniName; ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabla de reportes -->
                <div class="row" id="divGraTablas" style="display: none;">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Reportes Excel
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">

                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Nombre Reporte</th>
                                                <th>Descripción</th>
                                                <th style="display: none">Usuario Registra</th>
                                                <th style="display: none">Fecha Registro</th>
                                                <th style="display: none">Fecha Modificado</th>                                                
                                                <th>Descargar</th>
                                                <th>Mapa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (count($reports) > 0):
                                                $i = 0;
                                                ?>
                                                <?php foreach ($reports as $item): ?>
                                                    <tr class="odd gradeX">
                                                        <td><?php echo $item['intidreport']; ?></td>
                                                        <td><?php echo $item['strreportname']; ?></td>
                                                        <td><?php echo $item['strdescription']; ?></td>
                                                        <td style="display: none"><?php echo $item['strregistereduser']; ?></td>
                                                        <td style="display: none"><?php echo $item['dtcreatedate']; ?></td>
                                                        <td style="display: none"><?php echo $item['dtmodifieddate']; ?></td>
                                                        <td align="center">
                                                            <form name="fVerPre" action="javascript:descargar(<?php echo $i; ?>)" method="GET"> 
                                                                <input type="hidden" name="intIdReport" id="D_<?php echo $i; ?>" value="<?php echo $item['intidreport']; ?>">
                                                                <input type="hidden" name="town_id" id="D<?php echo $i; ?>" value="<?php echo $municipio; ?>"/>
                                                                <input type="hidden" name="proje" id="proje" value="<?php echo $proyecto; ?>"/>
                                                                <input type="hidden" name="encu" id="encu" value="<?php echo $encuesta; ?>"/>
                                                                <input type="submit" name="VerPre" value="Descargar" style="width:95%" class="odd gradeX" onclick="MostrarPreloader();"/>       
                                                            </form>  
                                                        </td>

                                                        <td align="center">
                                                            <form name="FverMapa" action="reportemapas.php" method="GET"> 
                                                                <input type="hidden" name="intIdReport" value="<?php echo $item['intidreport']; ?>">
                                                                <input type="hidden" name="town_id" id="M<?php echo $i; ?>" value="<?php echo $municipio; ?>"/>
                                                                <input type="hidden" name="proje" id="proje" value="<?php echo $proyecto; ?>"/>
                                                                <input type="hidden" name="encu" id="encu" value="<?php echo $encuesta; ?>"/>
                                                                <input type="submit" name="VerPre" formtarget="_blank" value="Ver" style="width:95%" class="odd gradeX"/>       
                                                            </form>  
                                                        </td>

                                                    </tr>
                                                    <?php $i++;
                                                endforeach;
                                                ?>
                                        <?php else: ?>
                                            <p> No hay Reportes para mostrar </p>
                                            <?php endif; ?>
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
        <div id="preload" class = "d_preloader">
            <div class ="d_background">
            </div>
            <div class ="d_container">
                <p>
                    Cargando...
                </p>
            </div>
        </div>
    </body>
<?php 
    if ($namePoll != '' && $muniName): ?>
?>  <script type="text/javascript">
        document.getElementById('divEncSel').style.display="block";
        document.getElementById('divGraTablas').style.display = "block";
    </script>
<?php
    else:
?>
        <script type="text/javascript">
            document.getElementById('divEncSel').style.display="none";
            document.getElementById('divGraTablas').style.display = "none";
        </script>
<?php
    endif;
?>
</html>
