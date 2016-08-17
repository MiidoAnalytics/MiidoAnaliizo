<?php
	$address = ($_SERVER['PHP_SELF']);
	$path = explode('/', $address);
	//die(count($path)."");
	//if($count)
	switch (count($path)) {
		case 2:
			include_once('lib/27052015/$_constants.php');
			break;
		
		default:
			include_once('$_constants.php');
			break;
	}

	//require_once '../../../../../../../conexiones/classconexion.php';
	/**
	* 
	*/

    /*
    Modificación Miguel Urango.
    */
	class ConexionAnaliizoPostgres extends PDO{
 
	//nombre base de datos
	private $dbname = "postgres";
	//nombre servidor
	private $host = "localhost";
	//nombre usuarios base de datos
	private $user = "postgres";
	//password usuario
	private $pass = 'Miido2015';
	//puerto postgreSql
	private $port = 5432;
	private $dbh;
 
	//creamos la conexión a la base de datos prueba
	public function __construct() 
	{
	    try {
 
	        $this->dbh = parent::__construct("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->user;password=$this->pass");
 
	    } catch(PDOException $e) {
 
	        echo  $e->getMessage(); 
 
	    }
 
	}
 
	//función para cerrar una conexión pdo
	public function close_con() 
	{
 
    	$this->dbh = null; 
 
	}
 
}

	class analiizoByMiido
	{
		var $d;
		var $v;
		var $m;
		var $o;
		var $i;
		var $c;
		var $l;
		var $r;
		var $s;

		function __construct() {
			extract($_GET);
			$this->r = 'false';
			/*if(isset($dep)){
				define('_DEPARTAMENT', $dep);
				//define('_PEOPLE_C'	 , 'codigoDepartamento = '._DEPARTAMENT );//._SP.'codigoMunicipio='._CITY
				define('_CITY_C'	 , 'idDepartamento     = '._DEPARTAMENT );
				$this->getCoreData();
			}*/
			/*$tmp = $this->_SELECT(
			_DEPAR_T,
			((defined('_DEPAR_C')) ? constant('_DEPAR_C') : null),
			_DEPAR_F,
			_DEPAR_D );
			$this->s   = $this->_PARSE($tmp);*/
		}

		//private function connect($db) {
			//$conn = new mysqli(_HOST, _USER, _PASSWORD, $db);
		//	$conn = new ConexionAnaliizoPostgres();

		//	if ($conn) {
		//		return $conn;
		//	} else {
		//		die("Algo no ha ido como debería.".$conn->connect_error);
		//	}
		//}

        /*
        Modificación Miguel Urango
        */
		private function connect() {
			//$conn = new mysqli(_HOST, _USER, _PASSWORD, $db);
			$conn = new ConexionAnaliizoPostgres();

			if ($conn) {
				return $conn;
			} else {
				die("Algo no ha ido como debería."/*.$conn->connect_error*/);
			}
		}

		public function getCoreData(){
			$this->r = 'true';

			/*$tmp = $this->_SELECT(
				_MOVE_T,
				((defined('_MOVE_C')) ? constant('_MOVE_C') : null),
				_MOVE_F,
				_MOVE_D );
			$this->l = $this->_PARSE($tmp);
			
			$tmp = $this->_SELECT(
				_DISEASES_T,
				((defined('_DISEASES_C')) ? constant('_DISEASES_C') : null),
				_DISEASES_F,
				_DISEASES_D );
			$this->d   = $this->_PARSE($tmp);

			$tmp = $this->_SELECT(
				_INTERV_T,
				((defined('_INTERV_C')) ? constant('_INTERV_C') : null),
				_INTERV_F,
				_INTERV_D );
			$this->i   = $this->_PARSE($tmp);

			$tmp = $this->_SELECT(
				_CITY_T,
				((defined('_CITY_C')) ? constant('_CITY_C') : null),
				_CITY_F,
				_CITY_D );
			$this->c   = $this->_PARSE($tmp);

			$tmp = $this->_SELECT(
				_CUMS_T,
				((defined('_CUMS_C')) ? constant('_CUMS_C') : null),
				_CUMS_F,
				_CUMS_D );
			$this->v   = $this->_PARSE($tmp);

			$tmp = $this->_SELECT(
				_CUPS_T,
				((defined('_CUPS_C')) ? constant('_CUPS_C') : null),
				_CUPS_F,
				_CUPS_D );
			$this->m = $this->_PARSE($tmp);

			$tmp = $this->_SELECT(
				_CIUO_T,
				((defined('_CIUO_C')) ? constant('_CIUO_C') : null),
				_CIUO_F,
				_CIUO_D );
			$this->o   = $this->_PARSE($tmp);*/
		}

		public function _SELECT($target, $data, $response, $db){
			
			$data = ( ( isset( $data ) ) ? $data : '1' );
			$data = str_replace(_SP, ' AND ', $data);
			$tmp2 = explode(",", $response);
			if (count($tmp2) > 1) {
				$response = "";
				foreach ($tmp2 as $tmp3) {
					$response .= 'replace('.$tmp3.', "\"", "") as '.$tmp3.',';
				}
				$response = substr($response, 0, strlen($response)-1);
			}
			$query = "SELECT $response FROM $target WHERE $data;";
			//echo $query;
			$conn = $this->connect(constant('_DB'.$db));

			if ($tmp = $conn->query($query)){
				return $tmp;
			}
			return null;
		}

		//public function _INSERT($target, $data, $db){
		//	$q = "INSERT INTO $target VALUES ($data)";
			//return $q;
		//	$conn = $this->connect(constant('_DB'.$db));
		//	$conn->query($q);
		//	return $conn->insert_id;
		//}

        /*
        Modificación Miguel Urango
        */
		public function _INSERT($target, $data, $db){
			$q = "INSERT INTO $target (pollcontent) VALUES ($data)";
			$conn = $this->connect();
			$query = $conn->prepare($q);
			//echo $q;
			//$query.bindValue(1,$data,PDO::PARAM_STR);
			$result = $query->execute();
			if($result){
				$query = $conn->prepare("SELECT currval('administracion.poll_secuence_id') AS last_value");
				$query->execute();
				return $query->fetch(PDO::FETCH_ASSOC)['last_value'];
			}
			return 0;
		}

		public function _UPDATE($target, $data, $values, $db){
			$data = ( ( isset( $data ) ) ? $data : '1' );
			$query = "update $target set $values where $data;";
			$conn = $this->connect(constant('_DB'.$db));
			$conn->query($query);
			return $conn->insert_id;
		}

		public function _PARSE($data){
			if (null != $data) {
				$response = array();
				while ($row = $data->fetch_assoc()) {
					array_walk_recursive($row, function(&$item, $key){
			        	if(!mb_detect_encoding($item, 'utf-8', true)){
			                $item = utf8_encode($item);
			        	}
			    	});
					$response[] = $row;
				}
				try {
					$data->free();
				} catch (Exception $e){}
				return $response;
			}
			//try {
				//$data->free();
			//} catch (Exception $e){}
			return null;
		}
	}
?>