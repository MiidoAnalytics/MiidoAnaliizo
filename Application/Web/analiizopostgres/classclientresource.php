<?php

/* 
 * Clase que contiene la estructura y consultas basicas de los recursos de un cliente.
 * 
 * @author Ing. Miguel Angel Urango Blanco.
 * @date 25/11/2015 d/m/Y
 */

define('CONTROLADOR',true);

require_once '../conexiones/classconexion.php';


$poll = new ClientResource();
if(isset($_POST['poll'])){
  $pollcontent = $_POST['poll'];
  $pollcontent =str_replace(array('(a)','(e)','(i)','(o)','(u)','(n)'),array('á','é','í','ó','ú','ñ'), $pollcontent);
    $result = $poll->insertPollData($pollcontent);
    if($result){
        echo "OK";
    }
}

class ClientResource{
    
    /*
     * Atributos
     * @attribute int $intidrecurso id del recurso
     * @attribute string $strnombrerecurso nombre del recurso
     * @attribute oid $oidrecurso contenido binario del recurso
     * @attribute int $intsizeresource tamaño en bytes del recurso
     * @attribute string $strtiporecurso tipo de mime
     * @attribute int $intidregistereduser id del usuario que lo registró
     * @attribute int $intstatus estado en la base de datos
     * @attribute string $dtcreatedate fecha de creación
     * @attribute string $dtmodifieddate fecha de modificación
     * @attribute int $intidcliente id del cliente al que pertenece el recurso
     */
    private $intidrecurso;
    private $strnombrerecurso;
    private $oidrecurso;
    private $intsizeresource;
    private $strtiporecurso;
    private $intidregistereduser;
    private $intstatus;
    private $dtcreatedate;
    private $dtmodifieddate;
    private $intidcliente;
    
    /*
     * Atributos estaticos
     * @attribute string $TIME_ZONE Zona horaria
     * @attribute string $DATE_FORMAT formato de fecha.
     * @attribute string $CHARSET_QUERY consulta para codificación en UTF-8
     * @attribute string $SELECT_QUERY prefijo para la consulta de procedimientos
     */
    private static $TIME_ZONE = "America/Lima";
    private static $DATE_FORMAT = "Y-m-d H:i:s";
    private static $CHARSET_QUERY = "set names utf8";
    private static $SELECT_QUERY = "SELECT * FROM administracion.";
    
    /*
     * Atributos estaticos
     * @attribute string $INSERT_PROCEDURE procedimiento de inserción
     * @attribute string $UPDATE_PROCEDURE procedimiento de actualización
     * @attribute string $DELETE_PROCEDURE procedimiento de borrado
     * @attribute array() $SELECT_PROCEDURES arreglo asociativo con los procedimientos de selección.
     */
    private static $INSERT_PROCEDURE = "spinsertresource(?,?,?,?,?,?,?,?,?,?);";
    private static $UPDATE_PROCEDURE = "spupdateresource(?,?,?,?,?,?,?,?,?,?);";
    private static $DELETE_PROCEDURE = "spdeleteresource(?,?);";
    private static $SELECT_PROCEDURES = array(
        'GET_RESOURCE_BY_ID' => 'spselectedresourcexid(?);',
        'GET_RESOURCES_BY_CLIENT_ID' => 'spselectedresourcexclientid(?);'
    );
    
    function __construct() {
        $this->intidrecurso = $this->intidregistereduser = $this->intstatus = $this->intidcliente = $this->intsizeresource = 0;
        $this->strnombrerecurso = $this->strtiporecurso = $this->dtcreatedate = $this->dtmodifieddate = "";
        date_default_timezone_set(self::$TIME_ZONE);
    }
    
    /*
     * Establece las propiedades de la clase a partr de un arreglo asociativo
     * con los atributos de la clase como llave y sus respectivos valores.
     * @param array() $client_resource_array_data arreglo asociativo
     */
    function setClientResourceData($client_resource_array_data = array()){
        foreach($client_resource_array_data as $propertie => $value){
            $this->$propertie = $value;
        }
    }
    
