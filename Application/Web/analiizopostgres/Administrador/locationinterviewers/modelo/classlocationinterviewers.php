<?php

if (!defined('CONTROLADOR'))
    exit;

@session_start();

require_once '../../../../conexiones/classconexion.php';

class LocationInterviewers {

    private $intIdlocationInterviewer;  
    private $intIdInterviewers;
    private $strIdTown;
    private $strIdDepartament;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'locationinterviewers';

    public function __construct($intIdInterviewers = null, $strIdTown = null, $strIdDepartament = null, $intIdlocationInterviewer = null, $strRegisteredUser = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {
        $this->intIdlocationInterviewer = $intIdlocationInterviewer;
        $this->intIdInterviewers = $intIdInterviewers;
        $this->strIdTown = $strIdTown;
        $this->strIdDepartament = $strIdDepartament;
        $this->strRegisteredUser = $strRegisteredUser;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getLocationInterviewer() {
        return $this->intIdlocationInterviewer;
    }

    public function getIdInterviewer() {
        return $this->intIdInterviewers;
    }

    public function getCodTown() {
        return $this->strIdTown;
    }

    public function getCodDepartament() {
        return $this->strIdDepartament;
    }

    public function getRegisteredUser() {
        return $this->$strRegisteredUser;
    }

    public function getStatusId() {
        return $this->$IstatusId;
    }

    public function getCreateDate() {
        return $this->$dtCreateDate;
    }

    public function getModifiedDate() {
        return $this->$dtModifiedDate;
    }

    public function setLocationInterviewer($intIdlocationInterviewer) {
        $this->intIdlocationInterviewer = $intIdlocationInterviewer;
    }

    public function setIdInterviewer($intIdInterviewers) {
        $this->intIdInterviewers = $intIdInterviewers;
    }

    public function setCodTown($strIdTown) {
        $this->strIdTown = $strIdTown;
    }

    public function setCodDepartament($strIdDepartament) {
        $this->strIdDepartament = $strIdDepartament;
    }

    public function setRegisteredUser($strRegisteredUser) {
        $this->strRegisteredUser = $strRegisteredUser;
    }

    public function setStatusId($IstatusId) {
        $this->IstatusId = $IstatusId;
    }

    public function setModifiedDate($dtModifiedDate) {
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function setCreateDate($dtCreateDate) {
        $this->dtCreateDate = $dtCreateDate;
    }

    public function insert($intIdInterviewer, $strCodTown,$strCodDepartament) {
        $conexion = new ConexionAnaliizoPostgres();
        try {
            //            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' (strIdTown, strNombreidPais) VALUES(:strIdTown, :strNombreidPais)');
            //            $consulta->bindParam(':strIdTown', $this->strIdTown);
            //            $consulta->bindParam(':strNombreidPais', $this->strNombreidPais);
            $strRegisteredUser = $_SESSION['user'];
            $IstatusId = '1';
            $dtCreateDate = date("Ymd");
            $dtModifiedDate = date("Ymd");

            /*$this->intIdInterviewers;
            $this->strIdDepartament;
            $this->strIdTown;*/

            $consulta = $conexion->prepare('select * from administracion.spInsertLocationInterviewers(?,?,?,?,?,?,?);');
            $consulta->bindParam(1, $intIdInterviewer, PDO::PARAM_INT);
            $consulta->bindParam(2, $strCodTown, PDO::PARAM_STR);
            $consulta->bindParam(3, $strIdDepartament, PDO::PARAM_STR);
            $consulta->bindParam(4, $strRegisteredUser, PDO::PARAM_STR);
            $consulta->bindParam(5, $IstatusId, PDO::PARAM_STR);
            $consulta->bindParam(6, $dtCreateDate, PDO::PARAM_STR);
            $consulta->bindParam(7, $dtModifiedDate, PDO::PARAM_STR);

            $consulta->execute();
            $this->intIdlocationInterviewer = $conexion->lastInsertId();

            //Llama a la función auditoria
            //$procedimiento = $consulta;
            /* $procedimiento = 'select * from administracion.spInsertCountry('. $this->strIdTown . $this->strNombreidPais . $strRegisteredUser . $dtCreateDate . ')';
              Pais::guardarAuditoria($procedimiento); */
        } catch (Exception $ex) {
            echo 'Excepción capturada: ', $ex->getMessage(), "\n";
        }
        $conexion = null;
    }

    public function updateInterviewerLocation($intIdInterviewers, $strIdTown, $strIdDepartament) {
        $conexion = new ConexionAnaliizoPostgres();

        try {
            $strRegisteredUser = $_SESSION['user'];
            $IstatusId = '1';
            $dtCreateDate = date("Ymd");
            $dtModifiedDate = date("Ymd");

            $this->intIdInterviewers;
            $this->strIdTown;
            $this->strIdDepartament;

            $consulta = $conexion->prepare('select * from administracion.spUpdateInterviewersxLocations(?,?,?,?,?,?,?);');
            $consulta->bindParam(1, $intIdInterviewers, PDO::PARAM_INT);
            $consulta->bindParam(2, $strIdTown, PDO::PARAM_STR);
            $consulta->bindParam(3, $strIdDepartament, PDO::PARAM_STR);
            $consulta->bindParam(4, $strRegisteredUser, PDO::PARAM_STR);
            $consulta->bindParam(5, $IstatusId, PDO::PARAM_INT);
            $consulta->bindParam(6, $dtCreateDate, PDO::PARAM_STR);
            $consulta->bindParam(7, $dtModifiedDate, PDO::PARAM_STR);

            $consulta->execute();
            $this->intIdlocationInterviewer = $conexion->lastInsertId();

            //Llama a la función auditoria
            //$procedimiento = $consulta;
            /* $procedimiento = 'select * from administracion.spInsertCountry('. $this->strIdTown . $this->strNombreidPais . $strRegisteredUser . $dtCreateDate . ')';
              Pais::guardarAuditoria($procedimiento); */
        } catch (Exception $ex) {
            echo 'Excepción capturada: ', $ex->getMessage(), "\n";
        }
    }

    public function freeTablets($intIdInterviewers, $strIdTown) {
        $conexion = new ConexionAnaliizoPostgres();
//        $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' WHERE intIdInterviewer = :intIdInterviewer');
//        $consulta->bindParam(':intIdInterviewer', $this->intIdInterviewer);

        $dtCreateDate = date("Ymd");
        $consulta = $conexion->prepare('select * from administracion.spFreeTabletsInterviewer(?,?,?);');
        $consulta->bindParam(1, $intIdInterviewers, PDO::PARAM_INT);
        $consulta->bindParam(2, $strIdTown, PDO::PARAM_INT);
        $consulta->bindParam(3, $dtCreateDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function buscarPorId($intIdInterviewers) {
        $conexion = new ConexionAnaliizoPostgres();
        //$consulta = $conexion->prepare('SELECT strIdTown, strNombreidPais FROM ' . self::TABLA . ' WHERE intIdInterviewer = :intIdInterviewer');
        //$consulta->bindParam(':intIdInterviewer', $intIdInterviewer);

        $consulta = $conexion->prepare('select * from administracion.spSelectedCountryxId(?);');
        $consulta->bindParam(1, $intIdInterviewers, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strcodcountry'], $registro['strcountryname'], $intIdInterviewers);
        } else {
            return false;
        }
    }

    public static function searchUserWithLocation($intIdInterviewers) {

        //return $intIdInterviewers;
        $conexion = new ConexionAnaliizoPostgres();
        
        $consulta = $conexion->prepare('select * from administracion.spSelectedUserWithLocationxId(?);');
        $consulta->bindParam(1, $intIdInterviewers, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function getAll() {
        $conexion = new ConexionAnaliizoPostgres();
        $consulta = $conexion->prepare('select * from administracion.spSelectedLocationInterviewers();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //Function que guarda en la tabla audit
    public function guardarAuditoria($vStrProcedimiento) {
        $conexion = new ConexionAnaliizoPostgres();
        /* Inserta */ {
//            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' (strIdTown, strNombreidPais) VALUES(:strIdTown, :strNombreidPais)');
//            $consulta->bindParam(':strIdTown', $this->strIdTown);
//            $consulta->bindParam(':strNombreidPais', $this->strNombreidPais);

            $strUsuarioId = $_SESSION['userId'];
            $strProcedimiento = $vStrProcedimiento; //'procedimiento';
            $strRegisteredUser = $_SESSION['user'];
            $dtCreateDate = date("Ymd");

            $consulta = $conexion->prepare('select * from administracion.spInsertAudit(?,?,?,?);');
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
