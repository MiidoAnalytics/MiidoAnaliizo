<!DOCTYPE html>
<html>
	<head>
		<title>AnaliizoByMiido</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
		<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" />
		<link rel="stylesheet" href="css/jquery-ui.min.css" type="text/css" />
		<link rel="stylesheet" href="css/jquery-ui.structure.css" type="text/css" />
		<link rel="stylesheet" href="css/jquery-ui.structure.min.css" type="text/css" />
		<link rel="stylesheet" href="css/jquery-ui.theme.css" type="text/css" />
		<link rel="stylesheet" href="css/jquery-ui.theme.min.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="css/sb-admin-2.css">
		<style type="text/css">
			.ui-autocomplete {
				height: 200px;
				width: 400px;
				overflow-y: scroll;
				overflow-x: hidden;
				position: absolute;
			}
			.ui-helper-hidden-accessible { display:none; }
		</style>
		<script src="socket.io/socket.io-1.2.0.js"></script>
		<script type="text/javascript" src="lang/loader.js"></script>
		<script type="text/javascript" src="lang/es/es-CO.js"></script>
		<script type="text/javascript" src="lib/aws-sdk-2.1.30.min.js"></script>
		<script type="text/javascript" src="lib/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="lib/jquery-ui.min.js"></script>
		<!--
		<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
		<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
		!-->
		<?php
			require_once('lib/27052015/$270520151108.php');
			$analiizoByMiido = new analiizoByMiido();
			//$analiizoByMiido->getCoreData();
			//print_r(($analiizoByMiido->r));
			//die();
			/*json_encode(utf8_encode($analiizoByMiido->p));
			*/
			echo 
			"<script type=\"text/javascript\">
				var r = true;
				var deptos = JSON.parse('{}');
				var diseases = JSON.parse('{}');
				var interviewer = JSON.parse('{}');
				var lister = JSON.parse('{}');
				var city = JSON.parse('{}');
				var cums = JSON.parse('{}');
				var ciuo = JSON.parse('{}');
				var cups = JSON.parse('{}');
			</script>";
			//print_r(($analiizoByMiido->p)."---");
			switch (json_last_error()) {
			    case JSON_ERROR_NONE:
			        //echo ' - Sin errores';
			        break;
			    case JSON_ERROR_DEPTH:
			        echo ' - Excedido tamaño máximo de la pila';
			        break;
			    case JSON_ERROR_STATE_MISMATCH:
			        echo ' - Desbordamiento de buffer o los modos no coinciden';
			        break;
			    case JSON_ERROR_CTRL_CHAR:
			        echo ' - Encontrado carácter de control no esperado';
			        break;
			    case JSON_ERROR_SYNTAX:
			        echo ' - Error de sintaxis, JSON mal formado';
			        break;
			    case JSON_ERROR_UTF8:
			        echo ' - Caracteres UTF-8 malformados, posiblemente están mal codificados';
			        break;
			    default:
			        echo ' - Error desconocido';
			        break;
			}
		?>
		<script type="text/javascript" src="system/constants.js"></script>
		<script type="text/javascript" src="aws/sm-0.1.min.js"></script>
        <script type="text/javascript" src="formatter.js"></script>
        <script type="text/javascript" src="listener.js"></script>
        <script type="text/javascript" src="compose.js"></script>
        <script type="text/javascript" src="interface_builder.js"></script>
        <script type="text/javascript" src="interviewer.js"></script>
        <script type="text/javascript" src="pollView.js"></script>
        <script type="text/javascript" src="gps.js"></script>
        <script type="text/javascript" src="filter.js"></script>
	</head>
	<body align="center" style= " background-color:#f8f8f8; border-top: 10px solid #0aae13; background-repeat: no-repeat; background-position: right top; background-size: 100% auto; " >
		<img src="images/background_02.jpg" class="bk_image" />
		<div class="bk_gradient"></div>
            <div id="formulario">
		<div id="masterDiv" align="center">
                    <img src="images/analiizo_logo.png">
		</div>
            </div>
	</body>
	<script type="text/javascript" src="core.js"></script>
	
</html>