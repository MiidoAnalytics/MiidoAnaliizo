<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class EncuestaInterviewer 
{

    private $intIdInterviewer;
    private $strUsername;
    private $Encuesta;
    private $Proyecto;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'interviewer';
    
    public function __construct($Encuesta=null, $Proyecto=null, $strUsername=null,$intIdInterviewer=null, $strRegisteredUser=null, $IstatusId=null, $dtCreateDate=null, $dtModifiedDate=null) 
    {
        $this->intIdInterviewer = $intIdInterviewer;
        $this->Proyecto = $Proyecto;
        $this->Encuesta = $Encuesta;  
        $this->strUsername = $strUsername;
        $this->strRegisteredUser = $strRegisteredUser;    
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }
    
    public function getIdInterviewer() {
        return $this->intIdInterviewer;
    }

    public function getEncuesta() {
        return $this->Encuesta;
    }

    public function getProyecto() {
        return $this->Proyecto;
    }

    public function getUsername() {
        return $this->strUsername;
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

    public function setEncuesta($Encuesta) {
        $this->Encuesta = $Encuesta;
    }

    public function setProyecto($Proyecto) {
        $this->Proyecto = $Proyecto;
    }

    public function setUsername($strUsername) {
        $this->strUsername = $strUsername;
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
            
    public function guardarEncuesaInterviewer($Encuesta, $intIdInterviewer) 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8"); 
        $intidusuarios = $_SESSION['userid'];
        $IstatusId = '1';
        $dtCreateDate = date("Ymd");
        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_salud.spInsertecncuestaInterviewer(?,?,?,?,?,?);');
        $consulta->bindParam(1, $Encuesta, PDO::PARAM_STR);            
        $consulta->bindParam(2, $intIdInterviewer, PDO::PARAM_STR);
        $consulta->bindParam(3, $intidusuarios, PDO::PARAM_STR);
        $consulta->bindParam(4, $IstatusId, PDO::PARAM_INT);
        $consulta->bindParam(5, $dtCreateDate, PDO::PARAM_STR);
        $consulta->bindParam(6, $dtModifiedDate, PDO::PARAM_STR);
        
        $consulta->execute();
        $this->intIdInterviewer = $conexion->lastInsertId();  
        $conexion = null;
    }

    public function guardarProyectoInterviewer($Proyecto, $intIdInterviewer) 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8"); 
        $intidusuarios = $_SESSION['userid'];
        $IstatusId = '1';
        $dtCreateDate = date("Ymd");
        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_salud.spInsertproyectoInterviewer(?,?,?,?,?,?);');
        $consulta->bindParam(1, $Proyecto, PDO::PARAM_STR);            
        $consulta->bindParam(2, $intIdInterviewer, PDO::PARAM_STR);
        $consulta->bindParam(3, $intidusuarios, PDO::PARAM_STR);
        $consulta->bindParam(4, $IstatusId, PDO::PARAM_INT);
        $consulta->bindParam(5, $dtCreateDate, PDO::PARAM_STR);
        $consulta->bindParam(6, $dtModifiedDate, PDO::PARAM_STR);
        
        $consulta->execute();
        $this->intIdInterviewer = $conexion->lastInsertId();  
        $conexion = null;
    }
    
    public function deleterelEstructura($intIdInterviewer, $Encuesta)
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");
        $consulta = $conexion->prepare('select * from analiizo_salud.spDeletedInterviewerEncuesta(?,?,?);');
        $consulta->bindParam(1, $intIdInterviewer, PDO::PARAM_INT);
        $consulta->bindParam(2, $Encuesta, PDO::PARAM_INT);
        $consulta->bindParam(3, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public function deleterelProyectoEncues($intIdInterviewer, $Proyecto)
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");
        $consulta = $conexion->prepare('select * from analiizo_salud.spDeletedInterviewerProyecto(?,?,?);');
        $consulta->bindParam(1, $intIdInterviewer, PDO::PARAM_INT);
        $consulta->bindParam(2, $Proyecto, PDO::PARAM_INT);
        $consulta->bindParam(3, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function contarInterviewerEncuesta($intIdInterviewer) 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spselectedContarrEncuestaInterviewer(?);');
        $consulta->bindParam(1, $intIdInterviewer, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function validarInterviewerEncuesta($intIdInterviewer, $Encuesta) 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spselectedValidarEncuestaInterviewer(?,?);');
        $consulta->bindParam(1, $intIdInterviewer, PDO::PARAM_INT);
        $consulta->bindParam(2, $Encuesta, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function contarProyectoEncues($intIdInterviewer, $Proyecto) 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spselectedcontarproyectoencuestador(?,?);');
        $consulta->bindParam(1, $intIdInterviewer, PDO::PARAM_INT);
        $consulta->bindParam(2, $Proyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function ObtenerEncuestadores() 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spselectedinterviewers();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function ObternerEncuestasProyecto($Proyecto) 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedEncuestaProyectos(?);');
        $consulta->bindParam(1, $Proyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
    
    public static function ObtenerProyectos() 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedProyectos();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
    
    public static function getAllRegisters() 
    {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedEncuestaInter();');        
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

}

