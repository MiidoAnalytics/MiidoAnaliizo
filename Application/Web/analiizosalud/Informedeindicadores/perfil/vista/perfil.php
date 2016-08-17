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

            function mostrarDiv () {
                var encuesta = document.getElementById('encuesta').value;
                var proyecto = document.getElementById('proyecto').value;
                if (encuesta && proyecto) {
                    document.getElementById('divTipoPerfil').style.display = "block";
                    document.getElementById('perfil').focus();
                }else{
                    document.getElementById('divTipoPerfil').style.display = "none";
                    document.getElementById('perfil').focus();
                }                
            }

            function activarNumDoc(selValue){
                if (selValue != '') {
                    document.getElementById('documento').disabled = false;
                }else{
                    document.getElementById('documento').disabled = true;
                };
            }

            function mosOptFam(selValFam){
                if (selValFam != "") {
                    document.getElementById('valorBuscar').style.display = "block";
                    document.getElementById('valorBuscar').value = "";
                }else{
                    document.getElementById('valorBuscar').style.display = "none";
                };
            }

            function showPerfil() {
                t1 = document.getElementById('perfil').value;
                if (t1 == 1)
                {   
                    lookup();
                    document.getElementById('persona').style.display = "block";
                    document.getElementById('tipoIdentidad').focus();
                    document.getElementById('familia').style.display = "none";
                }
                else if(t1 == 2)
                {   
                    document.getElementById('project1').value = document.getElementById('encuesta').value;
                    document.getElementById('pollid1').value = document.getElementById('proyecto').value;
                    document.getElementById('familia').style.display = "block";
                    document.getElementById('nombreFamilia').focus();
                    document.getElementById('persona').style.display = "none"; 
                }else{
                    document.getElementById('persona').style.display = "none";
                    document.getElementById('persona').style.display = "none";
                }
            }

            function lookup() {
                $.post("tipoidentificacion.php", {
                    proyecto:document.getElementById('proyecto').value,
                    encuesta:document.getElementById('encuesta').value 
                }, function (data) {
                    $('#tipoIdentidad').html(data);
                });
                document.getElementById('project').value = document.getElementById('encuesta').value;
                document.getElementById('pollid').value = document.getElementById('proyecto').value;
            }

            function MostrarPreloader () {
                document.getElementById('preload').style.display = 'block';
            }

            $(function(){
                $("#perButton").click(function(){
                    var intervalo = setInterval(function(){
                        var cookie = document.cookie;
                        if (cookie.search('desIni') == -1 || cookie.search('desIni') == 0) {
                            document.getElementById('preload').style.display = 'none';    
                            document.cookie = 'desIni=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
                            clearInterval(intervalo);       
                        }else{
                        };    
                    },1000)
                    document.getElementById('familiaRes').style.display = "none";
                    //
                    var url = "listadoperfil.php";
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $("#formPersona").serialize(), 
                        success: function(data)
                            {
                               $("#personasRes").html(data);
                                document.getElementById('personasRes').style.display = "block"; 
                            }
                        });
                    return false;
                });
                $("#perFamilia").click(function(){
                    var nomFam = document.getElementById('nombreFamilia').value;
                    var url = "listadoperfil.php";
                    if(document.getElementById('nombreFamilia').value.length > 0){
                        MostrarPreloader();
                        var intervalo = setInterval(function(){
                            var cookie = document.cookie;
                            if (cookie.search('desIni') == -1 || cookie.search('desIni') == 0) {
                                document.getElementById('preload').style.display = 'none';    
                                document.cookie = 'desIni=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
                                clearInterval(intervalo);       
                            }else{
                            };    
                        },1000);
                    document.getElementById('personasRes').style.display = "none";
                        $.ajax({
                        type: "POST",
                        url: url,
                        data: $("#formFamilia").serialize(), 
                        success: function(data)
                            {
                               $("#familiaRes").html(data); 
                               document.getElementById('familiaRes').style.display = "block";
                            }
                        });
                        return false;
                    }else{
                        alert("El campo no puede estar vacio");
                        document.getElementById('nombreFamilia').focus();
                    };
                });
            });
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
                        <h1 class="page-header">Perfil por persona o por Familia</h1>
                    </div>                    
                </div>
                <!-- seleccionar proyecto encuesta -->
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Seleccione proyecto y Encuesta
                        </div>  
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="encuesta" class="col-lg-2 control-label">Encuesta: </label>
                                        <select name='encuesta' id='encuesta' class='form-control' required required onchange="mostrarDiv();">
                                             <option value="" selected="selected">SELECCIONE</option>
                                        </select> 
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="divTipoPerfil" style="display: none;">
                                <div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            Seleccione un tipo de perfil
                                        </div> 
                                        <div class="panel-body">                             
                                            <div class="col-lg-6">
                                                <select name='perfil' id='perfil' class='form-control' onchange="showPerfil()">                
                                                    <option value="0" selected="selected">Seleccione</option> 
                                                    <option value="1">Persona</option>
                                                    <option value="2">Familia</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12" id="persona" style="display:none">
                    <div class="panel panel-default">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Buscar Persona
                                </div>
                                <div class="panel-body">
                                    <form method="post" action="" role="form" id="formPersona">  
                                        <div class="col-lg-6">                                                                                  
                                            <div class="form-group">                                                
                                                <label>Tipo Documento</label>                                                
                                                <select name='tipoIdentidad' id='tipoIdentidad' class='form-control' required onchange="activarNumDoc(this.value)">
                                                </select>                                       
                                            </div>    
                                            <div class="form-group">                                                
                                                <label>Primer nombre</label>                                                
                                                <input type="text" class="form-control" name="primerNombre" id="primerNombre" placeholder="Primer Nombre" value="">                                            
                                            </div>                                         
                                            <div class="form-group">                                                
                                                <label>Segundo nombre</label>                                                
                                                <input type="text" class="form-control" name="segundoNombre" id="segundoNombre" placeholder="Segundo Nombre" value="">                                            
                                            </div>                                          
                                        </div>
                                        <div class="col-lg-6">                                                                                     
                                            <div class="form-group">                                                
                                                <label>Documento</label>                                                
                                                <input type="number" class="form-control" name="documento" id="documento" placeholder="Número de documento" value="" disabled="true">                                            
                                            </div>                                         
                                            <div class="form-group">                                                
                                                <label>Primer apellido</label>                                                
                                                <input type="text" class="form-control" name="primerApellido" id="primerApellido" placeholder="Primer apellido" value="">                                            
                                            </div>
                                            <div class="form-group">                                                
                                                <label>Segundo apellido</label>                                                
                                                <input type="text" class="form-control" name="segundoApellido" id="segundoApellido" placeholder="Segundo apellido" value="">                                            
                                            </div>                                          
                                        </div>
                                        <input type="hidden" value="" name="project" id="project"> 
                                        <input type="hidden" value="" name="pollid" id="pollid"> 
                                        <input type="hidden" value="1" name="perfil"> 
                                        <button type="button" id="perButton" class="btn btn-success" value="Guardar" onclick="MostrarPreloader();">Buscar</button>
                                    </form>
                                </div>
                            </div>      
                        </div>
                    </div>
                </div>

                <div class="col-lg-12" id="familia" style="display:none">
                    <div class="panel panel-default">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Buscar Perfil Familia
                                </div>
                                <div class="panel-body">
                                    <form method="post" action="" role="form" id="formFamilia">  
                                        <div class="col-lg-6">                                                                                   
                                            <div class="form-group">                                                
                                                <label>Nombre Familia</label>                                                
                                                <SELECT type="text" class="form-control" name="nombreFamilia" id="nombreFamilia" onchange="mosOptFam(this.value);">
                                                    <option value="">Seleccione</option>
                                                    <!-- <option value="familia">Código familia</option> -->
                                                    <option value="nombre_familia">Nombre de Familia</option>
                                                </select>
                                            </div>                                                                                  
                                        </div>
                                        <div class="col-lg-6" id="buscarFam">      
                                            <div class="form-group">                                                
                                                <label>Valor</label>                                                
                                                <input type="text" class="form-control" name="valorBuscar" id="valorBuscar" placeholder="Ingrese el valor a buscar" style="display:none;">
                                            </div>                                                                                                                        
                                        </div>
                                        <input type="hidden" value="2" name="perfil">
                                        <input type="hidden" value="" name="project1" id="project1"> 
                                        <input type="hidden" value="" name="pollid1" id="pollid1"> 
                                        <button type="button" class="btn btn-success" value="Guardar" id="perFamilia">Buscar</button> 
                                    </form>
                                </div> 
                            </div>     
                        </div>
                    </div>
                </div>

                <div class="col-lg-12" id="personasRes" style="display:block">
                </div>

                <div class="col-lg-12" id="familiaRes" style="display:block">  
                </div>
            </div>
        </div>
        <?php include("../../../sitemedia/html/scriptpie.php"); ?>
        <div id="preload" class = "d_preloader">
            <div class ="d_background">
            </div>
            <div class ="d_container">
                <p>
                    Buscando...
                </p>
            </div>
        </div>
    </body>
</html>