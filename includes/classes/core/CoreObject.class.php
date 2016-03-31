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
 *                                      '-> ConcreteClass1          AnyCreature as Child via - extends CoreExtends
 *                                      |-> ...                     AnyCreature as Child via - extends CoreExtends
 *  -> ClassXYZ                 									AnyCreature from Outerspace
 *  -> ...         													AnyCreature from Outerspace
 *
 */
namespace classes\core;


class CoreObject extends CoreMySQLi
{

	// Initialsiere Variable
	protected static $obj = null;










	// Klassen eigener Konstruktor
	function __construct()
	{

		parent::__construct();

	}   // END function __construct()










	// Vor Clone-Funktion schützen
	protected function __clone()
	{
	}    // END protected function __clone()



	// Stellt sicher, dass nur eine Instanz der Klasse erzeugt wird...
	// Aufruf dann über {klassenname}::getSigleton() ... gibt das Objekt zurück
	public static function getSingleton()
	{

		if (null === self::$obj)
			self::$obj = new self;

		return self::$obj;

	}   // END public static function getSingleton()

}   // END class CoreObject extends CoreMySQLi