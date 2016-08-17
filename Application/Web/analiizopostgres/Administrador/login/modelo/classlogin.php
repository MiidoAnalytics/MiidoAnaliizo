<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class Login {

    private $strUser;
    private $strPassword;

    const TABLA = 'login';

    public function __construct($strUser = null, $strPassword = null) {
        $this->strUser = $strUser;
        $this->strPassword = $strPassword;
    }

//    public function getCodidMunicipio() {
//        return $this->strCodidPais;
//    }
    //****************************************************************************
    //Recupera la información por el usuario y la contraseña
    //****************************************************************************

    public static function recuperarUsuarioPassword($strUser, $strPassword) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spValidaUsuarioPassword(?,?);');
        $consulta->bindParam(1, $strUser, PDO::PARAM_STR);
        $consulta->bindParam(2, $strPassword, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        // print_r($registros);
        // die();
        $conexion = null;
        return $registros;
    }

    public static function ActualizarHoraIngresoUsuario($strUser, $strPassword) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spActualizaHoraIngresoUsuario(?,?);');
        $consulta->bindParam(1, $strUser, PDO::PARAM_STR);
        $consulta->bindParam(2, $strPassword, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function ActualizarPasswordUsuario($strUser, $strPasswordOld, $strPasswordNew) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spActualizaPasswordUsuario(?,?,?);');
        $consulta->bindParam(1, $strUser, PDO::PARAM_STR);
        $consulta->bindParam(2, $strPasswordOld, PDO::PARAM_STR);
        $consulta->bindParam(3, $strPasswordNew, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Funcion que busca el menu de inicio para cada rol padre
    //****************************************************************************
    public static function obtenerMenuIni($role_id) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from administracion.spPrimerMenuIngreso(?);');
        $consulta->bindParam(1, $role_id, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

}
