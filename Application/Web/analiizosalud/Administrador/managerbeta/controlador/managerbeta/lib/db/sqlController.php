<?php

	require_once('constants.php');
	require_once('es-CO.php');
	
	class ConexionAnaliizoPostgres extends PDO
	{
	 
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

	class sqlController {

		var $query;
		var $conn;

		function __construct() {
			$this->query = "";
		}

		/*public function exceute() {
	        $conexion = new ConexionAnaliizoPostgres();
	        $conexion->exec("set names utf8");
	        //echo $this->query;
	        $resultado = $conexion->query($this->query);       
	        $resultado->execute();
	        
	        if (!$resultado) {
                    echo "\nPDO::errorInfo():\n";
                    print_r($consulta->errorInfo());
                }
	        $registros = $resultado->fetchAll(PDO::FETCH_ASSOC);
	        $conexion = null;
	        
	        if(count($registros) > 0){
	        	return $registros;
	        }
	        return null;
	    } 

		public function prepare($arguments) {
			try {
				if (isset($arguments['operation']) || isset($arguments['OPERATION'])) {
					switch ($arguments['operation']) {
						case 'SELECT':
						case 'select':
							$this->query = 'SELECT ';
							$this->query .= ((isset($arguments['fields'])) ? 
										$arguments['fields'] :
										(isset($arguments['FIELDS']) ?
											$arguments['FIELDS'] :
											'*'));
							$this->query .= ' FROM ';
							$this->query .= ((isset($arguments['table'])) ?
										$arguments['table'] :
										(isset($arguments['TABLE']) ?
											$arguments['TABLE'] :
											die(err_prep_sel) ));
							$this->query .= ' WHERE ';
							$this->query .= ((isset($arguments['conditions'])) ?
										$arguments['conditions'] :
										(isset($arguments['CONDITIONS']) ?
											$arguments['CONDITIONS'] :
											"1"));
						break;
						case 'INSERT':
						case 'insert':
							$this->query = 'INSERT INTO ';
							$this->query .= ((isset($arguments['table'])) ? 
										$arguments['table'] :
										(isset($arguments['TABLE']) ?
											$arguments['TABLE'] :
											die(err_prep_ins)));
							$this->query .= ((isset($arguments['fields'])) ?
										"(".$arguments['fields'].")" :
										(isset($arguments['FIELDS']) ?
											"(".$arguments['FIELDS'].")" :
											'' ));
							$this->query .= ' VALUES (';
							$this->query .= ((isset($arguments['values'])) ?
										$arguments['values'] :
										(isset($arguments['VALUES']) ?
											$arguments['VALUES'] :
											die(err_prep_ins)));
							$this->query .= ");";
						break;
						case 'UPDATE':
						case 'update':
							$this->query = 'UPDATE ';
							$this->query .= ((isset($arguments['table'])) ? 
										$arguments['table'] :
										(isset($arguments['TABLE']) ?
											$arguments['TABLE'] :
											die(err_prep_ins)));
							$this->query .= ' SET ';
							$this->query .= ((isset($arguments['values'])) ?
										$arguments['values'] :
										(isset($arguments['VALUES']) ?
											$arguments['VALUES'] :
											die(err_prep_ins)));
							$this->query .= ' WHERE ';
							$this->query .= ((isset($arguments['conditions'])) ?
										$arguments['conditions'] :
										(isset($arguments['CONDITIONS']) ?
											$arguments['CONDITIONS'] :
											"1"));
						break;

						default:
						break;
					}
				}
			} catch(Error $e) {
			}
		}

	}*/

	public function exceute() {
	        $conexion = new ConexionAnaliizoPostgres();
	        $conexion->exec("set names utf8");
	        $resultado = $conexion->query($this->query);       
	        //echo $this->query;
	        //$resultado->execute();
	        
	        if (!$resultado) {
                    echo "\nPDO::errorInfo():\n";
                    print_r($conexion->errorInfo());
                }
	        $registros = $resultado->fetchAll(PDO::FETCH_ASSOC);
	        $conexion->close_con();
	        $conexion = null;

	        if(count($registros) > 0){
	        	return $registros;
	        }
	        return null;
	    } 

		public function prepare($arguments) {
			try {
				if (isset($arguments['operation']) || isset($arguments['OPERATION'])) {
					switch ($arguments['operation']) {
						case 'SELECT':
						case 'select':
							$this->query = 'SELECT ';
							$this->query .= ((isset($arguments['fields'])) ? 
										$arguments['fields'] :
										(isset($arguments['FIELDS']) ?
											$arguments['FIELDS'] :
											'*'));
							$this->query .= ' FROM ';
							$this->query .= ((isset($arguments['table'])) ?
										$arguments['table'] :
										(isset($arguments['TABLE']) ?
											$arguments['TABLE'] :
											die(err_prep_sel) ));
							$this->query .= ' WHERE ';
							$this->query .= ((isset($arguments['conditions'])) ?
										$arguments['conditions'] :
										(isset($arguments['CONDITIONS']) ?
											$arguments['CONDITIONS'] :
											"1"));
						break;
						case 'INSERT':
						case 'insert':
							$this->query = 'INSERT INTO ';
							$this->query .= ((isset($arguments['table'])) ? 
										$arguments['table'] :
										(isset($arguments['TABLE']) ?
											$arguments['TABLE'] :
											die(err_prep_ins)));
							$this->query .= ((isset($arguments['fields'])) ?
										"(".$arguments['fields'].")" :
										(isset($arguments['FIELDS']) ?
											"(".$arguments['FIELDS'].")" :
											'' ));
							$this->query .= ' VALUES (';
							$this->query .= ((isset($arguments['values'])) ?
										$arguments['values'] :
										(isset($arguments['VALUES']) ?
											$arguments['VALUES'] :
											die(err_prep_ins)));
							$this->query .= ");";
						break;
						case 'UPDATE':
						case 'update':
							$this->query = 'UPDATE ';
							$this->query .= ((isset($arguments['table'])) ? 
										$arguments['table'] :
										(isset($arguments['TABLE']) ?
											$arguments['TABLE'] :
											die(err_prep_ins)));
							$this->query .= ' SET ';
							$this->query .= ((isset($arguments['values'])) ?
										$arguments['values'] :
										(isset($arguments['VALUES']) ?
											$arguments['VALUES'] :
											die(err_prep_ins)));
							$this->query .= ' WHERE ';
							$this->query .= ((isset($arguments['conditions'])) ?
										$arguments['conditions'] :
										(isset($arguments['CONDITIONS']) ?
											$arguments['CONDITIONS'] :
											"null"));
						break;

						default:
						break;
					}
				}
			} catch(Error $e) {
			}
		}

	}

?>