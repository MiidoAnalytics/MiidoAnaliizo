<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class Eps {

    private $intIdEps;
    private $strCodEps;
    private $strNameEps;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'Usuarios';

    public function __construct($intIdEps = null, $strCodEps = null, $strNameEps = null,
        $strRegisteredUser = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {

        $this->intIdEps = $intIdEps;
        $this->strCodEps = $strCodEps;
        $this->strNameEps = $strNameEps;
        $this->strRegisteredUser = $strRegisteredUser;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getintIdEps() {
        return $this->intIdEps;
    }

    public function getCodEps() {
        return $this->strCodEps;
    }

    public function getNamEps() {
        return $this->strNameEps;
    }

    public function getRegisteredUser() {
        return $this->$strRegisteredUser;
    }

    public function getIstatusId() {
        return $this->$IstatusId;
    }

    public function getCreateDate() {
        return $this->$dtCreateDate;
    }

    public function getModifiedDate() {
        return $this->$dtModifiedDate;
    }

    public function setintIdEps($intIdEps) {
        $this->intIdEps = $intIdEps;
    }

    public function setCodEps($strCodEps) {
        $this->strCodEps = $strCodEps;
    }

    public function setNamEps($strNameEps) {
        $this->strNameEps = $strNameEps;
    }

    public function setRegisteredUser($strRegisteredUser) {
        $this->strRegisteredUser = $strRegisteredUser;
    }

    public function setIstatusId($IstatusId) {
        $this->IstatusId = $IstatusId;
    }

    public function setCreateDate($dtCreateDate) {
        $this->dtCreateDate = $dtCreateDate;
    }

    public function setModifiedDate($dtModifiedDate) {
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function insert() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8"); 
        if ($this->intIdEps)/* Modifica */ {
            try {

                $strRegisteredUser = $_SESSION['user'];
                $dtFechaRegistro = date("Ymd");
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_salud.spUpdateEps(?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdEps, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->strCodEps, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strNameEps, PDO::PARAM_STR);
                $consulta->bindParam(4, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtModifiedDate, PDO::PARAM_STR);

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

                $consulta = $conexion->prepare('select * from analiizo_salud.spInsertEps(?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->strCodEps, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strNameEps, PDO::PARAM_STR);
                $consulta->bindParam(3, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(4, $IstatusId, PDO::PARAM_INT);
                $consulta->bindParam(5, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(6, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->intIdEps = $conexion->lastInsertId();

            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function delete($intIdEps) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_salud.spdeletedeps(?,?);');
        $consulta->bindParam(1, $this->intIdEps, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function searchById($intIdEps) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $consulta = $conexion->prepare('select * from analiizo_salud.spselectedepsxid(?);');
        $consulta->bindParam(1, $intIdEps, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($intIdEps, $registro['strcodeps'], $registro['strnameps']);
        } else {
            return false;
        }
    }

    //****************************************************************************
    //Funcion que obtiene todos las EPS activas en la tabla eps
    //****************************************************************************
    public static function getAllEps() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        
        $consulta = $conexion->prepare('select * from analiizo_salud.spselectedeps();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }
}
