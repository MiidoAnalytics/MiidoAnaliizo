<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class Pais {

    private $intIdPais;
    private $strCodidPais;
    private $strNombreidPais;
    private $strUsuarioRegistra;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'pais';

    public function __construct($strCodidPais = null, $strNombreidPais = null, $intIdPais = null, $strUsuarioRegistra = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {
        $this->intIdPais = $intIdPais;
        $this->strCodidPais = $strCodidPais;
        $this->strNombreidPais = $strNombreidPais;
        $this->strUsuarioRegistra = $strUsuarioRegistra;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getIdPais() {
        return $this->intIdPais;
    }

    public function getCodidPais() {
        return $this->strCodidPais;
    }

    public function getNombreidPais() {
        return $this->strNombreidPais;
    }

    public function getUsuarioRegistra() {
        return $this->$strUsuarioRegistra;
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

    public function setCodidPais($strCodidPais) {
        $this->strCodidPais = $strCodidPais;
    }

    public function setNombreidPais($strNombreidPais) {
        $this->strNombreidPais = $strNombreidPais;
    }

    public function setUsuarioRegistra($strUsuarioRegistra) {
        $this->strUsuarioRegistra = $strUsuarioRegistra;
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
        if ($this->intIdPais) /* Modifica */ {
            try {
                $strUsuarioRegistra = $_SESSION['user'];
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_interventoria.spUpdateCountry(?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdPais, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strCodidPais, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strNombreidPais, PDO::PARAM_STR);
                $consulta->bindParam(4, $strUsuarioRegistra, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        } else /* Inserta */ {
            try {
                $strUsuarioRegistra = $_SESSION['user'];
                $IstatusId = '1';
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_interventoria.spInsertCountry(?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->strCodidPais, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strNombreidPais, PDO::PARAM_STR);
                $consulta->bindParam(3, $strUsuarioRegistra, PDO::PARAM_STR);
                $consulta->bindParam(4, $IstatusId, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(6, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->intIdPais = $conexion->lastInsertId();

                /* $procedimiento = 'call * from spInsertCountry(' . $this->strCodidPais . $this->strNombreidPais . $strUsuarioRegistra . $dtCreateDate . ')';
                  Pais::guardarAuditoria($procedimiento); */
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function eliminar($intIdPais) {

        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spDeletedCountry(?,?);');
        $consulta->bindParam(1, $this->intIdPais, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function buscarPorId($intIdPais) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedCountryxId(?);');
        $consulta->bindParam(1, $intIdPais, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strcodcountry'], $registro['strcountryname'], $intIdPais);
        } else {
            return false;
        }
    }

    public static function buscarPorCod($strCodidPais) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedCountryxCod(?);');
        $consulta->bindParam(1, $strCodidPais, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strcountryname'], $strCodidPais);
        } else {
            return false;
        }
    }

    public static function recuperarTodos() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedCountry();');
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
            $strUsuarioRegistra = $_SESSION['user'];
            $dtCreateDate = date("Ymd");

            $consulta = $conexion->prepare('call * from spInsertAudit(?,?,?,?);');
            $consulta->bindParam(1, $strUsuarioId, PDO::PARAM_STR);
            $consulta->bindParam(2, $strProcedimiento, PDO::PARAM_STR);
            $consulta->bindParam(3, $strUsuarioRegistra, PDO::PARAM_STR);
            $consulta->bindParam(4, $dtCreateDate, PDO::PARAM_STR);

            $consulta->execute();
            $this->intIdPais = $conexion->lastInsertId();
        }
        $conexion = null;
    }

}
