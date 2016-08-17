<!DOCTYPE html>
<html>
	<head>
		
		<!---------------|
		 |------CSS------|
		 |-------------!-->
		<link rel="stylesheet" type="text/css" href="src/css/main.css">
		<link rel="stylesheet" type="text/css" href="src/css/sb-admin-2.css">
		<!---------------|
		 |----FABICON----|
		 |-------------!-->
		<link rel="apple-touch-icon" sizes="57x57" href="src/images/ico/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="src/images/ico/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="src/images/ico/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="src/images/ico/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="src/images/ico/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="src/images/ico/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="src/images/ico/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="src/images/ico/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="src/images/ico/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="src/images/ico/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="src/images/ico/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="src/images/ico/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="src/images/ico/favicon-16x16.png">
		<link rel="manifest" href="src/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="src/images/ico/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<!---------------|
		 |----SCRIPTS----|
		 |-------------!-->
		<script src="src/socket.io/socket.io-1.2.0.js"></script>
		<script src="src/jquery/framework/jquery-2.1.4.js"></script>
		<script src="src/jquery/ui/jquery-ui.js"></script>
		<script src="src/javascript/ajax.js"></script>
		<script src="src/javascript/md5.js"></script>
		<script src="src/lang/es-CO/es-CO.js"></script>
		<script src="src/javascript/general.js"></script>
	</head>
<?php
	require_once('lib/db/es-CO.php');
	session_start();
	if (($_POST) && (isset($_SESSION['CREDENTIALS']))) {
		extract($_POST);
	    require_once($hiddenField.'.php');
	} elseif (isset($_SESSION['CREDENTIALS'])) {
		$hiddenField = 'main';
		require_once($hiddenField.'.php');
	} else {
		require_once("login.php"); 
	}
?>
</html>
