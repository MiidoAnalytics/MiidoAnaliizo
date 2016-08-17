<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class Proyectos {

    private $intidproyecto;
    private $nombre;
    private $intidregistereduser;
    private $intistatus;
    private $dtCreateDate;
    private $dtModifiedDate;
    private $intidcliente;

    const TABLA = 'Proyectos';

    public function __construct($intidproyecto = null, $nombre = null, $intidregistereduser = null, $intistatus = null, $dtCreateDate = null, $dtModifiedDate = null, $intidcliente = null) {

        $this->intidproyecto = $intidproyecto;
        $this->nombre = $nombre;
        $this->intidregistereduser = $intidregistereduser;
        $this->intistatus = $intistatus;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
        $this->intidcliente = $intidcliente;
    }

    public function getintidproyecto() {
        return $this->intidproyecto;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getRegisteredUser() {
        return $this->$intidregistereduser;
    }

    public function getintistatus() {
        return $this->$intistatus;
    }

    public function getCreateDate() {
        return $this->$dtCreateDate;
    }

    public function getModifiedDate() {
        return $this->$dtModifiedDate;
    }

    public function getIntidcliente() {
        return $this->$intidcliente;
    }

    public function setintidproyecto($intidproyecto) {
        $this->intidproyecto = $intidproyecto;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setRegisteredUser($intidregistereduser) {
        $this->intidregistereduser = $intidregistereduser;
    }

    public function setintistatus($intistatus) {
        $this->intistatus = $intistatus;
    }

    public function setCreateDate($dtCreateDate) {
        $this->dtCreateDate = $dtCreateDate;
    }

    public function setModifiedDate($dtModifiedDate) {
        $this->dtModifiedDate = $dtModifiedDate;
    }

    public function SetIntidcliente($intidcliente)
    {
        $this->intidcliente = $intidcliente;
    }

    public function insert() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8"); 
        $this->intidproyecto;
        if ($this->intidproyecto)/* Modifica */ {
            try {

                $intidregistereduser = $_SESSION['userid'];
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_salud.spUpdateProyectos(?,?,?,?);');
                $consulta->bindParam(1, $this->intidproyecto, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->nombre, PDO::PARAM_STR);
                $consulta->bindParam(3, $intidregistereduser, PDO::PARAM_INT);
                $consulta->bindParam(4, $dtModifiedDate, PDO::PARAM_STR);

                $consulta->execute();
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        } else /* Inserta */ {
            try {
                $intidregistereduser = $_SESSION['userid'];
                $intistatus = '1';
                $dtCreateDate = date("Ymd");
                $dtModifiedDate = date("Ymd");
                $intidcliente = '1';

                $consulta = $conexion->prepare('select * from analiizo_salud.spinsertproyectos(?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->nombre, PDO::PARAM_STR);
                $consulta->bindParam(2, $intidregistereduser, PDO::PARAM_STR);
                $consulta->bindParam(3, $intistatus, PDO::PARAM_INT);
                $consulta->bindParam(4, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(5, $dtModifiedDate, PDO::PARAM_STR);
                $consulta->bindParam(6, $intidcliente, PDO::PARAM_INT);

                $consulta->execute();
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public function deleterelProyecto($intidproyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_salud.spDeletedProyecto(?,?);');
        $consulta->bindParam(1, $intidproyecto, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public function deleterelProyectoUser($intidproyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_salud.spDeletedProyectoUser(?,?);');
        $consulta->bindParam(1, $intidproyecto, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function searchById($intidproyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $consulta = $conexion->prepare('select * from analiizo_salud.spSelectedproyectosxId(?);');
        $consulta->bindParam(1, $intidproyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($intidproyecto, $registro['nombre'], $registro['intidregistereduser'],
                $registro[' intidcliente']);
        } else {
            return false;
        }
    }

    public static function getAllProyectos() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_salud.spselectedproyectos2();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

}
