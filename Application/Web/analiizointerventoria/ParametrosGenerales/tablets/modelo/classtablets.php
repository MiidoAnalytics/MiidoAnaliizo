<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

//session_start();

class Tablet {

    private $intIdTablet;
    private $strNomtablets;
    private $strKeytablets;
    private $intFree;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'tablets';

    public function __construct($strNomtablets = null, $strKeytablets = null, $intIdTablet = null, $intFree = null, $strRegisteredUser = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {
        $this->intIdTablet = $intIdTablet;
        $this->strNomtablets = $strNomtablets;
        $this->strKeytablets = $strKeytablets;
        $this->intFree = $intFree;
        $this->strRegisteredUser = $strRegisteredUser;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getIdTablet() {
        return $this->intIdTablet;
    }

    public function getNomtablets() {
        return $this->strNomtablets;
    }

    public function getKeytablets() {
        return $this->strKeytablets;
    }

    public function getFree() {
        return $this->$intFree;
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

    public function setIdTablet($intIdTablet) {
        $this->intIdTablet = $intIdTablet;
    }

    public function setNomtablets($strNomtablets) {
        $this->strNomtablets = $strNomtablets;
    }

    public function setsKeytablets($strKeytablets) {
        $this->strKeytablets = $strKeytablets;
    }

    public function setFree($intFree) {
        $this->$intFree = $intFree;
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
        if ($this->intIdTablet) /* Modifica */ {
            try {
                //$consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET strCodidPais = :strCodidPais, strNombreidPais = :strNombreidPais WHERE intIdPais = :intIdPais');
                //$consulta->bindParam(':strCodidPais', $this->strCodidPais);
                //$consulta->bindParam(':strNombreidPais', $this->strNombreidPais);

                $strRegisteredUser = $_SESSION['user'];
                $dtFechaRegistro = date("Ymd");
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_interventoria.spUpdateTablets(?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdTablet, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->strNomtablets, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strKeytablets, PDO::PARAM_STR);
                $consulta->bindParam(4, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        } else /* Inserta */ {
            try {

                $intFree = '1';
                $strRegisteredUser = $_SESSION['user'];
                $IstatusId = '1';
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_interventoria.spInsertTablets(?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->strNomtablets, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strKeytablets, PDO::PARAM_STR);
                $consulta->bindParam(3, $intFree, PDO::PARAM_INT);
                $consulta->bindParam(4, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(5, $IstatusId, PDO::PARAM_INT);
                $consulta->bindParam(6, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(7, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->intIdTablet = $conexion->lastInsertId();

                //Llama a la función auditoria
                //$procedimiento = $consulta;
                /* $procedimiento = 'select * from analiizo_interventoria.spInsertDepartment('. $this->strNomtablets. $this->strKeytablets . $strRegisteredUser . $dtFechaRegistro . ')';
                  Tablet::guardarAuditoria($procedimiento); */
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function delete($intIdTablet) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_interventoria.spDeletedTablets(?,?);');
        $consulta->bindParam(1, $this->intIdTablet, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

     public function delelteRelTabletsInter($intIdTablet) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.delelteRelTabletsInter(?,?);');
        $consulta->bindParam(1, $this->intIdTablet, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function searchById($intIdTablet) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        //$consulta = $conexion->prepare('SELECT strCodidPais, strNombreidPais FROM ' . self::TABLA . ' WHERE intIdPais = :intIdPais');
        //$consulta->bindParam(':intIdPais', $intIdPais);

        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedTabletxId(?);');
        $consulta->bindParam(1, $intIdTablet, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strtabletname'], $registro['strtabletkey'], $intIdTablet);
        } else {
            return false;
        }
    }

    public static function getAllTablets() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        //$consulta = $conexion->prepare('SELECT intIdPais, strCodidPais, strNombreidPais FROM ' . self::TABLA . ' ORDER BY strCodidPais');
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedTablets();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function getAllTabletsFree() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedTabletsFree();');
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

            $consulta = $conexion->prepare('select * from analiizo_interventoria.spInsertAudit(?,?,?,?);');
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
