<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

@session_start();

class Report {

    private $intIdReports;
    private $strreportname;
    private $strdescription;
    private $strquery;
    private $strregistereduser;
    private $istatusid;
    private $dtcreatedate;
    private $dtmodifieddate;
    private $since;
    private $until;
    private $town;
    private $interviewer;
    private $disease;
    private $medicine;
    private $etnia;
    private $desplazado;
    private $estudios;
    private $minusvalido;
    private $odontologia;
    private $vivienda, $agua, $alcantarillado, $gasNatural, $electricidad;

    const TABLA = 'reportes';

    public function __construct($strreportname = null, $strdescription = null, $strquery = null, $intIdReports = null, $interviewer = null, $disease = null, $medicine = null, $strregistereduser = null, $istatusid = null, $dtcreatedate = null, $dtmodifieddate = null, $since = null, $until = null, $town = null, $etnia = null, $desplazado = null, $estudios = null, $minusvalido = null, $odontologia = null, $vivienda = null, $agua = null, $alcantarillado = null, $gasNatural = null, $electricidad = null, $intIdDiv = null) {


        $this->intIdReports = $intIdReports;
        $this->strreportname = $strreportname;
        $this->strdescription = $strdescription;
        $this->strquery = $strquery;
        $this->strregistereduser = $strregistereduser;
        $this->istatusid = $istatusid;
        $this->dtcreatedate = $dtcreatedate;
        $this->dtmodifieddate = $dtmodifieddate;
        $this->until = $until;
        $this->since = $since;
        $this->town = $town;
        $this->interviewer = $interviewer;
        $this->disease = $disease;
        $this->medicine = $medicine;
        $this->etnia = $etnia;
        $this->desplazado = $desplazado;
        $this->estudios = $estudios;
        $this->minusvalido = $minusvalido;
        $this->odontologia = $odontologia;
        $this->vivienda = $vivienda;
        $this->agua = $agua;
        $this->alcantarillado = $alcantarillado;
        $this->gasNatural = $gasNatural;
        $this->electricidad = $electricidad;
        $this->intIdDiv = $intIdDiv;
    }

    public function getIdReport() {
        return $this->intIdReports;
    }

    public function getReportName() {
        return $this->strreportname;
    }

    public function getDescription() {
        return $this->strdescription;
    }

    public function getQuery() {
        return $this->strquery;
    }

    public function getRegisteredUser() {
        return $this->$strregistereduser;
    }

    public function getistatusid() {
        return $this->$istatusid;
    }

    public function getCreateDate() {
        return $this->$dtcreatedate;
    }

    public function getModifiedDate() {
        return $this->$dtmodifieddate;
    }

    public function getDateSince() {
        return $this->$since;
    }

    public function getDateUntil() {
        return $this->$until;
    }

    public function getTown() {
        return $this->$town;
    }

    public function setIdReport($intIdReports) {
        $this->intIdReports = $intIdReports;
    }

    public function setReportName($strreportname) {
        $this->strreportname = $strreportname;
    }

    public function setDescription($strdescription) {
        $this->strdescription = $strdescription;
    }

    public function setQuery($strquery) {
        $this->strquery = $strquery;
    }

    public function setRegisteredUser($strregistereduser) {
        $this->strregistereduser = $strregistereduser;
    }

    public function setistatusid($istatusid) {
        $this->istatusid = $istatusid;
    }

    public function setCreateDate($dtcreatedate) {
        $this->dtcreatedate = $dtcreatedate;
    }

    public function setModifiedDate($dtmodifieddate) {
        $this->dtmodifieddate = $dtmodifieddate;
    }

    public function setUntilDate($until) {
        $this->until = $until;
    }

    public function setSinceDate($since) {
        $this->since = $since;
    }

    public function setTownDate($town) {
        $this->town = $town;
    }

