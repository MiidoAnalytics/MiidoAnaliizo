<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class Interviewersxtablets 
{
    private $intIdInterviewers;
    private $intIdTablet;
    private $strRegisteredUser;
    private $dtCreateDate;
    private $IstatusId;

    const TABLA = 'interviewersxtablets';
    
    public function __construct($intIdInterviewers=null, $intIdTablet=null, $strRegisteredUser=null, $dtCreateDate=null, $IstatusId=null) 
    {
        $this->intIdInterviewers = $intIdInterviewers;
        $this->intIdTablet = $intIdTablet;        
        $this->strRegisteredUser = $strRegisteredUser;   
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
    }

    public function getIdInterviewer() 
    {
        return $this->intIdInterviewers;
    }

    public function getIdTablet() 
    {
        return $this->intIdTablet;
    }
   
    public function getRegisteredUser() 
    {
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
       
     public function setIdInterviewer($intIdInterviewers) 
    {
        $this->intIdInterviewers = $intIdInterviewers;
    }
    
    public function setIdTablet($intIdTablet) 
    {
        $this->intIdTablet = $intIdTablet;
    }

    public function setNombreidPais($strNombreidPais) 
    {
        $this->strNombreidPais = $strNombreidPais;        
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
    
         
    
    public function insert() 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        /*if ($this->intIdInterviewers && $this->intIdTablet)  Modifica 
        {
            try
            {            
                //$consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET intIdTablet = :intIdTablet, strNombreidPais = :strNombreidPais WHERE intIdInterviewer = :intIdInterviewer');
                //$consulta->bindParam(':intIdTablet', $this->intIdTablet);
                //$consulta->bindParam(':strNombreidPais', $this->strNombreidPais);
                $strRegisteredUser = $_SESSION['user'];
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");
                
                $consulta = $conexion->prepare('select * from analiizo_interventoria.spUpdateCountry(?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdInterviewers, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->intIdTablet, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strNombreidPais, PDO::PARAM_STR);
                $consulta->bindParam(4, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(6, $dtModifiedDate, PDO::PARAM_STR);
                
                //$consulta->bindParam(':intIdInterviewer', $this->intIdInterviewer);
                $consulta->execute();
            } 
            catch (Exception $ex) 
            {
                echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
            }
        } 
        else /* Inserta */ 
        //{
            try 
            {
                //            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' (intIdTablet, strNombreidPais) VALUES(:intIdTablet, :strNombreidPais)');
                //            $consulta->bindParam(':intIdTablet', $this->intIdTablet);
                //            $consulta->bindParam(':strNombreidPais', $this->strNombreidPais);
                $strRegisteredUser = $_SESSION['user'];
                $IstatusId = '1';
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");
                
                $consulta = $conexion->prepare('select * from analiizo_interventoria.spInsertInterviewersxtablets(?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdInterviewers, PDO::PARAM_INT);            
                $consulta->bindParam(2, $this->intIdTablet, PDO::PARAM_INT);
                $consulta->bindParam(3, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(4, $IstatusId, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtCreateDate, PDO::PARAM_STR);
                
                $consulta->execute();
            } catch (Exception $ex) 
            {
                echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
            }
        $conexion = null;
    }
    
    public function updateTablexInterviewer($intIdInterviewers, $intIdTablet) 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        
                 try 
            {
                $strRegisteredUser = $_SESSION['user'];
                $IstatusId = '1';
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");
                
                $consulta = $conexion->prepare('select * from analiizo_interventoria.spUpdateInterviewersxtablets(?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdInterviewers, PDO::PARAM_INT);            
                $consulta->bindParam(2, $this->intIdTablet, PDO::PARAM_INT);
                $consulta->bindParam(3, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(4, $IstatusId, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtCreateDate, PDO::PARAM_STR);
                
                $consulta->execute();

            } catch (Exception $ex) 
            {
                echo 'Excepción capturada: ',  $ex->getMessage(), "\n";
            }
            
    }       
    
    public function freeTablets($intIdInterviewers, $intIdTablet)
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $strRegisteredUser = $_SESSION['user'];
        $dtCreateDate = date("Ymd");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spFreeTabletsInterviewer(?,?);');
        $consulta->bindParam(1, $intIdInterviewers, PDO::PARAM_INT);
        $consulta->bindParam(2, $intIdTablet, PDO::PARAM_INT);
        $consulta->execute();
        $conexion = null;
    }

    public function freeTablets2($intIdInterviewers, $intIdTablet)
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $strRegisteredUser = $_SESSION['user'];
        $dtCreateDate = date("Ymd");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spfreetabletsinterviewer2(?,?);');
        $consulta->bindParam(1, $intIdInterviewers, PDO::PARAM_INT);
        $consulta->bindParam(2, $intIdTablet, PDO::PARAM_INT);
        $consulta->execute();
        $conexion = null;
    }

    public static function buscarPorId($intIdInterviewers) 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");       
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedCountryxId(?);');
        $consulta->bindParam(1, $intIdInterviewers, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) 
        {
            return new self($registro['strcodcountry'], $registro['strcountryname'], $intIdInterviewers);
        } 
        else 
        {
            return false;
        }
    }
    
    public static function searchUserWithTablet($intIdInterviewers) 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedUserWithTablexId(?);');
        $consulta->bindParam(1, $intIdInterviewers, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) 
        {
            return true;
        } 
        else 
        {
            return false;
        }
    }
       
    public static function getAll() 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        //$consulta = $conexion->prepare('SELECT intIdInterviewer, intIdTablet, strNombreidPais FROM ' . self::TABLA . ' ORDER BY intIdTablet');
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedInterviewersxtablets();');        
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
//            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' (intIdTablet, strNombreidPais) VALUES(:intIdTablet, :strNombreidPais)');
//            $consulta->bindParam(':intIdTablet', $this->intIdTablet);
//            $consulta->bindParam(':strNombreidPais', $this->strNombreidPais);
            
            $strUsuarioId = $_SESSION['userId'];
            $strProcedimiento = $vStrProcedimiento;//'procedimiento';
            $strRegisteredUser = $_SESSION['user'];            
            $dtCreateDate = date("Ymd");
            
            $consulta = $conexion->prepare('select * from analiizo_interventoria.spInsertAudit(?,?,?,?);');
            $consulta->bindParam(1, $strUsuarioId, PDO::PARAM_STR);            
            $consulta->bindParam(2, $strProcedimiento, PDO::PARAM_STR);
            $consulta->bindParam(3, $strRegisteredUser, PDO::PARAM_STR);
            $consulta->bindParam(4, $dtCreateDate, PDO::PARAM_STR);
            
            $consulta->execute();
            $this->intIdInterviewers = $conexion->lastInsertId();            
        }
        $conexion = null;
    }

}
