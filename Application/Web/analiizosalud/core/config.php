<?php

class Constants {
    //constante duración de tiempo de Sesión
    const TIMEMAXSESSION = 300;      // RIGHT - Works INSIDE of a class definition.
    
    public static function getMinValue() {
        return self::TIMEMAXSESSION;
    }

}

?>