    /*
     * prepara los parametros de la consulta antes de ser ejecutada
     * @param PDOstatement $query declaración de la consulta como referencia
     */
    private function bindQuery(&$query){
        $query->bindParam(1, $this->getId(), PDO::PARAM_INT);
        $query->bindParam(2, $this->getName(), PDO::PARAM_STR);
        $query->bindParam(3, pg_escape_bytea($this->getContent()), PDO::PARAM_LOB);
        $query->bindParam(4, $this->getType(), PDO::PARAM_STR);
        $query->bindParam(5, $this->getContentLength(), PDO::PARAM_INT);
        $query->bindParam(6, $this->getIdRegisteredUser(), PDO::PARAM_INT);
        $query->bindParam(7, $this->getStatus(), PDO::PARAM_INT);
        $query->bindParam(8, $this->getCreateDate(), PDO::PARAM_STR);
        $query->bindParam(9, $this->getModifiedDate(), PDO::PARAM_STR);
        $query->bindParam(10, $this->getIdClient(), PDO::PARAM_INT);
    }
    
    /*
     * Obtiene un recurso establece la informacion obtenida en los atributos de la clase
     * @param int $intidresource contiene el id del recurso a consultar
     */
    function get($intidresource = 0){
        if($intidresource){
            try{
                $connection = new ConexionAnaliizoPostgres();
                $connection->exec(self::$CHARSET_QUERY);
                $query = $connection->prepare(self::$SELECT_QUERY . self::$SELECT_PROCEDURES['GET_RESOURCE_BY_ID']);
                $query->bindParam(1, $intidresource, PDO::PARAM_INT);
                $query->execute();
                $this->setClientResourceData($query->fetch(PDO::FETCH_ASSOC));
            } catch (Exception $ex) {
                // error log
                $this->setId(0);
            }  finally {
                $connection->close_con();
            }
        }
    }
     /*
      * Obtiene los recursos de la base de datos perteciente a un cliente en especifico
      * @param int $intidcliente id del cliente
      * @return array() arreglo con todos los recursos asignados a un cliente
      */
    function getResourcesByClientId($intidclient){
        if($intidclient){
            try{
                $connection = new ConexionAnaliizoPostgres();
                $connection->exec(self::$CHARSET_QUERY);
                $query = $connection->prepare(self::$SELECT_QUERY . self::$SELECT_PROCEDURES['GET_RESOURCES_BY_CLIENT_ID']);
                $query->bindParam(1, $intidclient, PDO::PARAM_INT);
                $query->execute();
                return $query->fetchAll();
            } catch (Exception $ex) {
                // error log
            }  finally {
                $connection->close_con();
            }
        }
        return array();
    }
    
    /*
     * inserta un nuevo recurso a la base de datos
     * @param array() $client_resource_array_data arreglo asociativo con los atributos de la clase
     * @return bool true si se insertó la información con exito en la base de datos, de lo contrario false.
     */
    function insert($client_resource_array_data = array()){
        try{
            $connection = new ConexionAnaliizoPostgres();
            $connection->exec(self::$CHARSET_QUERY);
            $query = $connection->prepare(self::$SELECT_QUERY . self::$INSERT_PROCEDURE);
            $this->setClientResourceData($client_resource_array_data);
            
            // se obtiene la fecha actual y se establecen los atributos $dtcreatedate y dtmodifieddate
            $date = date(self::$DATE_FORMAT);
            $this->setCreateDate($date);
            $this->setModifiedDate($date);
            
            $this->bindQuery($query);
            $isInserted = $query->execute();
        } catch (Exception $ex) {
            // error log
        }finally{
            $connection->close_con();
        }
        return $isInserted;
    }