    public function insert() {
        //require_once 'classConexion.php';
        $conexion = new ConexionAnaliizoPostgres();
        //$conexion->exec("set names utf8");

        if ($this->intIdReports) /* Modifica */ {
            try {

                $strregistereduser = $_SESSION['user'];
                $dtmodifieddate = date("Ymd");
                $dtcreatedate = date("Ymd");

                $consulta = $conexion->prepare('select * from analiizo.spUpdateReport(?,?,?,?,?,?);');
                $consulta->bindParam(1, $this->intIdReports, PDO::PARAM_INT);
                $consulta->bindParam(2, $this->strreportname, PDO::PARAM_INT);
                $consulta->bindParam(3, $this->strdescription, PDO::PARAM_STR);
                $consulta->bindParam(4, $this->strquery, PDO::PARAM_STR);
                $consulta->bindParam(5, $strregistereduser, PDO::PARAM_STR);
                $consulta->bindParam(6, $dtmodifieddate, PDO::PARAM_STR);

                $consulta->execute();
            } catch (Exception $ex) {
                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        } else /* Inserta */ {
            try {

                $strregistereduser = $_SESSION['user'];
                $istatusid = 1;
                $dtcreatedate = date("Ymd");
                $dtmodifieddate = date("Ymd");

                $consulta = $conexion->prepare('select analiizo.insertreport(:strreportname::VARCHAR,:strdescription::VARCHAR,
                                                    :strquery::TEXT,:strregistereduser::VARCHAR,:istatusid::INTEGER,
                                                    :dtcreatedate::TIMESTAMP,:dtmodifieddate::TIMESTAMP);');
                $consulta->bindParam('strreportname', $this->strreportname);
                $consulta->bindParam('strdescription', $this->strdescription);
                $consulta->bindParam('strquery', $this->strquery);
                $consulta->bindParam('strregistereduser', $strregistereduser);
                $consulta->bindParam('istatusid', $istatusid);
                $consulta->bindParam('dtcreatedate', $dtcreatedate);
                $consulta->bindParam('dtmodifieddate', $dtmodifieddate);
                $consulta->execute();
                $this->intIdReports = $conexion->lastInsertId();
            } catch (Exception $ex) {

                echo 'Excepción capturada: ', $ex->getMessage(), "\n";
            }
        }
        $conexion = null;
    }

    public static function searchById($intIdReports) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $consulta = $conexion->prepare('select * from analiizo.spSelectedReportxId(?);');
        $consulta->bindParam(1, $intIdReports, PDO::PARAM_INT);
        $consulta->execute();
        $registro = $consulta->fetch();
        $conexion = null;
        //echo count($registro);
        if ($registro) {
            return new self($registro['strreportname'], $registro['strdescription'], $registro['strquery'], $intIdReports);
        } else {

            return false;
        }
    }

