<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class Interviewer 
{

    private $intIdInterviewer;
    private $strNames;
    private $strSurnames;
    private $strUsername;
    private $strHashPassword;
    private $intIdOperator;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'interviewer';
    
    public function __construct($strNames=null, $strSurnames=null, $strUsername=null,$intIdInterviewer=null, $strHashPassword=null, $intIdOperator=null,$strRegisteredUser=null, $IstatusId=null, $dtCreateDate=null, $dtModifiedDate=null) 
    {
        $this->intIdInterviewer = $intIdInterviewer;
        $this->strNames = $strNames;
        $this->strSurnames = $strSurnames;  
        $this->strUsername = $strUsername;
        $this->strHashPassword = $strHashPassword;
        $this->intIdOperator = $intIdOperator;
        $this->strRegisteredUser = $strRegisteredUser;    
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }
    
    public function getIdInterviewer() {
        return $this->intIdInterviewer;
    }

    public function getNames() {
        return $this->strNames;
    }

    public function getSurnames() {
        return $this->strSurnames;
    }

    public function getUsername() {
        return $this->strUsername;
    }

    public function getHashPassword() {
        return $this->strHashPassword;
    }

    public function getIdOperator() {
        return $this->$intIdOperator;
    }

    public function getRegisteredUser() {
        return $this->$strRegisteredUser;
    }

     public function getStatusId()
    {
        return $this->$IstatusId;
    }
    
    public function getCreateDate()
    {
        return $this->$dtCreateDate;
    }
    
    public function getModifiedDate()
    {
        return $this->$dtModifiedDate;
    }
    

    public function setIdInterviewer($intIdInterviewer) 
    {
        $this->intIdInterviewer = $intIdInterviewer;
    }

    public function setNames($strNames) {
        $this->strNames = $strNames;
    }

    public function setSurnames($strSurnames) {
        $this->strSurnames = $strSurnames;
    }

    public function setUsername($strUsername) {
        $this->strUsername = $strUsername;
    }

    public function setHashPassword($strHashPassword) {
        $this->strHashPassword = $strHashPassword;
    }

    public function setIdOperator($intIdOperator) {
        $this->intIdOperator = $intIdOperator;
    }

    public function setRegisteredUser($strRegisteredUser) 
    {
        $this->strRegisteredUser = $strRegisteredUser;
    }

    public function setStatusId($IstatusId) 
    {
        $this->IstatusId = $IstatusId;
    }
    
    public function setCreateDate($dtCreateDate) 
    {
        $this->dtCreateDate = $dtCreateDate;
    }
    
    public function setModifiedDate($dtModifiedDate) 
    {
        $this->dtModifiedDate = $dtModifiedDate;
    }
            
    public function guardar() 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        if ($this->intIdInterviewer) /* Modifica */ 
        {
            
            try
            {            
                //$consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET strCodidPais = :strCodidPais, strNombreidPais = :strNombreidPais WHERE intIdPais = :intIdPais');
                //$consulta->bindParam(':strCodidPais', $this->strCodidPais);
                //$consulta->bindParam(':strNombreidPais', $this->strNombreidPais);

                $strRegisteredUser = $_SESSION['user'];
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");
                $intIdOperator = null;
                
                $consulta = $conexion->prepare('select * from analiizo_salud.spUpdateInterviewer(?,?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdInterviewer, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->strNames, PDO::PARAM_STR);            
                $consulta->bindParam(3, $this->strSurnames, PDO::PARAM_STR);
                $consulta->bindParam(4, $this->strUsername, PDO::PARAM_STR);
                $consulta->bindParam(5, $this->strHashPassword, PDO::PARAM_STR);
                $consulta->bindParam(6, $intIdOperator, PDO::PARAM_INT);
                $consulta->bindParam(7, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(8, $dtModifiedDate, PDO::PARAM_STR);
                
                $consulta->execute();
            } 
            catch (Exception $ex) 
            {
                echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
            }
        } 
        else /* Inserta */ 
        {
            try 
            {
                $strRegisteredUser = $_SESSION['user'];
                $IstatusId = '1';
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");
                $intIdOperator = null;

                $consulta = $conexion->prepare('select * from analiizo_salud.spInsertInterviewer(?,?,?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->strNames, PDO::PARAM_STR);            
                $consulta->bindParam(2, $this->strSurnames, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strUsername, PDO::PARAM_STR);
                $consulta->bindParam(4, $this->strHashPassword, PDO::PARAM_STR);
                $consulta->bindParam(5, $intIdOperator, PDO::PARAM_INT);
                $consulta->bindParam(6, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(7, $IstatusId, PDO::PARAM_INT);
                $consulta->bindParam(8, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(9, $dtModifiedDate, PDO::PARAM_STR);
                
                $consulta->execute();
                $this->intIdInterviewer = $conexion->lastInsertId();  

            } catch (Exception $ex) 
            {
                echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }
    
    public function delete()
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");
        $consulta = $conexion->prepare('select * from analiizo_salud.spDeletedInterviewer(?,?);');
        $consulta->bindParam(1, $this->intIdInterviewer, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public function deleteRelTablet()
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");
        $consulta = $conexion->prepare('select * from analiizo_salud.spDeleteRelTablet(?,?);');
        $consulta->bindParam(1, $this->intIdInterviewer, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public function deleteRelLocation()
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");
        $consulta = $conexion->prepare('select * from analiizo_salud.spDeleteRelLocation(?,?);');
        $consulta->bindParam(1, $this->intIdInterviewer, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function buscarPorId($intIdinterviewer) 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedInterviewerxId(?);');
        $consulta->bindParam(1, $intIdinterviewer, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) 
        {
            return new self($registro['strnames'], $registro['strsurnames'],$registro['strusername'], $intIdinterviewer);
        } 
        else 
        {
            return 'false';
        }
    }

    public static function tabletAsignada($intIdinterviewer) 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");        
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedTabletAsignada(?);');
        $consulta->bindParam(1, $intIdinterviewer, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) 
        {
            return $registro['intidtablet'];
        } 
        else 
        {
            return 'false';
        }
    }
    
    public static function buscarPorCod($intIdDepto) 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedCountryxCod(?);');
        $consulta->bindParam(1, $intIdDepto, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) 
        {
            return new self($registro['intidpais'], $registro['strnombreidpais'], $intIdDepto);
        } 
        else 
        {
            return false;
        }
    }
    
    public static function recuperarTodos() 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        //$consulta = $conexion->prepare('SELECT intIdPais, strCodidPais, strNombreidPais FROM ' . self::TABLA . ' ORDER BY strCodidPais');
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedInterviewers();');        
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
    
    //Function que guarda en la tabla audit
    public function guardarAuditoria($vStrProcedimiento) 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        /* Inserta */ 
        {
//            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' (strCodidPais, strNombreidPais) VALUES(:strCodidPais, :strNombreidPais)');
//            $consulta->bindParam(':strCodidPais', $this->strCodidPais);
//            $consulta->bindParam(':strNombreidPais', $this->strNombreidPais);
            
            $strUsuarioId = $_SESSION['userId'];
            $strProcedimiento = $vStrProcedimiento;//'procedimiento';
            $strUsuarioRegistra = $_SESSION['user'];            
            $dtFechaRegistro = date("Ymd");
            
            $consulta = $conexion->prepare('select * from analiizo_salud.spInsertAudit(?,?,?,?);');
            $consulta->bindParam(1, $strUsuarioId, PDO::PARAM_STR);            
            $consulta->bindParam(2, $strProcedimiento, PDO::PARAM_STR);
            $consulta->bindParam(3, $strUsuarioRegistra, PDO::PARAM_STR);
            $consulta->bindParam(4, $dtFechaRegistro, PDO::PARAM_STR);
            
            $consulta->execute();
            $this->intIdPais = $conexion->lastInsertId();            
        }
        $conexion = null;
    }

}

