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
 *                              '-> CoreObject                      Child
 * ==>                              '-> CoreExtends                 Child
 *                                      '-> ConcreteClass1          AnyCreature as Child via - extends CoreExtends
 *                                      |-> ...                     AnyCreature as Child via - extends CoreExtends
 *  -> ClassXYZ                                                    AnyCreature from Outerspace
 *  -> ...                                                            AnyCreature from Outerspace
 *
 */
namespace classes\core;


class CoreExtends extends CoreObject
{

	// Initialsiere Variable
	public $myDynObj;       // Objekt Handler aus dem Core - Klassen - System
	public $coreGlobal;     // Kopiere globale Variable aus der Start-Klasse
	public $coreMessages;   // Kopiere globale Variable aus der Start-Klasse










	// Klassen eigener Konstruktor
	function __construct($flagUseGlobalCoreClassObj = true)
	{

		parent::__construct();



		// Benutze das globale Core-Klassen-Objekt in der Klasse!
		if ($flagUseGlobalCoreClassObj)
			$this->getGlobalCoreObject();


		// DefaultConfig laden?
		if ((!isset($this->coreGlobal['Flag']['Cfg']['Loaded']['Default'])) || ($this->coreGlobal['Flag']['Cfg']['Loaded']['Default'] != 'yes'))
			$this->loadDefaultConfigs();


		// SystemConfig laden?
		if ((!isset($this->coreGlobal['Flag']['Cfg']['Loaded']['System'])) || ($this->coreGlobal['Flag']['Cfg']['Loaded']['System'] != 'yes'))
			$this->loadSystemConfigs();


		// Datenbank - Verbindung aufbauen?
		if ((!isset($this->coreGlobal['Objects']['DBConnection'])) || (!is_object($this->coreGlobal['Objects']['DBConnection'])))
			$this->createDBConnection();
		else
			$this->getDBConnection();

	}   // END function __construct(...)










	// Benutze in der Klasse das globale Core-Klassen-Objekt
	private function getGlobalCoreObject()
	{

		// Sicher stellen das wir den Core-Klassen-Objekt - Handler der Basisklassen nur einmal haben / benutzen
		$this->myDynObj = CoreObject::getSingleton();

		// Lokales coreGlobal referenzieren
		$this->coreGlobal = &$this->myDynObj->coreGlobal;

		// Lokales coreMessages referenzieren
		$this->coreMessages = &$this->myDynObj->coreMessages;

	}   // END private function getGlobalCoreObject()


}   // END class CoreExtends extends CoreObject