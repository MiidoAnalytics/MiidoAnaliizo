<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class Morbilidad {

    private $strCodidMunicipio;

    const TABLA = 'morbilidad';

    public function __construct($strCodidMunicipio = null) {
        $this->strCodidMunicipio = $strCodidMunicipio;
    }

    public function getCodidMunicipio() {
        return $this->strCodidPais;
    }

    //****************************************************************************
    //Recupera la información por código de municipio
    //****************************************************************************

    public static function recuperarMunicipiosEncuestadosxMun($strCodidMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedMunicipiosEncuestadosxMun(?);');
        $consulta->bindParam(1, $strCodidMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarMedicamentosAfiliadosxMun($strCodidMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedMedicamentosAfiliadosxMun(?);');
        $consulta->bindParam(1, $strCodidMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEnfermedadesPersonalesxMun($strCodidMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedEnfermedadesPersonalesxMun(?);');
        $consulta->bindParam(1, $strCodidMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarPrevaAnemiMuj3a10xMun($strCodidMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedPrevaAnemiMuj3a10xMun(?);');
        $consulta->bindParam(1, $strCodidMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarDiabetesMellitus18a69xMun($strCodidMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedDiabetesMellitus18a69xMun(?);');
        $consulta->bindParam(1, $strCodidMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEnfermedadRenalxMun($strCodidMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedEnfermedadRenalxMun(?);');
        $consulta->bindParam(1, $strCodidMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarHipertensionArterial18a69xMun($strCodidMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedHipertensionArterial18a69xMun(?);');
        $consulta->bindParam(1, $strCodidMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarObesidadMuj18a64xMun($strCodidMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedObesidadMuj18a64xMun(?);');
        $consulta->bindParam(1, $strCodidMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarObesidad18a64xMun($strCodidMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedObesidad18a64xMun(?);');
        $consulta->bindParam(1, $strCodidMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarObesidadHom18a64xMun($strCodidMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedObesidadHom18a64xMun(?);');
        $consulta->bindParam(1, $strCodidMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarVIHSidaxMun($strCodidMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedVIHSidaxMun(?);');
        $consulta->bindParam(1, $strCodidMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarVIHPersonas15a49xMun($strCodidMunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedVIHPersonas15a49xMun(?);');
        $consulta->bindParam(1, $strCodidMunicipio, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera la información de todos los municipios
    //****************************************************************************

    public static function recuperarMunicipiosEncuestados() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedMunicipiosEncuestados();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarMedicamentosAfiliados() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedMedicamentosAfiliados();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEnfermedadesPersonales() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedEnfermedadesPersonales();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarPrevaAnemiMuj3a10() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedPrevaAnemiMuj3a10();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarDiabetesMellitus18a69() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedDiabetesMellitus18a69();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarEnfermedadRenal() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedEnfermedadRenal();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarHipertensionArterial18a69() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedHipertensionArterial18a69();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarObesidadMuj18a64() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedObesidadMuj18a64();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarObesidad18a64() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedObesidad18a64();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarObesidadHom18a64() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedObesidadHom18a64();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarVIHSida() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedVIHSida();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    public static function recuperarVIHPersonas15a49() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedVIHPersonas15a49();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

}
