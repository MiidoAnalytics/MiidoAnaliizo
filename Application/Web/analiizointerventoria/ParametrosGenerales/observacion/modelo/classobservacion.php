<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class Observacion {

    private $intIdObservacion;
    private $strNombreObservacion;
    private $strUsuarioRegistra;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'Observacion';

    public function __construct($strNombreObservacion = null, $intIdObservacion = null, $strUsuarioRegistra = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {
        $this->intIdObservacion = $intIdObservacion;
        $this->strNombreObservacion = $strNombreObservacion;
        $this->strUsuarioRegistra = $strUsuarioRegistra;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getIdObservacion() {
        return $this->intIdObservacion;
    }

    public function getNombreObservacion() {
        return $this->strNombreObservacion;
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

    public function setNombreObservacion($strNombreObservacion) {
        $this->strNombreObservacion = $strNombreObservacion;
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
        if ($this->intIdObservacion) /* Modifica */ {
            try {
                $strUsuarioRegistra = $_SESSION['user'];
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_interventoria.updateOservationSp(?,?,?,?);');
                $consulta->bindParam(1, $this->intIdObservacion, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strNombreObservacion, PDO::PARAM_STR);
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

                $consulta = $conexion->prepare('select * from analiizo_interventoria.insertOservationSp(?,?,?,?,?);');
                $consulta->bindParam(1, $this->strNombreObservacion, PDO::PARAM_STR);
                $consulta->bindParam(2, $strUsuarioRegistra, PDO::PARAM_STR);
                $consulta->bindParam(3, $IstatusId, PDO::PARAM_INT);
                $consulta->bindParam(4, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->intIdObservacion = $conexion->lastInsertId();

            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function eliminar($intIdObservacion) {

        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.deletedOservationSp(?,?);');
        $consulta->bindParam(1, $this->intIdObservacion, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function buscarPorIdObservacion($intIdObservacion) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getOservationByIdSp(?);');
        $consulta->bindParam(1, $intIdObservacion, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strdescricpion'], $intIdObservacion);
        } else {
            return false;
        }
    }

    public static function recuperarTodos() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getAllOservationsSp();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
}
