<?php

/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:49
 *
 * Vererbungsfolge der (Basis) - Klassen:
 * ==> Abstract CoreBase                                            Adam/Eva
 *      '-> Abstract CoreSystemConfig                               Child
 *          '-> Abstract CoreDefaultConfig                          Child
 *              '-> Abstract CoreMessages                           Child
 *                  '-> Abstract CoreDebug                          Child
 *                      '-> Abstract CoreQuery                      Child
 *                          '-> Abstract CoreMySQLi                 Child
 *                              '-> CoreObject                      Child
 *                                  '-> CoreExtends                 Child
 *                                      '-> ConcreteClass1          CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ConcreteClass20         CoreExtends - Child - AnyCreature
 *
 */
abstract class CoreBase
{

	// Initialsiere Variable
	public $coreGlobal;      // Globale Variable für alle weiteren Klassen

	const LOGINFRAMESET = 'frsLogin.inc.php';
	const HOMEFRAMESET = 'frsStandard.inc.php';



	// Klassen eigener Konstruktor
	function __construct()
	{


	}   // END function __construct()





	// Gibt das zu ladende Frameset zurück
	function getFrameset()
	{
		if ( (isset($this->coreGlobal['Load']['Frameset'])) && (strlen($this->coreGlobal['Load']['Frameset']) > 0) ){
			return $this->coreGlobal['Load']['Frameset'];
		}

		return LOGINFRAMESET;
	}	// END function getFrameset()

}   // END class CoreBase