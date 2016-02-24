<?php

/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:53
 *
 * Vererbungsfolge der (Basis) - Klassen:
 *  Abstract CoreBase                                               Adam/Eva
 *      '-> Abstract CoreSystemConfig                               Child
 *          '-> Abstract CoreDefaultConfig                          Child
 *              '-> Abstract CoreMessages                           Child
 *                  '-> Abstract CoreDebug                          Child
 *                      '-> Abstract CoreQuery                      Child
 *                          '-> Abstract CoreMySQLi                 Child
 * ==>                          '-> CoreObject                      Child
 *                                  '-> CoreExtends                 Child
 *                                      '-> ConcreteClass1          CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ConcreteClass20         CoreExtends - Child - AnyCreature
 *
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