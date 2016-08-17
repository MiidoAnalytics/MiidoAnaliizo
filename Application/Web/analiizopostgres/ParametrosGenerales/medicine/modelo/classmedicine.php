<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

//session_start();

class Medicine {

    private $intIdMedicines;
    private $strCodMedicine;
    private $strMedicineName;
    private $intClassification;
    private $intIdComplement;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'medicine';

    public function __construct($strCodMedicine = null, $strMedicineName = null, $intIdMedicines = null, $intClassification = null, $intIdComplement = null, $strRegisteredUser = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {
        $this->intIdMedicines = $intIdMedicines;
        $this->strCodMedicine = $strCodMedicine;
        $this->strMedicineName = $strMedicineName;
        $this->intClassification = $intClassification;
        $this->intIdComplement = $intIdComplement;
        $this->strRegisteredUser = $strRegisteredUser;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getIdMedicine() {
        return $this->intIdMedicines;
    }

    public function getCodMedicine() {
        return $this->strCodMedicine;
    }

    public function getNombreMedicine() {
        return $this->strMedicineName;
    }

    public function getClassification() {
        return $this->$intClassification;
    }

    public function getIdComplement() {
        return $this->$intIdComplement;
    }

    public function getRegisteredUser() {
        return $this->$strRegisteredUser;
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

    public function setIdMedicine($intIdMedicines) {
        $this->intIdMedicines = $intIdMedicines;
    }

    public function setCodMedicine($strCodMedicine) {
        $this->strCodMedicine = $strCodMedicine;
    }

    public function setNombreMedicine($strMedicineName) {
        $this->strMedicineName = $strMedicineName;
    }

    public function setClassification($intClassification) {
        $this->intClassification = $intClassification;
    }

    public function setIdComplement($intIdComplement) {
        $this->intIdComplement = $intIdComplement;
    }

    public function setRegisteredUser($strRegisteredUser) {
        $this->strRegisteredUser = $strRegisteredUser;
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
        if ($this->intIdMedicines) /* Modifica */ {
            try {
                $strRegisteredUser = $_SESSION['user'];
                $IstatusId = '1';
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");
                $intClassification = null;
                $intIdComplement = null;

                $consulta = $conexion->prepare('select * from administracion.spUpdateMedicine(?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdMedicines, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->strCodMedicine, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strMedicineName, PDO::PARAM_STR);
                $consulta->bindParam(4, $intClassification, PDO::PARAM_INT);
                $consulta->bindParam(5, $intIdComplement, PDO::PARAM_INT);
                $consulta->bindParam(6, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(7, $dtModifiedDate, PDO::PARAM_STR);

                //$consulta->bindParam(':intIdMedicines', $this->intIdMedicines);
                $consulta->execute();
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        } else /* Inserta */ {
            try {
                //            $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . ' (strCodMedicine, strMedicineName) VALUES(:strCodMedicine, :strMedicineName)');
                //            $consulta->bindParam(':strCodMedicine', $this->strCodMedicine);
                //            $consulta->bindParam(':strMedicineName', $this->strMedicineName);
                $strRegisteredUser = $_SESSION['user'];
                $IstatusId = '1';
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");
                $intClassification = null;
                $intIdComplement = null;

                $consulta = $conexion->prepare('select * from administracion.spInsertMedicine(?,?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->strCodMedicine, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strMedicineName, PDO::PARAM_STR);
                $consulta->bindParam(3, $intClassification, PDO::PARAM_INT);
                $consulta->bindParam(4, $intIdComplement, PDO::PARAM_INT);
                $consulta->bindParam(5, $strRegisteredUser, PDO::PARAM_INT);
                $consulta->bindParam(6, $IstatusId, PDO::PARAM_STR);
                $consulta->bindParam(7, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(8, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->intIdMedicines = $conexion->lastInsertId();

                //Llama a la función auditoria
                //$procedimiento = $consulta;
                /* $procedimiento = 'select * from administracion.spInsertMedicine('. $this->strCodMedicine . $this->strMedicineName . $intClassification . $dtCreateDate . ')';
                  Medicine::guardarAuditoria($procedimiento); */
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function delete($intIdMedicines) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
//        $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' WHERE intIdMedicines = :intIdMedicines');
//        $consulta->bindParam(':intIdMedicines', $this->intIdMedicines);

        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from administracion.spDeletedMedicine(?,?);');
        $consulta->bindParam(1, $this->intIdMedicines, PDO::PARAM_STR);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function searchById($intIdMedicines) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        //$consulta = $conexion->prepare('SELECT strCodMedicine, strMedicineName FROM ' . self::TABLA . ' WHERE intIdMedicines = :intIdMedicines');
        //$consulta->bindParam(':intIdMedicines', $intIdMedicines);

        $consulta = $conexion->prepare('select * from administracion.spSelectedMedicinexId(?);');
        $consulta->bindParam(1, $intIdMedicines, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($registro['strcodmedicine'], $registro['strmedicinename'], $intIdMedicines);
        } else {
            return false;
        }
    }

    public static function recuperarTodos() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $consulta = $conexion->prepare('select * from administracion.spSelectedMedicine();');
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
            $consulta->bindParam(':strMedicineName', $this->strMedicineName);

            $strUsuarioId = $_SESSION['userId'];
            $strProcedimiento = $vStrProcedimiento; //'procedimiento';
            $intClassification = $_SESSION['user'];
            $dtCreateDate = date("Ymd");

            $consulta = $conexion->prepare('select * from administracion.spInsertAudit(?,?,?,?);');
            $consulta->bindParam(1, $strUsuarioId, PDO::PARAM_STR);
            $consulta->bindParam(2, $strProcedimiento, PDO::PARAM_STR);
            $consulta->bindParam(3, $intClassification, PDO::PARAM_STR);
            $consulta->bindParam(4, $dtCreateDate, PDO::PARAM_STR);

            $consulta->execute();
            $this->intIdMedicines = $conexion->lastInsertId();
        }
        $conexion = null;
    }

}
