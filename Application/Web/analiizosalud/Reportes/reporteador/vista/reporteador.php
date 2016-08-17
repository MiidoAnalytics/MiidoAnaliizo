<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Miido - Analiizo</title>
        <?php include("../../../sitemedia/html/scriptencabezado.php"); ?>
        <script type="text/javascript" src="../../../core/inactivesession.js"></script>
        <script type='text/javascript' src="../../../sitemedia/js/jquery-1.7.0.min.js"></script>
        <script type="text/javascript" src="../../../sitemedia/js/ActFilterControl.js"></script>
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

            function mostrarEncuestas() {
                proyecto = document.getElementById('proyecto').value;
                encuesta = document.getElementById('encuesta').value;
            
                var encuestaName= $('#encuesta option:selected').text();
                var proName= $('#proyecto option:selected').text();
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
                    window.location = ("reporteador.php?proyecto=" + proyecto + "&encuesta=" + encuesta + "&namePoll=" + encuestaName + "&namePro=" + proName);
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
                    //encuesta:document.getElementById('encuesta').value
                    //location.href = 'reportExcel.php?town_id='+document.getElementById('D'+data).value+'&intIdReport='+document.getElementById('D_'+data).value+'&proyecto='+document.getElementById('proje').value+'&encuesta='+document.getElementById('encu').value;
                
                return false;       
            }
        </script>
        <style type="text/css">
            .closeDiv{
                float:right; clear: both;
            }
            .closeButton{
                border-radius: 5em; width: 2em; height: 2em;
            }
        </style>
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
                        <h1 class="page-header">Reporteador Excel</h1>
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
                                        <select name='encuesta' id='encuesta' class='form-control' required required>
                                             <option value="" selected="selected">SELECCIONE</option>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-success" onclick="mostrarEncuestas()" style="margin: 2rem; margin-left: 12rem;">Aceptar</button>
                                    </div>
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
                                                            <th><?php echo strtoupper($proName); ?></th>
                                                            <th><?php echo strtoupper($namePoll); ?></th>
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
                <!-- Tabla de reportes -->
                <div class="row" id="divGraTablas" style="display: none;">
                    <form id="formFilter" action="descargarreporte.php" method="post" name="reporteadorForm">
                        <input type="hidden" name="project" id="project" value="<?php echo $proyecto; ?>"/>
                        <input type="hidden" name="poll" id="poll" value="<?php echo $encuesta; ?>"/>
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
                                        <option value="disCode">Enfermedades</option>
                                        <option value="medDesc">Medicamentos</option>
                                        <option value="pollCity">Municipios</option>
                                        <?php 
                                        foreach ($datosBD as $key) { 
                                            //if ($key['rules'] != 'vch' && $key['tipo'] != 'tf') {
                                                ?>   
                                                <option value="<?php echo $key['pregunta']; ?>"><?php echo $key['descripcion'];?></option>
                                                <?php 
                                           // }
                                        }
                                        ?>    
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <input type="button" name="BAddFilter" id="BAddFilter" value="Agregar" class="btn btn-lg btn-success btn-block">
                                </div>
                                </div>
                                </div>                            
                                <div class="col-lg-6" id = '1'>
                                    
                                </div>
                                <div class="col-lg-6" id = '2'>
                                    
                                </div>
                            </div>
                        </div>
                    </form>
                    <div style="width: 100%"  class="panel-title2" align="center">
                        <button style="width:400px;" type="button" class="btn btn-lg btn-success btn-block" onclick="descargar(); document.getElementById('formFilter').submit(); MostrarPreloader();">Descargar Reporte</button>    
                    </div>
                </div>
            </div>
        </div>
        
        <?php include("../../../sitemedia/html/scriptpie.php"); ?>

    </body>
<?php 
    if ($namePoll != '' && $namePro): ?>
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
