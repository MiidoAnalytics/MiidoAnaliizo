<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class Equipo {

    private $intIdEquipo;
    private $strNombreEquipo;
    private $strUsuarioRegistra;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'Equipo';

    public function __construct($strNombreEquipo = null, $intIdEquipo = null, $strUsuarioRegistra = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {
        $this->intIdEquipo = $intIdEquipo;
        $this->strNombreEquipo = $strNombreEquipo;
        $this->strUsuarioRegistra = $strUsuarioRegistra;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getIdEquipo() {
        return $this->intIdEquipo;
    }

    public function getNombreEquipo() {
        return $this->strNombreEquipo;
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

    public function setNombreEquipo($strNombreEquipo) {
        $this->strNombreEquipo = $strNombreEquipo;
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
        if ($this->intIdEquipo) /* Modifica */ {
            try {
                $strUsuarioRegistra = $_SESSION['user'];
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_interventoria.updateMachineSp(?,?,?,?);');
                $consulta->bindParam(1, $this->intIdEquipo, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strNombreEquipo, PDO::PARAM_STR);
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

                $consulta = $conexion->prepare('select * from analiizo_interventoria.insertMachineSp(?,?,?,?,?);');
                $consulta->bindParam(1, $this->strNombreEquipo, PDO::PARAM_STR);
                $consulta->bindParam(2, $strUsuarioRegistra, PDO::PARAM_STR);
                $consulta->bindParam(3, $IstatusId, PDO::PARAM_INT);
                $consulta->bindParam(4, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->intIdEquipo = $conexion->lastInsertId();

            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function eliminar($intIdEquipo) {

        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.deletedMachineSp(?,?);');
        $consulta->bindParam(1, $this->intIdEquipo, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function buscarPorIdEquipo($intIdEquipo) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getMachineByIdSp(?);');
        $consulta->bindParam(1, $intIdEquipo, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strnombreequipo'], $intIdEquipo);
        } else {
            return false;
        }
    }

    public static function recuperarTodos() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getAllMachinesSp();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
}
