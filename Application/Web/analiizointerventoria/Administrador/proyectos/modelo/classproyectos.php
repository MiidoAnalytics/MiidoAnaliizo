<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class Proyectos {

    private $intidproyecto;
    private $nombre;
    private $cateproject;
    private $supervisor;
    private $plazoIni; 
    private $fechaIni;
    private $fechaSus;
    private $fechaReini;
    private $fechaFin;
    private $strCodTown;
    private $barrioVereda;
    private $numContInter;
    private $nominter;
    private $valorIniInter;
    private $valorAdiInter;
    private $numContratista;
    private $nomContratista;
    private $valorIniObra;
    private $valorAdiObra;
    private $intidregistereduser;
    private $intistatus;
    private $dtCreateDate;
    private $dtModifiedDate;
    private $intidcliente;
    private $plazoEtaPre;
    private $plazoEtaCon;
    private $fechaIniCon;

    const TABLA = 'Proyectos';

    public function __construct($intidproyecto = null, $nombre = null, $cateproject = null, $supervisor = null, $plazoIni = null, $fechaIni = null, $fechaSus = null,
                                $fechaReini = null, $fechaFin = null, $strCodTown = null, $barrioVereda = null, $numContInter = null, $nominter = null, $valorIniInter = null, $valorAdiInter = null,
                                $numContratista = null, $nomContratista = null, $valorIniObra = null, $valorAdiObra = null,
                                $intidregistereduser = null, $intidcliente = null, $plazoEtaPre = null, 
                                $plazoEtaCon = null, $fechaIniCon = null ,$intistatus = null, $dtCreateDate = null, $dtModifiedDate = null) {

        $this->intidproyecto = $intidproyecto;
        $this->nombre = $nombre;
        $this->cateproject = $cateproject;
        $this->supervisor = $supervisor;
        $this->plazoIni = $plazoIni;
        $this->fechaIni = $fechaIni;
        $this->fechaSus = $fechaSus;
        $this->fechaReini = $fechaReini;
        $this->fechaFin = $fechaFin;
        $this->strCodTown = $strCodTown;
        $this->barrioVereda = $barrioVereda;
        $this->numContInter = $numContInter;
        $this->nominter = $nominter;
        $this->valorIniInter = $valorIniInter;
        $this->valorAdiInter = $valorAdiInter;
        $this->numContratista = $numContratista;
        $this->nomContratista = $nomContratista;
        $this->valorIniObra = $valorIniObra;
        $this->valorAdiObra = $valorAdiObra;
        $this->intidregistereduser = $intidregistereduser;
        $this->intistatus = $intistatus;
        $this->dtCreateDate = $dtCreateDate;
        $this->dtModifiedDate = $dtModifiedDate;
        $this->intidcliente = $intidcliente;
        $this->plazoEtaPre = $plazoEtaPre;
        $this->plazoEtaCon = $plazoEtaCon;
        $this->fechaIniCon = $fechaIniCon;
    }

    public function getintidproyecto() {
        return $this->intidproyecto;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getCategoria() {
        return $this->cateproject;
    }

    public function getSupervisor() {
        return $this->supervisor;
    }

    public function getPlazoInicial() {
        return $this->plazoIni;
    }

    public function getFechaInicial() {
        return $this->fechaIni;
    }

    public function getFechaSuspension() {
        return $this->fechaSus;
    }

    public function getFechaReinicio() {
        return $this->fechaReini;
    }

    public function getFechaFin() {
        return $this->fechaFin;
    }

    public function getcodTown() {
        return $this->strCodTown;
    }

    public function getBarrioVereda() {
        return $this->barrioVereda;
    }

    public function getNumContraInter() {
        return $this->numContInter;
    }

    public function getInterName() {
        return $this->nominter;
    }

    public function getValorIniInter() {
        return $this->valorIniInter;
    }

    public function getValorAdiInter() {
        return $this->valorAdiInter;
    }

    public function getNumContraObra() {
        return $this->numContratista;
    }

    public function getObraName() {
        return $this->nomContratista;
    }

    public function getValorIniObra() {
        return $this->valorIniObra;
    }

    public function getValorAdiObra() {
        return $this->valorAdiObra;
    }

    public function getRegisteredUser() {
        return $this->intidregistereduser;
    }

    public function getintistatus() {
        return $this->intistatus;
    }

    public function getCreateDate() {
        return $this->dtCreateDate;
    }

    public function getModifiedDate() {
        return $this->dtModifiedDate;
    }

    public function getIntidcliente() {
        return $this->intidcliente;
    }

    public function getplazoEtapaPre() {
        return $this->plazoEtaPre;
    }

    public function getplazoEtapaCons() {
        return $this->plazoEtaCon;
    }

    public function getdateIniCon() {
        return $this->fechaIniCon;
    }

    public function setintidproyecto($intidproyecto) {
        $this->intidproyecto = $intidproyecto;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setCategoria($cateproject) {
        $this->cateproject = $cateproject ;
    }

    public function setSupervisor($supervisor) {
        $this->supervisor = $supervisor;
    }

    public function setPlazoInicial($plazoIni) {
        $this->plazoIni = $plazoIni ;
    }

    public function setFechaInicial($fechaIni) {
        $this->fechaIni = $fechaIni ;
    }

    public function setFechaSuspension($fechaSus) {
        $this->fechaSus = $fechaSus ;
    }

    public function setFechaReinicio($fechaReini) {
        $this->fechaReini = $fechaReini;
    }

    public function setFechaFin($fechaFin) {
        $this->fechaFin = $fechaFin ;
    }

    public function setcodTown($strCodTown) {
        $this->strCodTown = $strCodTown ;
    }

    public function setBarrioVereda($barrioVereda) {
        $this->barrioVereda = $barrioVereda;
    }

    public function setNumContraInter($numContInter) {
        $this->numContInter = $numContInter ;
    }

    public function setInterName($nominter) {
        $this->nominter = $nominter ;
    }

    public function setValorIniInter($valorIniInter) {
        $this->valorIniInter = $valorIniInter;
    }

    public function setValorAdiInter($valorAdiInter) {
        $this->valorAdiInter = $valorAdiInter ;
    }

    public function setNumContraObra($numContratista) {
        $this->numContratista = $numContratista ;
    }

    public function setObraName($nomContratista) {
        $this->nomContratista = $nomContratista ;
    }

    public function setValorIniObra($valorIniObra) {
        $this->valorIniObra = $valorIniObra ;
    }

    public function setValorAdiObra($valorAdiObra) {
        $this->valorAdiObra = $valorAdiObra;
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

     public function SetPlazoEtaPre($plazoEtaPre)
    {
        $this->plazoEtaPre = $plazoEtaPre;
    }

    public function SetPlazoEtaCons($plazoEtaCon)
    {
        $this->plazoEtaCon = $plazoEtaCon;
    }

    public function SetaDtFechaIniCon($fechaIniCon)
    {
        $this->fechaIniCon = $fechaIniCon;
    }

    public function insert() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8"); 

        if ($this->intidproyecto)/* Modifica */ {
            try {

                $intidregistereduser = $_SESSION['userid'];
                $dtModifiedDate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo_interventoria.spUpdateProyectos(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->intidproyecto, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->nombre, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->supervisor, PDO::PARAM_STR);
                $consulta->bindParam(4, $this->strCodTown, PDO::PARAM_STR);
                $consulta->bindParam(5, $this->barrioVereda, PDO::PARAM_STR);
                $consulta->bindParam(6, $this->plazoIni, PDO::PARAM_INT);
                $consulta->bindParam(7, $this->fechaIni, PDO::PARAM_STR);
                $consulta->bindParam(8, $this->fechaSus, PDO::PARAM_STR);
                $consulta->bindParam(9, $this->fechaReini, PDO::PARAM_STR);
                $consulta->bindParam(10, $this->fechaFin, PDO::PARAM_STR);
                $consulta->bindParam(11, $this->numContInter, PDO::PARAM_STR);
                $consulta->bindParam(12, $this->nominter, PDO::PARAM_STR);
                $consulta->bindParam(13, $this->valorIniInter, PDO::PARAM_INT);
                $consulta->bindParam(14, $this->valorAdiInter, PDO::PARAM_INT);
                $consulta->bindParam(15, $this->numContratista, PDO::PARAM_STR);
                $consulta->bindParam(16, $this->nomContratista, PDO::PARAM_STR);
                $consulta->bindParam(17, $this->valorIniObra, PDO::PARAM_INT);
                $consulta->bindParam(18, $this->valorAdiObra, PDO::PARAM_INT);
                $consulta->bindParam(19, $intidregistereduser, PDO::PARAM_STR);
                $consulta->bindParam(20, $dtModifiedDate, PDO::PARAM_STR);
                $consulta->bindParam(21, $this->cateproject, PDO::PARAM_STR);
                $consulta->bindParam(22, $this->plazoEtaPre, PDO::PARAM_INT);
                $consulta->bindParam(23, $this->plazoEtaCon, PDO::PARAM_INT);
                $consulta->bindParam(24, $this->fechaIniCon, PDO::PARAM_INT);

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
                $intidcliente = '2';

                $consulta = $conexion->prepare('select * from analiizo_interventoria.insertproyectossp(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->nombre, PDO::PARAM_STR);
                $consulta->bindParam(2, $this->supervisor, PDO::PARAM_STR);
                $consulta->bindParam(3, $this->strCodTown, PDO::PARAM_STR);
                $consulta->bindParam(4, $this->barrioVereda, PDO::PARAM_STR);
                $consulta->bindParam(5, $this->plazoIni, PDO::PARAM_INT);
                $consulta->bindParam(6, $this->fechaIni, PDO::PARAM_STR);
                $consulta->bindParam(7, $this->fechaSus, PDO::PARAM_STR);
                $consulta->bindParam(8, $this->fechaReini, PDO::PARAM_STR);
                $consulta->bindParam(9, $this->fechaFin, PDO::PARAM_STR);
                $consulta->bindParam(10, $this->numContInter, PDO::PARAM_STR);
                $consulta->bindParam(11, $this->nominter, PDO::PARAM_STR);
                $consulta->bindParam(12, $this->valorIniInter, PDO::PARAM_INT);
                $consulta->bindParam(13, $this->valorAdiInter, PDO::PARAM_INT);
                $consulta->bindParam(14, $this->numContratista, PDO::PARAM_STR);
                $consulta->bindParam(15, $this->nomContratista, PDO::PARAM_STR);
                $consulta->bindParam(16, $this->valorIniObra, PDO::PARAM_INT);
                $consulta->bindParam(17, $this->valorAdiObra, PDO::PARAM_INT);
                $consulta->bindParam(18, $intidregistereduser, PDO::PARAM_STR);
                $consulta->bindParam(19, $intistatus, PDO::PARAM_INT);
                $consulta->bindParam(20, $dtCreateDate, PDO::PARAM_STR);
                $consulta->bindParam(21, $dtModifiedDate, PDO::PARAM_STR);
                $consulta->bindParam(22, $this->cateproject, PDO::PARAM_STR);
                $consulta->bindParam(23, $intidcliente, PDO::PARAM_INT);
                $consulta->bindParam(24, $plazoEtaPre, PDO::PARAM_INT);
                $consulta->bindParam(25, $plazoEtaCon, PDO::PARAM_INT);
                $consulta->bindParam(26, $fechaIniCon, PDO::PARAM_INT);

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

        $consulta = $conexion->prepare('select * from analiizo_interventoria.spDeletedProyecto(?,?);');
        $consulta->bindParam(1, $intidproyecto, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public function deleterelProyectoUser($intidproyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $dtModifiedDate = date("Ymd");

        $consulta = $conexion->prepare('select * from analiizo_interventoria.spDeletedProyectoUser(?,?);');
        $consulta->bindParam(1, $intidproyecto, PDO::PARAM_INT);
        $consulta->bindParam(2, $dtModifiedDate, PDO::PARAM_STR);
        $consulta->execute();
        $conexion = null;
    }

    public static function searchById($intidproyecto) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $consulta = $conexion->prepare('select * from analiizo_interventoria.spSelectedproyectosxId(?);');
        $consulta->bindParam(1, $intidproyecto, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        if ($registro) {
            return new self($intidproyecto, $registro['nombre'], $registro['intidcategoria'], $registro['supervisor'], $registro['plazoini'], $registro['fechaini'], $registro['fechasus'], 
                $registro['fechareini'], $registro['fechafin'], $registro['codmunicipio'], $registro['barrio'], $registro['numconint'], $registro['nombreint'], $registro['valiniint'], 
                $registro['valadiint'], $registro['numcontobra'], $registro['nombreobra'], $registro['valiniobra'], $registro['valadobra'], $registro['intidregistereduser'], $registro['intidcliente']
                , $registro['plazoprecon'], $registro['plazocons'], $registro['dtfechainicon']);
        } else {
            return false;
        }
    }

    public static function getAllProyectos() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.spselectedproyectos2();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /*Obtiene las categorias para llenar el select*/
    public static function getAllCategories() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getallcategories();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /*Obtiene las categorias para llenar el select*/
    public static function getCategoriesxId($cateproject) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getcategoriesbyid(?);');
        $consulta->bindParam(1, $cateproject, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    /*Obtiene las departamento para llenar el select*/
    public static function getDepMunbycod($codmunicipio) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo_interventoria.getdepmunbycodsp(?);');
        $consulta->bindParam(1, $codmunicipio, PDO::PARAM_INT);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

}
