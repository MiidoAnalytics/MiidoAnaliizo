<?php

/**
 * Ajax invocation service for Analiizo Manager By Miido S.A.S.
 * Order and execute all request from frontEnd Pages.
 * @author Alvaro Salgado <alvarosalgado@miido.co>
 * @version 1.0.0  01/09/2015
 * @access protected
 */
	
	require_once('sqlController.php');

	class ajax extends sqlController {
		
		var $arguments;

		/**
		* @access public
		*/
		function __construct($arguments) {
			$this->arguments = json_decode($arguments);
			$this->switchAction();
		}

		/**
		* @access private
		*/
		private function switchAction() {
			switch ($this->arguments->action) {
				case 'login':
						$this->login();
					break;
				case 'logout':
					session_start();
					session_destroy();
					return 1;
					break;
				case 'pollSaver':
					$this->pollSaver();
					break;
				case 'pollFinder':
					$this->pollFinder();
					break;
				case 'projectFinder':
					$this->projectFinder();
					break;
				default:
					break;
			}
		}


		/**
		* @access protected
		*/
		protected function projectFinder() {

			$this->prepare(array(
				"operation"  => _ACTIONPOLLG,
				"fields" 	 => _PROINDX.", "._PRONAME,
				"table" 	 => _TABLEPROJECT.", "._TABLEUSER, 
				"conditions" => 'intidregistereduser = idusuarios and analiizo_salud.proyecto.intistatus not in (3)'
			));
			$result = $this->exceute();
			$c = 0;
			echo "[";
			while ($tmpRs = $result[$c]) {
				if($c > 0)
					echo ",";
				echo "{";
				echo ('"Id":"'.$tmpRs[_PROINDX].'",' );
				echo ('"Project":"'.$tmpRs[_PRONAME].'"' );
				echo "}";
				$c++;
			}
			echo "]";
		}

		/**
		* @access protected
		*/
		protected function login() {
			
			session_start();
			$this->prepare(array(
				"operation"  => _ACTIONLOGIN,
				"fields" 	 => "count(*) as login, idusuarios",
				"table" 	 => _TABLEUSER,
				"conditions" => _AUTHUSER." = '".$this->arguments->uname."' AND "._AUTHPASS." = '".$this->arguments->hashpass."' group by idusuarios"
			));
			$result = $this->exceute();//->fetch_assoc();
			if(count($result) == 0) {
				$_SESSION['SID'] = 'undefined';
				session_abort();
				die(0);
			}
			try {
				$result = $result[0];
			} catch (Exception $e) {
			}
			if ($result['login'] > 0) {
				echo ($result['idusuarios']);
				$_SESSION['SID'] = $result['idusuarios'];
				$_SESSION['CREDENTIALS'] = json_encode($this->arguments);
			} else {
				echo ($result['login']);
				$_SESSION['SID'] = 'undefined';
				session_abort();
			}
		}

		/**
		* @access protected
		*/
		protected function pollSaver() {
			session_start();
			if($this->arguments->status == 1) {
				$this->prepare(array(
					'operation' => _ACTIONPOLLU,
					'table' => _TABLEPOLL,
					'values' => _POLLSTAT." = 0",
					'conditions' => 'intidestructura < -1'
				));
				//$this->exceute();
			}

			if(isset($this->arguments->index)){
				$poll = $this->arguments->poll;
				$index = $this->arguments->index;
				$uid  = $_SESSION['userid'];
				$this->prepare(array(
					'operation' => _ACTIONPOLLU,
					'table' => _TABLEPOLL, 
					'values' => _POLLCONT." = '$poll', "._POLLSTAT." = ".$this->arguments->status.",intregistereduser = $uid, dtmodifieddt = NOW(), nombre= '".$this->arguments->name."'",
					'conditions' => _POLLINDX." = $index"
				));
				$this->exceute();
				echo "1";
			} else {
				$uid  = $_SESSION['userid'];
				$poll = $this->arguments->poll;
				$this->prepare(array(
					'operation' => _ACTIONPOLLS,
					'table' => _TABLEPOLL." (estructura,intstatuspublic,intregistereduser,intstatus,dtcreatedate,intidproyecto, nombre)",
					'values' => "'$poll', ".$this->arguments->status.",  $uid, 1, NOW(), 1, '".$this->arguments->name."'"
				));
				$this->exceute();
				//print_r($algo);
				echo 1;
			}
		}

		/**
		* @access protected
		*/
		protected function pollFinder() {
			$this->prepare(array(
				"operation"  => _ACTIONPOLLG,
				"fields" 	 => _POLLINDX.", "._POLLCONT.", analiizo_salud.estructuraencuestas.dtcreatedate, "._POLLSTAT.", intregistereduser, strlogin",
				"table" 	 => _TABLEPOLL.", "._TABLEUSER,
				"conditions" => 'intregistereduser = idusuarios'
			));
			$result = $this->exceute();
			$c = 0;
			echo "[";
			while ($tmpRs = $result[$c]) {
				if($c > 0)
					echo ",";
				echo "{";
				echo ('"Id"		:'.$tmpRs[_POLLINDX].			',' );
				echo ('"Poll"	:'.$tmpRs[_POLLCONT].			',' );
				echo ('"Date"	:"'.$tmpRs['dtcreatedate'].			'",');
				echo ('"Status"	:'.$tmpRs[_POLLSTAT].			',' );
				echo ('"IdUser"	:'.$tmpRs['intregistereduser'].	',' );
				echo ('"User"	:"'.$tmpRs['strlogin'].			'"' );
				echo "}";
				$c++;
			}
			echo "]";
		}
	}

	//ConexionAnaliizoPostgres::close_con();

	extract($_POST);
	extract($_GET); //Developer only
	
	function is_ajax() {
		return true; //Developer only
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}

	if((is_ajax()) &&
		isset($arguments)){
			//echo $arguments;
			$tmp = new ajax($arguments);
	}
	else{
		echo err_aut_serv;
	}
?>
