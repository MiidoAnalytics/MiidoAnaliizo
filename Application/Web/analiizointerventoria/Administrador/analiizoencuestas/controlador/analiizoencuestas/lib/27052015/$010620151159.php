<?php
	
	require_once('$270520151108.php');
	extract($_POST);
	//extract($_GET);
	//http://localhost/lib/27052015/$010620151159.php?call=SENDPOLL&arguments=asd&extras=1,2||-1||5

	class sender extends analiizoByMiido
	{
		var $user = "ROOT";
		var $ip4;
		var $arg;
		
		function __construct($arguments){
			$this->arg = $arguments;
			$this->ip4 = (
				(!empty($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] :
					(!empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] :
						$_SERVER['REMOTE_ADDR'])
					)
				);
		}
		//public function sendGroup(){//echo ($this->arg); /*$arguments = "NULL, '$this->arg', NULL, '$this->user', '$this->ip4', 1"; echo $this->_INSERT('temporarybuilder', $arguments, '1');*/ }
		//public function sendPerson($parent){/*$arguments = "NULL, '$this->arg', $parent, '$this->user', '$this->ip4', 1"; echo $this->_INSERT('temporarybuilder', $arguments, '1');*/ }
		//public function sendHouse($parent){/*$arguments = "NULL, '$this->arg', $parent, '$this->user', '$this->ip4', 1"; echo $this->_INSERT('temporarybuilder', $arguments, '1');*/ }
		
		/*
		Modificación Miguel Urango
		*/
		public function sendPoll(){
			//$arguments = "NULL, '$this->arg', NULL";
			$arguments = "'$this->arg'";
			//die("hola");
			//echo $this->_INSERT('poll',
			//echo "pasé";
			echo $this->_INSERT('administracion.poll', $arguments, '3');
		}

		public function closePollData($parameters){/*$temporary = explode("||", $parameters); echo $this->_UPDATE("temporaryBuilder", "iTBId in ($temporary[0])", "iTBStatus = $temporary[1]", "3"); $this->movements($temporary[2]);*/ }
		//private function movements($data){/*$arguments = "NULL, '$data', 'WEBTEMP', '$this->ip4', NOW()"; $this->_INSERT(constant('_MOVE_T'), $arguments, '1');*/ } 
		//public function getPeople(){/*$tmp = $this->_SELECT(_INTERV_T, ((defined('_INTERV_C')) ? constant('_INTERV_C') : null), _INTERV_F, _INTERV_D ); $this->i   = $this->_PARSE($tmp); var people = JSON.parse('".(json_encode(($analiizoByMiido->p)))."');*/ }
		//public function sendResponse(){}
	}
	
	class finder extends analiizoByMiido {
		var $arg;
		function __construct($arguments) {
			$this->arg = $arguments;
		}

		public function getPersons($extra){
			$arguments = "";
			$munic = intval(substr($extra, 2));
			$depto = intval(substr($extra, 0, 2));
			$tmp = $this->_SELECT(
				'comfasucre',
				"codigoDepartamento = $depto AND codigoMunicipio = $munic and identificacion in (select identificacion from comfasucre where identificacion like '%".$this->arg."%' or primerNombre like '%".$this->arg."%' or segundoNombre like '%".$this->arg."%' or primerApellido like '%".$this->arg."%' or segundoApellido like '%".$this->arg."%') limit 100",
				'tipoIdentificacion,identificacion,primerNombre,segundoNombre,primerApellido,segundoApellido,numeroCarnet,fechaNacimiento,genero,telefono',
				1 );
			echo json_encode($this->_PARSE($tmp));
		}
	}

	function is_ajax() {
		//return true;
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}

	###########################################
	
	if((is_ajax()) && (isset($call)) && (isset($arguments))){
		switch ($call) {
			case 'SENDGROUP':
				$sender = new sender($arguments);
				$sender->sendGroup();
				break;

			case 'SENDPERSON':
				$sender = new sender($arguments);
				$sender->sendPerson($home);
				break;

			case 'SENDHOUSE':
				$sender = new sender($arguments);
				$sender->sendHouse($home);
				break;

			case 'SENDPOLL':
				$sender = new sender($arguments);
				$sender->sendPoll();
				//echo "string";
				$sender->closePollData($extras);
				break;
			case 'FINDPERSON':
				$finder = new finder($arguments);
				$finder->getPersons($extras);
				break;
			default:
				exit();
				break;
		}
	}

?>