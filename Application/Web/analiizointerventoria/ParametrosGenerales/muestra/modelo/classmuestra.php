<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class Muestra {

    private $intIdMuestra;
    private $strNombreMuestra;
    private $strUsuarioRegistra;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'Muestra';

    public function __construct($strNombreMuestra = null, $intIdMuestra = null, $strUsuarioRegistra = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {
        $this->intIdMuestra = $intIdMuestra;
        $this->strNombreMuestra = $strNombreMuestra;
        $this->strUsuarioRegistra = $strUsuarioRegistra;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getIdMuestra() {
        return $this->intIdMuestra;
    }

    public function getNombreMuestra() {
        return $this->strNombreMuestra;
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

    public function setNombreMuestra($strNombreMuestra) {
        $this->strNombreMuestra = $strNombreMuestra;
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
        if ($this->intIdMuestra) /* Modifica */ {
            try {
                $strUsuarioRegistra = $_SESSION['user'];
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_interventoria.updateSampleSp(?,?,?,?);');
                $consulta->bindParam(1, $this->intIdMuestra, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strNombreMuestra, PDO::PARAM_STR);
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

                $consulta = $conexion->prepare('select * from analiizo_interventoria.insertSampleSp(?,?,?,?,?);');
                $consulta->bindParam(1, $this->strNombreMuestra, PDO::PARAM_STR);
                $consulta->bindParam(2, $strUsuarioRegistra, PDO::PARAM_STR);
                $consulta->bindParam(3, $IstatusId, PDO::PARAM_INT);
                $consulta->bindParam(4, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->intIdMuestra = $conexion->lastInsertId();

            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function eliminar($intIdMuestra) {

        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.deletedSampleSp(?,?);');
        $consulta->bindParam(1, $this->intIdMuestra, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function buscarPorIdMuestra($intIdMuestra) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getSampleByIdSp(?);');
        $consulta->bindParam(1, $intIdMuestra, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strnombremuestra'], $intIdMuestra);
        } else {
            return false;
        }
    }

    public static function recuperarTodos() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getAllSamplesSp();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
}
