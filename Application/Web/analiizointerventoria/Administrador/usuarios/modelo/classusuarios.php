<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class Usuarios {

    private $idusuarios;
    private $strFirstName;
    private $strLastName;
    private $strPhone;
    private $strEmail;
    private $strLogin;
    private $strPassword;
    private $strRegisteredUser;
    private $IstatusId;
    private $dtCreateDate;
    private $dtModifiedDate;

    const TABLA = 'Usuarios';

    public function __construct($idusuarios = null, $strFirstName = null, $strLastName = null, $strPhone = null, 
        $strEmail = null, $strLogin = null, $strPassword = null, 
        $strRegisteredUser = null, $dtFechaRegistro = null, $IstatusId = null, $dtCreateDate = null, $dtModifiedDate = null) {

        $this->idusuarios = $idusuarios;
        $this->strFirstName = $strFirstName;
        $this->strLastName = $strLastName;
        $this->strPhone = $strPhone;
        $this->strEmail = $strEmail;
        $this->strLogin = $strLogin;
        $this->strPassword = $strPassword;
        $this->strRegisteredUser = $strRegisteredUser;
        $this->IstatusId = $IstatusId;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function getIdUsuarios() {
        return $this->idusuarios;
    }

    public function getFirtsName() {
        return $this->strFirstName;
    }

    public function getLatsName() {
        return $this->strLastName;
    }

    public function getUserPhone() {
        return $this->strPhone;
    }

    public function getUserEmail() {
        return $this->strEmail;
    }

    public function getUserLogin() {
        return $this->strLogin;
    }

    public function getUserPassword() {
        return $this->strPassword;
    }

    public function getLastName() {
        return $this->strLastName;
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

    public function setIdUsuarios($idusuarios) {
        $this->idusuarios = $idusuarios;
    }

    public function setFirstName($strFirstName) {
        $this->strFirstName = $strFirstName;
    }

    public function setLastName($strLastName) {
        $this->strLastName = $strLastName;
    }

    public function setUserPhone($strPhone) {
        $this->strPhone = $strPhone;
    }

    public function setUserEmail($strEmail) {
        $this->strEmail = $strEmail;
    }

    public function setUserLogin($strLogin) {
        $this->strLogin = $strLogin;
    }

    public function setUserPassword($strPassword) {
        $this->strPassword = $strPassword;
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
        if ($this->idusuarios)/* Modifica */ {
            try {

                $strRegisteredUser = $_SESSION['user'];
                $dtFechaRegistro = date("Ymd");
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_interventoria.spUpdateUsuarios(?,?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->idusuarios, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->strFirstName, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strLastName, PDO::PARAM_STR);
                $consulta->bindParam(4, $this->strPhone, PDO::PARAM_STR);
                $consulta->bindParam(5, $this->strEmail, PDO::PARAM_STR);
                $consulta->bindParam(6, $this->strLogin, PDO::PARAM_STR);
                $consulta->bindParam(7, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(8, $dtModifiedDate, PDO::PARAM_STR);

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

                $consulta = $conexion->prepare('select * from analiizo_interventoria.spinsertusuarios(?,?,?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->strFirstName, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->strLastName, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strPhone, PDO::PARAM_STR);
                $consulta->bindParam(4, $this->strEmail, PDO::PARAM_STR);
                $consulta->bindParam(5, $this->strLogin, PDO::PARAM_STR);
                $consulta->bindParam(6, $strRegisteredUser, PDO::PARAM_STR);
                $consulta->bindParam(7, $IstatusId, PDO::PARAM_INT);
                $consulta->bindParam(8, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(9, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
                $this->idusuarios = $conexion->lastInsertId();

                //Llama a la función auditoria
                //$procedimiento = $consulta;
                /* $procedimiento = 'select * from analiizo_interventoria.spInsertDepartment('. $this->strFirstName. $this->strLastName . $strRegisteredUser . $dtFechaRegistro . ')';
                  Cups::guardarAuditoria($procedimiento); */
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function delete($idusuarios) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_interventoria.spDeletedUsuario(?,?);');
        $consulta->bindParam(1, $idusuarios, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public function deleteRelUsuarioProyecto($idusuarios) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_interventoria.spDeletedUsuarioProyecto(?,?);');
        $consulta->bindParam(1, $idusuarios, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public function resetPass($idusuarios) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_interventoria.spResetPassAdm(?,?);');
        $consulta->bindParam(1, $this->idusuarios, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function searchById($idusuarios) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedUsuariosxId(?);');
        $consulta->bindParam(1, $idusuarios, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($idusuarios, $registro['strfirstname'], $registro['strlastname'],
                $registro['strphone'], $registro['stremail'], $registro['strlogin']);
        } else {
            return false;
        }
    }

    public static function getAllUsuarios() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spselectedusuarios();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        //print_r($registros); die();
        $conexion = null;
        return $registros;
    }

}
