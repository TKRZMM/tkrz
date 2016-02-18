<?php

/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:53
 */
class CoreObject extends CoreMySQLi
{

    protected static $obj = null;





    // Klassen eigener Konstruktor
    function __construct()
    {

        parent::__construct();

    }   // END function __construct()





    // Vor Clone-Funktion schützen
    protected function __clone() { }





    // Stellt sicher, dass nur eine Instanz der Klasse erzeugt wird...
    // Aufruf dann über {klassenname}::getSigleton() ... gibt das Objekt zurück
    public static function getSingleton()
    {
        if (null === self::$obj)
            self::$obj = new self;

        return self::$obj;
    }

}   // END abstract class CoreObject extends CoreMySQLi