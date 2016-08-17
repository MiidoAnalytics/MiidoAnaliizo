<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class Town {

    private $intIdTowns;
    private $strCodTown;
    private $strTownName;
    private $strCodDepartament;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'Town';

    public function __construct($strCodTown = null, $strTownName = null, $intIdTowns = null, $strCodDepartament = null, $strRegisteredUser = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {
        $this->intIdTowns = $intIdTowns;
        $this->strCodTown = $strCodTown;
        $this->strTownName = $strTownName;
        $this->strCodDepartament = $strCodDepartament;
        $this->strRegisteredUser = $strRegisteredUser;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getIdTown() {
        return $this->intIdTowns;
    }

    public function getCodTown() {
        return $this->strCodTown;
    }

    public function getNombreTown() {
        return $this->strTownName;
    }

    public function getCodDepartment() {
        return $this->strCodDepartament;
    }

    public function getUsuarioRegistra() {
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

    public function setIdTown($intIdTowns) {
        $this->intIdTowns = $intIdTowns;
    }

    public function setCodTown($strCodTown) {
        $this->strCodTown = $strCodTown;
    }

    public function setNombreTown($strTownName) {
        $this->strTownName = $strTownName;
    }

    public function setCodDepartment($strCodDepartament) {
        $this->strCodDepartament = $strCodDepartament;
    }

    public function setUsuarioRegistra($strRegisteredUser) {
        $this->strRegisteredUser = $strRegisteredUser;
    }

    public function setStatusId($IstatusId) {
        $this->IstatusId = $IstatusId;
    }

    public function setCreateDate($dtCreateDate) {
        $this->dtCreateDate = $dtCreateDate;
    }

    public function setModifiedDate($dtModifiedDate) {
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function guardar() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        if ($this->intIdTowns) /* Modifica */ {
            try {
                $strRegisteredUser = $_SESSION['user'];
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from administracion.spUpdateTown(?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdTowns, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->strCodTown, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strTownName, PDO::PARAM_STR);
                $consulta->bindParam(4, $this->strCodDepartament, PDO::PARAM_STR);
                $consulta->bindParam(5, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(6, $dtModifiedDate, PDO::PARAM_STR);

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

                $consulta = $conexion->prepare('select * from administracion.spInsertTown(?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->strCodTown, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strTownName, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strCodDepartament, PDO::PARAM_STR);
                $consulta->bindParam(4, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(5, $IstatusId, PDO::PARAM_INT);
                $consulta->bindParam(6, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(7, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->intIdTowns = $conexion->lastInsertId();

                //Llama a la función auditoria
                //$procedimiento = $consulta;
                /* $procedimiento = 'call spInsertCountry('. $this->strCodTown . $this->strNombreTown . $strRegisteredUser . dtCreateDate . ')';
                  Pais::guardarAuditoria($procedimiento); */
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function delete($intIdTowns) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from administracion.spDeletedTown(?,?);');
        $consulta->bindParam(1, $this->intIdTowns, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function buscarPorId($intIdTowns) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spSelectedTownxId(?);');
        $consulta->bindParam(1, $intIdTowns, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strcodtown'], $registro['strtownname'], $intIdTowns);
        } else {
            return false;
        }
    }

    public static function searchByCod($strCodDepartament) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spselectedtownxcoddepto(?);');
        $consulta->bindParam(1, $strCodDepartament, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetchAll();
        $conexion = null;
        if ($registro) {
            return $registro;
        } else {
            return false;
        }
    }
    
    public static function searchByCodDepto($strCodDepartament) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spSelectedTownxCodDepto(?);');
        $consulta->bindParam(1, $strCodDepartament, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetchAll();
        $conexion = null;
        if ($registro) {
            return $registro;
        } else {
            return false;
        }
    }

    public static function recuperarTodos() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spSelectedTown();');
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
            $dtCreateDate = date("Ymd");

            $consulta = $conexion->prepare('select * from administracion.spInsertAudit(?,?,?,?);');
            $consulta->bindParam(1, $strUsuarioId, PDO::PARAM_STR);
            $consulta->bindParam(2, $strProcedimiento, PDO::PARAM_STR);
            $consulta->bindParam(3, $strRegisteredUser, PDO::PARAM_STR);
            $consulta->bindParam(4, $dtCreateDate, PDO::PARAM_STR);

            $consulta->execute();
            $this->intIdTowns = $conexion->lastInsertId();
        }
        $conexion = null;
    }

}
