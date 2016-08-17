<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class Occupation {

    private $intIdOccupations;
    private $strCodeOccupation;
    private $strNameOccupation;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'Occupations';

    public function __construct($strCodeOccupation = null, $strNameOccupation = null, $intIdOccupations = null, $strRegisteredUser = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {
        $this->intIdOccupations = $intIdOccupations;
        $this->strCodeOccupation = $strCodeOccupation;
        $this->strNameOccupation = $strNameOccupation;
        $this->strRegisteredUser = $strRegisteredUser;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getIdOccupation() {
        return $this->intIdOccupations;
    }

    public function getCodeOccupation() {
        return $this->strCodeOccupation;
    }

    public function getNameOccupation() {
        return $this->strNameOccupation;
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

    public function setIdOccupation($intIdOccupations) {
        $this->intIdOccupations = $intIdOccupations;
    }

    public function setCodeOccupation($strCodeOccupation) {
        $this->strCodeOccupation = $strCodeOccupation;
    }

    public function setNameOccupation($strNameOccupation) {
        $this->strNameOccupation = $strNameOccupation;
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
        if ($this->intIdOccupations) /* Modifica */ {
            try {
                //$consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET strCodidPais = :strCodidPais, strNombreidPais = :strNombreidPais WHERE intIdPais = :intIdPais');
                //$consulta->bindParam(':strCodidPais', $this->strCodidPais);
                //$consulta->bindParam(':strNombreidPais', $this->strNombreidPais);

                $strRegisteredUser = $_SESSION['user'];
                $dtFechaRegistro = date("Ymd");
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from administracion.spUpdateOccupations(?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdOccupations, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->strCodeOccupation, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strNameOccupation, PDO::PARAM_STR);
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

                $consulta = $conexion->prepare('select * from administracion.spInsertOccupations(?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->strCodeOccupation, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strNameOccupation, PDO::PARAM_STR);
                $consulta->bindParam(3, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(4, $IstatusId, PDO::PARAM_INT);
                $consulta->bindParam(5, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(6, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->intIdOccupations = $conexion->lastInsertId();

                //Llama a la función auditoria
                //$procedimiento = $consulta;
                /* $procedimiento = 'select * from administracion.spInsertDepartment('. $this->strCodeOccupation. $this->strNameOccupation . $strRegisteredUser . $dtFechaRegistro . ')';
                  Occupation::guardarAuditoria($procedimiento); */
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function delete($intIdOccupations) {
       $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
//        $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' WHERE intIdPais = :intIdPais');
//        $consulta->bindParam(':intIdPais', $this->intIdPais);

        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from administracion.spDeletedOccupations(?,?);');
        $consulta->bindParam(1, $this->intIdOccupations, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function searchById($intIdOccupations) {
       $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        //$consulta = $conexion->prepare('SELECT strCodidPais, strNombreidPais FROM ' . self::TABLA . ' WHERE intIdPais = :intIdPais');
        //$consulta->bindParam(':intIdPais', $intIdPais);

        $consulta = $conexion->prepare('select * from administracion.spSelectedOccupationxId(?);');
        $consulta->bindParam(1, $intIdOccupations, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strcodeoccupation'], $registro['stnameoccupation'], $intIdOccupations);
        } else {
            return false;
        }
    }

    public static function getAllOccupations() {
       $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        //$consulta = $conexion->prepare('SELECT intIdPais, strCodidPais, strNombreidPais FROM ' . self::TABLA . ' ORDER BY strCodidPais');
        $consulta = $conexion->prepare('select * from administracion.spSelectedOccupations();');
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

            $consulta = $conexion->prepare('select * from administracion.spInsertAudit(?,?,?,?);');
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
