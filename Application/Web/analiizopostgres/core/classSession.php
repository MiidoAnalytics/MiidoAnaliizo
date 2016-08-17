<?php

if (!defined('CONTROLADOR'))
    exit;

require_once '../../../../conexiones/classconexion.php';

class Session {

    private $bandera;

    const TABLA = 'session';

    public function __construct($bandera = null) {
        $this->bandera = $bandera;
    }

    public function getBandera() {
        return $this->bandera;
    }

    public static function TiempoSession() {
        @session_start();
        if (isset($_SESSION['user'])) {
            $fechaGuardada = $_SESSION["ultimoAcceso"];
            $ahora = date("Y-m-d H:i:s");
            if (empty($_SESSION['user'])) {
                return false;
                //echo 'dos';
            } else {

                    return true;
                    //echo 'uno';
                //}
            }
        } else {
            return false;
            //echo 'uno';
        }
    }
}
?>