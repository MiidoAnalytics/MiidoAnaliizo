<!DOCTYPE=HTML>
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
		
		<script type="text/javascript" src="lang/loader.js"></script>
		<script type="text/javascript" src="lang/es/es-CO.js"></script>
		<script type="text/javascript" src="https://sdk.amazonaws.com/js/aws-sdk-2.1.30.min.js"></script>
		<script type="text/javascript" src="lib/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="lib/jquery-ui.min.js"></script>
		<?php
			extract($_GET);
			require_once('lib/27052015/$270520151108.php');
                        //die("pasé por aquí");
			$analiizoByMiido = new analiizoByMiido();
			$analiizoByMiido->getCoreData();
			echo 
			"<script type=\"text/javascript\">
				var people = JSON.parse('".(json_encode($analiizoByMiido->p))."');
				var diseases = JSON.parse('".(json_encode($analiizoByMiido->d))."');
				var cums = JSON.parse('".(json_encode($analiizoByMiido->v))."');
				var ciuo = JSON.parse('".(json_encode($analiizoByMiido->o))."');
				var cups = JSON.parse('".(json_encode($analiizoByMiido->m))."');
			</script>
";
		?>
		<script type="text/javascript" src="system/constants.js"></script>
		<script type="text/javascript" src="aws/sm-0.1.min.js"></script>
        <script type="text/javascript" src="formatter.js"></script>
        <script type="text/javascript" src="listener.js"></script>
        <script type="text/javascript" src="compose.js"></script>
        <script type="text/javascript" src="interface_builder.js"></script>
        <script type="text/javascript" src="interviewer.js"></script>
	</head>
	<body align="center" >
		<div id="masterDiv" class="col-md-4 col-md-offset-4">
		</div>
	</body>
	<script type="text/javascript" src="core.js"></script>
	
</html>
