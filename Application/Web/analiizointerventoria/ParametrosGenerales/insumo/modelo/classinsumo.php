<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class Insumo {

    private $intIdInsumo;
    private $strNombreInsumo;
    private $strUsuarioRegistra;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'Insumo';

    public function __construct($strNombreInsumo = null, $intIdInsumo = null, $strUsuarioRegistra = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {
        $this->intIdInsumo = $intIdInsumo;
        $this->strNombreInsumo = $strNombreInsumo;
        $this->strUsuarioRegistra = $strUsuarioRegistra;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getIdInsumo() {
        return $this->intIdInsumo;
    }

    public function getNombreInsumo() {
        return $this->strNombreInsumo;
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

    public function setNombreInsumo($strNombreInsumo) {
        $this->strNombreInsumo = $strNombreInsumo;
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
        if ($this->intIdInsumo) /* Modifica */ {
            try {
                $strUsuarioRegistra = $_SESSION['user'];
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_interventoria.updateInputSp(?,?,?,?);');
                $consulta->bindParam(1, $this->intIdInsumo, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strNombreInsumo, PDO::PARAM_STR);
                $consulta->bindParam(3, $strUsuarioRegistra, PDO::PARAM_STR);
                $consulta->bindParam(4, $dtModifiedDate, PDO::PARAM_STR);

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

                $consulta = $conexion->prepare('select * from analiizo_interventoria.insertInputSp(?,?,?,?,?);');
                $consulta->bindParam(1, $this->strNombreInsumo, PDO::PARAM_STR);
                $consulta->bindParam(2, $strUsuarioRegistra, PDO::PARAM_STR);
                $consulta->bindParam(3, $IstatusId, PDO::PARAM_INT);
                $consulta->bindParam(4, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->intIdInsumo = $conexion->lastInsertId();

            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function eliminar($intIdInsumo) {

        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.deletedInputSp(?,?);');
        $consulta->bindParam(1, $this->intIdInsumo, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function buscarPorIdInsumo($intIdInsumo) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getInputByIdSp(?);');
        $consulta->bindParam(1, $intIdInsumo, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strnombreinsumo'], $intIdInsumo);
        } else {
            return false;
        }
    }

    public static function recuperarTodos() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getAllInputsSp();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
}
