<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class Cups {

    private $intIdCup;
    private $strCodCups;
    private $strCupsName;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'Cups';

    public function __construct($strCodCups = null, $strCupsName = null, $intIdCup = null, $strRegisteredUser = null, $dtFechaRegistro = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {
        $this->intIdCup = $intIdCup;
        $this->strCodCups = $strCodCups;
        $this->strCupsName = $strCupsName;
        $this->strRegisteredUser = $strRegisteredUser;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getIdCup() {
        return $this->intIdCup;
    }

    public function getCodCups() {
        return $this->strCodCups;
    }

    public function getCupsName() {
        return $this->strCupsName;
    }

    public function getRegisteredUser() {
        return $this->$strRegisteredUser;
    }

    public function getFechaRegistro() {
        return $this->$dtFechaRegistro;
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

    public function setIdCup($intIdCup) {
        $this->intIdCup = $intIdCup;
    }

    public function setCodCups($strCodCups) {
        $this->strCodCups = $strCodCups;
    }

    public function setCupsName($strCupsName) {
        $this->strCupsName = $strCupsName;
    }

    public function setRegisteredUser($strRegisteredUser) {
        $this->strRegisteredUser = $strRegisteredUser;
    }

    public function setFechaRegistro($dtFechaRegistro) {
        $this->dtFechaRegistro = $dtFechaRegistro;
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
        if ($this->intIdCup) /* Modifica */ {
            try {

                $strRegisteredUser = $_SESSION['user'];
                $dtFechaRegistro = date("Ymd");
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_salud.spUpdateCups(?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdCup, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->strCodCups, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strCupsName, PDO::PARAM_STR);
                $consulta->bindParam(4, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        } else /* Inserta */ {
            try {
                //            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' (strCodidPais, strNombreidPais) VALUES(:strCodidPais, :strNombreidPais)');
                //            $consulta->bindParam(':strCodidPais', $this->strCodidPais);
                //            $consulta->bindParam(':strNombreidPais', $this->strNombreidPais);

                $strRegisteredUser = $_SESSION['user'];
                $IstatusId = '1';
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_salud.spInsertCups(?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->strCodCups, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strCupsName, PDO::PARAM_STR);
                $consulta->bindParam(3, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(4, $IstatusId, PDO::PARAM_INT);
                $consulta->bindParam(5, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(6, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->intIdCup = $conexion->lastInsertId();

                //Llama a la función auditoria
                //$procedimiento = $consulta;
                /* $procedimiento = 'select * from adminstracion.spInsertDepartment('. $this->strCodCups. $this->strCupsName . $strRegisteredUser . $dtFechaRegistro . ')';
                  Cups::guardarAuditoria($procedimiento); */
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function delete($intIdCup) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
//        $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' WHERE intIdPais = :intIdPais');
//        $consulta->bindParam(':intIdPais', $this->intIdPais);

        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_salud.spDeletedCups(?,?);');
        $consulta->bindParam(1, $this->intIdCup, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function searchById($intIdCup) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        //$consulta = $conexion->prepare('SELECT strCodidPais, strNombreidPais FROM ' . self::TABLA . ' WHERE intIdPais = :intIdPais');
        //$consulta->bindParam(':intIdPais', $intIdPais);

        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedCupstxId(?);');
        $consulta->bindParam(1, $intIdCup, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strcodcups'], $registro['strcupsname'], $intIdCup);
        } else {
            return false;
        }
    }

    public static function getAllCups() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        //$consulta = $conexion->prepare('SELECT intIdPais, strCodidPais, strNombreidPais FROM ' . self::TABLA . ' ORDER BY strCodidPais');
        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedDCups();');
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
//            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' (strCodidPais, strNombreidPais) VALUES(:strCodidPais, :strNombreidPais)');
//            $consulta->bindParam(':strCodidPais', $this->strCodidPais);
//            $consulta->bindParam(':strNombreidPais', $this->strNombreidPais);

            $strUsuarioId = $_SESSION['userId'];
            $strProcedimiento = $vStrProcedimiento; //'procedimiento';
            $strRegisteredUser = $_SESSION['user'];
            $dtFechaRegistro = date("Ymd");

            $consulta = $conexion->prepare('select * from analiizo_salud.spInsertAudit(?,?,?,?);');
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
