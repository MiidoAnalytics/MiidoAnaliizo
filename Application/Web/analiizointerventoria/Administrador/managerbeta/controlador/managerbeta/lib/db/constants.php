<?php

/**
 * Configuration file for Analiizo Manager By Miido S.A.S.
 * Contains php aplication constants for uses when is callign from ajax service
 * @author Alvaro Salgado <alvarosalgado@miido.co>
 * @version 1.0.0 01/09/2015
 * @access public
 */

	########################
	##     CONNECTION     ##
	########################
	define('_HOST',		'localhost');
	define('_USER',		'postgres');
	define('_PASSWORD',	'Miido2015');
	define('_PORT',		'5432');
	define('_DATABASE',	'postgres');
	
	########################
	##  TABLES RELATIONS  ##
	########################
	define('_TABLEUSER', 'analiizo_interventoria.usuarios');
	define('_TABLEPOLL', 'analiizo_interventoria.estructuraencuestas');
	define('_TABLEPROJECT', 'analiizo_interventoria.proyecto');

	########################
	##  ACTIONS OPERATION ##
	########################
	define('_ACTIONLOGIN', 'SELECT');
	define('_ACTIONPOLLG', 'SELECT');
	define('_ACTIONPOLLS', 'INSERT');
	define('_ACTIONPOLLU', 'UPDATE');

	########################
	##  FIELDS RELATIONS  ##
	########################
	define('_AUTHUSER', 'strlogin');
	define('_AUTHPASS', 'strpassword');
	define('_POLLCONT', 'estructura');
	define('_POLLINDX', 'intidestructura');
	define('_POLLSTAT', 'intstatuspublic');


	define('_PROINDX', 'intidproyecto');
	define('_PRONAME', 'strnombre');



	

?>