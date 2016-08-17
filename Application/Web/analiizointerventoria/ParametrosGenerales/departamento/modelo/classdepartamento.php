<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class Departament {

    private $intIdDepartament;
    private $strCodDepartament;
    private $strNombreDepartament;
    private $strCodCountry;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'departamento';

    public function __construct($strCodCountry = null, $strCodDepartament = null, $strNombreDepartament = null, $intIdDepartament = null, $strRegisteredUser = null, $dtFechaRegistro = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {
        $this->intIdDepartament = $intIdDepartament;
        $this->strCodDepartament = $strCodDepartament;
        $this->strNombreDepartament = $strNombreDepartament;
        $this->strCodCountry = $strCodCountry;
        $this->strRegisteredUser = $strRegisteredUser;
        $this->dtFechaRegistro = $dtFechaRegistro;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    function getIntIdDepartament() {
        return $this->intIdDepartament;
    }

    function getStrCodDepartament() {
        return $this->strCodDepartament;
    }

    function getStrNombreDepartament() {
        return $this->strNombreDepartament;
    }

    function getStrCodCountry() {
        return $this->strCodCountry;
    }

    function getStrRegisteredUser() {
        return $this->strRegisteredUser;
    }

    function getIstatusId() {
        return $this->IstatusId;
    }

    function getDtCreateDate() {
        return $this->dtCreateDate;
    }

    function getDtModifiedDate() {
        return $this->dtModifiedDate;
    }

    function setIntIdDepartament($intIdDepartament) {
        $this->intIdDepartament = $intIdDepartament;
    }

    function setStrCodDepartament($strCodDepartament) {
        $this->strCodDepartament = $strCodDepartament;
    }

    function setStrNombreDepartament($strNombreDepartament) {
        $this->strNombreDepartament = $strNombreDepartament;
    }

    function setStrCodCountry($strCodCountry) {
        $this->strCodCountry = $strCodCountry;
    }

    function setStrRegisteredUser($strRegisteredUser) {
        $this->strRegisteredUser = $strRegisteredUser;
    }

    function setIstatusId($IstatusId) {
        $this->IstatusId = $IstatusId;
    }

    function setDtCreateDate($dtCreateDate) {
        $this->dtCreateDate = $dtCreateDate;
    }

    function setDtModifiedDate($dtModifiedDate) {
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function guardar() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        if ($this->intIdDepartament) /* Modifica */ {
            try {
                $strRegisteredUser = $_SESSION['user'];
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_interventoria.spUpdateDepartment(?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdDepartament, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strCodDepartament, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strNombreDepartament, PDO::PARAM_STR);
                $consulta->bindParam(4, $this->strCodCountry, PDO::PARAM_STR);
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

                $consulta = $conexion->prepare('select * from analiizo_interventoria.spInsertDepartment(?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->strCodDepartament, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strNombreDepartament, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strCodCountry, PDO::PARAM_STR);
                $consulta->bindParam(4, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(5, $IstatusId, PDO::PARAM_STR);
                $consulta->bindParam(6, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(7, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->intIdDepartament = $conexion->lastInsertId();

                //Llama a la función auditoria
                //$procedimiento = $consulta;
                $procedimiento = 'select * from analiizo_interventoria.spInsertDepartment(' . $this->strCodDepartament . $this->strNombreDepartament . $strRegisteredUser . $dtCreateDate . ')';
                //Departamento::guardarAuditoria($procedimiento);
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function eliminar($intIdDepartament) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_interventoria.spDeletedDepartment(?,?);');
        $consulta->bindParam(1, $this->intIdDepartament, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function buscarPorId($intIdDepartament) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedDepartmentxId(?);');
        $consulta->bindParam(1, $intIdDepartament, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strcodcountry'], $registro['strcoddepartament'], $registro['strdepartamentname'], $intIdDepartament);
        } else {
            return false;
        }
    }

    public static function buscarPorCod($strCodDepartament) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedDepartamentxCod(?);');
        $consulta->bindParam(1, $strCodDepartament, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strdepartamentname'], $strCodDepartament);
        } else {
            return 'false';
        }
    }

    public static function recuperarTodos() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedDepartment();');
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

            $consulta = $conexion->prepare('select * from analiizo_interventoria.spInsertAudit(?,?,?,?);');
            $consulta->bindParam(1, $strUsuarioId, PDO::PARAM_STR);
            $consulta->bindParam(2, $strProcedimiento, PDO::PARAM_STR);
            $consulta->bindParam(3, $strRegisteredUser, PDO::PARAM_STR);
            $consulta->bindParam(4, $dtFechaRegistro, PDO::PARAM_STR);

            $consulta->execute();
            $this->strCodCountry = $conexion->lastInsertId();
        }
        $conexion = null;
    }

}