    function insertPollData($poll){
        try{
            
            $connection = new ConexionAnaliizoPostgres();
            //$connection->exec(self::$CHARSET_QUERY);
            $query = $connection->prepare("INSERT INTO administracion.poll (pollcontent) VALUES (?)");

            $query->bindParam(1, $poll, PDO::PARAM_STR);
            
            $isInserted = $query->execute();
        } catch (Exception $ex) {
            // error log
        }finally{
            $connection->close_con();
        }
        return $isInserted;
    }

    
    /*
     * Actualiza la información de el recurso en la base de datos
     * @param array() $client_resource_array_data arreglo asociativo con los datos del recurso
     * @return bool true si se actualizó la información con exito en la base de datos, de lo contrario false.
     */
    function update($client_resource_array_data = array()){
        try{
            $connection = new ConexionAnaliizoPostgres();
            $connection->exec(self::$CHARSET_QUERY);
            $query = $connection->prepare(self::$SELECT_QUERY . self::$UPDATE_PROCEDURE);
            $this->setClientResourceData($client_resource_array_data);
            
            // establecemos los atributos $dtcreatedate y $modifieddate con la fecha actual
            // la fecha de creación no será modificada en la base de datos
            $date = date(self::$DATE_FORMAT);
            $this->setCreateDate($date);
            $this->setModifiedDate($date);

            $this->bindQuery($query);
            $isUpdated = $query->execute();
        } catch (Exception $ex) {
            // error log
        }  finally {
            $connection->close_con();
        }
        return $isUpdated;
    }
    
    /*
     * Elimina lógicamente un recurso de la base de datos
     * @param int $intidresource el id de la recurso
     * @return bool true si se elimina exitosamente el recurso de la base de datos, de lo contrario false
     */
    function delete($intidresource = 0){
        if($intidresource){
            try{
                $connection = new ConexionAnaliizoPostgres();
                $connection->exec(self::$CHARSET_QUERY);
                $query = $connection->prepare(self::$SELECT_QUERY . self::$DELETE_PROCEDURE);
                $query->bindParam(1, $intidresource, PDO::PARAM_INT);
                $query->bindParam(2, date(self::$DATE_FORMAT), PDO::PARAM_STR);
                $isDeleted = $query->execute();
            } catch (Exception $ex) {
                // error log
            }  finally {
                $connection->close_con();
            }
        }
        return $isDeleted;
    }
    
     /*
     * convierte los atributos de la clase y sus valores a un arreglo asociativo
     * return array arreglo asociativo con los atributos de la clase con llave y sus respectivos valores
     */
    function toArray(){
        return array(
            'intidrecurso' => $this->getId(),
            'strnombrerecurso' => $this->getName(),
            'oidrecurso' => $this->getContent(),
            'intsizeresource' => $this->getContentLength(),
            'strtiporecurso' => $this->getType(),
            'intidregistereduser' => $this->getIdRegisteredUser(),
            'intstatus' => $this->getStatus(),
            'dtcreatedate' => $this->getCreateDate(),
            'dtmodifieddate' => $this->getModifiedDate(),
            'intidcliente' => $this->getIdClient()
        );
    }
    
    /*
     * Getters
     */
    function getId(){
        return $this->intidrecurso;
    }
    
    function getName(){
        return $this->strnombrerecurso;
    }
    
    function getContent(){
        return $this->oidrecurso;
    }
    
    function getContentLength(){
        return $this->intsizeresource;
    }
    
    function getType(){
        return $this->strtiporecurso;
    }
    
    function getIdRegisteredUser(){
        return $this->intidregistereduser;
    }
    
    function getStatus(){
        return $this->intstatus;
    }
    
    function getCreateDate(){
        return $this->dtcreatedate;
    }
    
    function getModifiedDate(){
        return $this->dtmodifieddate;
    }
    
    function getIdClient(){
        return $this->intidcliente;
    }
    
    /*
     * Setters
     */
    
    function setId($intidresource){
        $this->intidrecurso = $intidresource;
    }
    
    function setContent($content){
        $this->oidrecurso =$content;
    }
    
    function setCreateDate($createdate){
        $this->dtcreatedate = $createdate;
    }
    
    function setModifiedDate($modifieddate){
        $this->dtmodifieddate = $modifieddate;
    }
}
