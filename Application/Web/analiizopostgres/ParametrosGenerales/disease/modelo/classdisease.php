<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class Disease {

    private $intIdDiseases;
    private $strCodDisease;
    private $strNombreDisease;
    private $intIdComplement;
    private $intIdclassification;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'disease';

    public function __construct($strCodDisease = null, $strNombreDisease = null, $intIdDiseases = null, $intIdclassification = null, $intIdComplement = null, $strRegisteredUser = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {
        $this->intIdDiseases = $intIdDiseases;
        $this->strCodDisease = $strCodDisease;
        $this->strNombreDisease = $strNombreDisease;
        $this->intIdComplement = $intIdComplement;
        $this->intIdclassification = $intIdclassification;
        $this->strRegisteredUser = $strRegisteredUser;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getIdDisease() {
        return $this->intIdDiseases;
    }

    public function getCodDisease() {
        return $this->strCodDisease;
    }

    public function getNombreDisease() {
        return $this->strNombreDisease;
    }

    public function getIdComplement() {
        return $this->$intIdComplement;
    }

    public function getIdclassification() {
        return $this->$intIdclassification;
    }

    public function getRegisteredUser() {
        return $this->$strRegisteredUser;
    }

    public function getIstatusId() {
        return $this->$IstatusId;
    }

    public function getDtCreateDate() {
        return $this->$dtCreateDate;
    }

    public function getDtModifiedDate() {
        return $this->$dtModifiedDate;
    }

    public function setIdDisease($intIdDiseases) {
        $this->intIdDiseases = $intIdDiseases;
    }

    public function setCodDisease($strCodDisease) {
        $this->strCodDisease = $strCodDisease;
    }

    public function setNombreDisease($strNombreDisease) {
        $this->strNombreDisease = $strNombreDisease;
    }

    public function setIdComplement($intIdComplement) {
        $this->intIdComplement = $intIdComplement;
    }

    public function setIdclassification($intIdclassification) {
        $this->intIdclassification = $intIdclassification;
    }

    public function setRegisteredUser($strRegisteredUser) {
        $this->strRegisteredUser = $strRegisteredUser;
    }

    public function setIstatusId($IstatusId) {
        $this->IstatusId = $IstatusId;
    }

    public function setDtCreateDate($dtCreateDate) {
        $this->dtCreateDate = $dtCreateDate;
    }

    public function setDtModifiedDate($dtModifiedDate) {
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function guardar() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        if ($this->intIdDiseases) /* Modifica */ {
            try {
                $strRegisteredUser = $_SESSION['user'];
                // $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");
                $intIdclassification = null;
                $intIdComplement = null;

                $consulta = $conexion->prepare('select * from administracion.spUpdateDisease(?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdDiseases, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->strCodDisease, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strNombreDisease, PDO::PARAM_STR);
                $consulta->bindParam(4, $intIdclassification, PDO::PARAM_INT);
                $consulta->bindParam(5, $intIdComplement, PDO::PARAM_INT);
                $consulta->bindParam(6, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(7, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        } else /* Inserta */ {
            try {
                $strRegisteredUser = $_SESSION['user'];
                $IstatusId = '1';
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");
                $intIdclassification = null;
                $intIdComplement = null;
                $consulta = $conexion->prepare('select * from administracion.spInsertDisease(?,?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->strCodDisease, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strNombreDisease, PDO::PARAM_STR);
                $consulta->bindParam(3, $intIdclassification, PDO::PARAM_STR);
                $consulta->bindParam(4, $intIdComplement, PDO::PARAM_STR);
                $consulta->bindParam(5, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(6, $IstatusId, PDO::PARAM_STR);
                $consulta->bindParam(7, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(8, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();

                $this->intIdDiseases = $conexion->lastInsertId();

                //Llama a la función auditoria
                //$procedimiento = $consulta;
                /* $procedimiento = 'select * from administracion.spInsertDepartment('. $this->strCodDepartamento. $this->strNombreDepartamento . $strRegisteredUser . $dtFechaRegistro . ')';
                  Departamento::guardarAuditoria($procedimiento); */
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function delete($intIdDiseases) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");
        $consulta = $conexion->prepare('select * from administracion.spDeletedDisease(?,?);');
        $consulta->bindParam(1, $this->intIdDiseases, PDO::PARAM_STR);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function searchById($intIdDiseases) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        //$consulta = $conexion->prepare('SELECT strCodidPais, strNombreidPais FROM ' . self::TABLA . ' WHERE intIdPais = :intIdPais');
        //$consulta->bindParam(':intIdPais', $intIdPais);

        $consulta = $conexion->prepare('select * from administracion.spSelectedDiseasexId(?);');
        $consulta->bindParam(1, $intIdDiseases, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strcoddisease'], $registro['strdiseasename'], $intIdDiseases);
        } else {
            return false;
        }
    }

    public static function getAll() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spSelectedDisease();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //Function que guarda en la tabla audit
    public function guardarAuditoria($vStrProcedimiento) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        /* Inserta */ {
            $strUsuarioId = $_SESSION['userId'];
            $strProcedimiento = $vStrProcedimiento; //'procedimiento';
            $strRegisteredUser = $_SESSION['user'];
            $dtFechaRegistro = date("Ymd");

            $consulta = $conexion->prepare('select * from administracion.spInsertAudit(?,?,?,?);');
            $consulta->bindParam(1, $strUsuarioId, PDO::PARAM_STR);
            $consulta->bindParam(2, $strProcedimiento, PDO::PARAM_STR);
            $consulta->bindParam(3, $strRegisteredUser, PDO::PARAM_STR);
            $consulta->bindParam(4, $dtFechaRegistro, PDO::PARAM_STR);

            $consulta->execute();
            $this->intIdPais = $conexion->lastInsertId();
        }
        $conexion = null;
    }

}