    public static function getAll() {

        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");

        $consulta = $conexion->prepare('select * from analiizo.spSelectedReport();');
        //$consulta->query();
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

   public static function Query($strQuery){
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        
        $consulta = $strQuery;
        $resultado = $conexion->query($consulta);       
        $resultado->execute();
        ini_set('memory_limit', '-1');
        $registros = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registros;
        $resultado->closeCursor();
        $registros = null;
    } 
  

    public static function TitlesCols($strquery) {
        $conexion = new ConexionAnaliizoPostgres();
        $consulta = $strquery;
        $rs = $conexion->query($consulta);
        for ($i = 0; $i < $rs->columnCount(); $i++) {
            $col = $rs->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        return $columns;
    }

    public static function buscarDatosTodosFiltro($since, $until, $town, $ageSince, $ageUntil, $gender, $interviewer, $disease, $medicine, $etnia, $desplazado, $estudios, $minusvalido, $odontologia, $vivienda, $agua, $alcantarillado, $gasNatural, $electricidad) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spbuscardatosreportetodosfiltro(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);');
        $consulta->bindParam(1, $since, PDO::PARAM_STR);
        $consulta->bindParam(2, $until, PDO::PARAM_STR);
        $consulta->bindParam(3, $town, PDO::PARAM_STR);
        $consulta->bindParam(4, $ageSince, PDO::PARAM_STR);
        $consulta->bindParam(5, $ageUntil, PDO::PARAM_STR);
        $consulta->bindParam(6, $gender, PDO::PARAM_STR);
        $consulta->bindParam(7, $interviewer, PDO::PARAM_STR);
        $consulta->bindParam(8, $disease, PDO::PARAM_STR);
        $consulta->bindParam(9, $medicine, PDO::PARAM_STR);
        $consulta->bindParam(10, $etnia, PDO::PARAM_STR);
        $consulta->bindParam(11, $desplazado, PDO::PARAM_STR);
        $consulta->bindParam(12, $estudios, PDO::PARAM_STR);
        $consulta->bindParam(13, $minusvalido, PDO::PARAM_STR);
        $consulta->bindParam(14, $odontologia, PDO::PARAM_STR);
        $consulta->bindParam(15, $vivienda, PDO::PARAM_STR);
        $consulta->bindParam(16, $agua, PDO::PARAM_STR);
        $consulta->bindParam(17, $alcantarillado, PDO::PARAM_STR);
        $consulta->bindParam(18, $gasNatural, PDO::PARAM_STR);
        $consulta->bindParam(19, $electricidad, PDO::PARAM_STR);
        $consulta->execute();
        $registro = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $conexion = null;
        return $registro;
    }

    public static function buscarDatosTodosFiltroTitulos($since, $until, $town, $ageSince, $ageUntil, $gender, $interviewer, $disease, $medicine, $etnia, $desplazado, $estudios, $minusvalido, $odontologia, $vivienda, $agua, $alcantarillado, $gasNatural, $electricidad) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spbuscardatosreportetodosfiltro(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);');
        $consulta->bindParam(1, $since, PDO::PARAM_STR);
        $consulta->bindParam(2, $until, PDO::PARAM_STR);
        $consulta->bindParam(3, $town, PDO::PARAM_STR);
        $consulta->bindParam(4, $ageSince, PDO::PARAM_STR);
        $consulta->bindParam(5, $ageUntil, PDO::PARAM_STR);
        $consulta->bindParam(6, $gender, PDO::PARAM_STR);
        $consulta->bindParam(7, $interviewer, PDO::PARAM_STR);
        $consulta->bindParam(8, $disease, PDO::PARAM_STR);
        $consulta->bindParam(9, $medicine, PDO::PARAM_STR);
        $consulta->bindParam(10, $etnia, PDO::PARAM_STR);
        $consulta->bindParam(11, $desplazado, PDO::PARAM_STR);
        $consulta->bindParam(12, $estudios, PDO::PARAM_STR);
        $consulta->bindParam(13, $minusvalido, PDO::PARAM_STR);
        $consulta->bindParam(14, $odontologia, PDO::PARAM_STR);
        $consulta->bindParam(15, $vivienda, PDO::PARAM_STR);
        $consulta->bindParam(16, $agua, PDO::PARAM_STR);
        $consulta->bindParam(17, $alcantarillado, PDO::PARAM_STR);
        $consulta->bindParam(18, $gasNatural, PDO::PARAM_STR);
        $consulta->bindParam(19, $electricidad, PDO::PARAM_STR);
        $consulta->execute();
        $cuenta_col = $consulta->columnCount();
        if (!$cuenta_col) {
            echo "\nPDO::errorInfo():\n";
            print_r($consulta->errorInfo());
        }
        //$registro = $consulta->fetchAll();
        for ($i = 0; $i < $cuenta_col; $i++) {
            $col = $consulta->getColumnMeta($i);
            $columns[] = $col['name'];
        }
        return $columns;
    }

    //****************************************************************************
    //Recupera la información de las Enfermedades Reportadas
    //****************************************************************************

    public static function recuperarEnfermedadesReportadas() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedEnfermedadReportada();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera la información de los Medicamentos Reportadas
    //****************************************************************************

    public static function recuperarMedicamentosReportados() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedMedicamentoReportado();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera la información de las Razas Reportadas
    //****************************************************************************

    public static function recuperarRazasReportadas() {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedRazaReportada();');
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

    //****************************************************************************
    //Recupera el id de reporste correspondiente al indice que se muestra
    //****************************************************************************

    public static function recuperarIdReporte($intIdDiv) {
        $conexion = new ConexionAnaliizoPostgres();
        $conexion->exec("set names utf8");
        $consulta = $conexion->prepare('select * from analiizo.spSelectedRecuperarIdReporte(?);');
        $consulta->bindParam(1, $intIdDiv, PDO::PARAM_STR);
        $consulta->execute();
        $registros = $consulta->fetchAll();
        $conexion = null;
        return $registros;
    }

}

?>
