<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:51
 *
 * Vererbungsfolge der (Basis) - Klassen:
 *  Abstract CoreBase                                               Adam/Eva
 *      '-> Abstract CoreSystemConfig                               Child
 *          '-> Abstract CoreDefaultConfig                          Child
 * ==>          '-> Abstract CoreMessages                           Child
 *                  '-> Abstract CoreDebug                          Child
 *                      '-> Abstract CoreQuery                      Child
 *                          '-> Abstract CoreMySQLi                 Child
 *                              '-> CoreObject                      Child
 *                                  '-> CoreExtends                 Child
 *                                      '-> ConcreteClass1          AnyCreature as Child via - extends CoreExtends
 *                                      |-> ...                     AnyCreature as Child via - extends CoreExtends
 *  -> ClassXYZ                 									AnyCreature from Outerspace
 *  -> ...         													AnyCreature from Outerspace
 *
 */
namespace classes\core;


abstract class CoreMessage extends CoreDefaultConfig
{

	// Initialsiere Variable
	public $messages;    // Globale Message Variable für alle weiteren Klassen










	// Klassen eigener Konstruktor
	function __construct()
	{

		parent::__construct();

	}   // END function __construct()










	// Fügt einen Message - Datensatz hinzu, der ausgegeben werden soll
	function addMessage($headline = null, $message, $type = null, $code = null, $explain = null)
	{

		$this->coreMessages['headline'][] = $headline;    // z.B. "Fehlerhafter Login"
		$this->coreMessages['message'][] = $message;    // z.B. "Benutzername und/oder Passwort-Kombination unbekannt.
		$this->coreMessages['type'][] = $type;    // z.B. "error" ... für Kategorisierung
		$this->coreMessages['code'][] = $code;    // z.B. "login" ... für Bereich
		$this->coreMessages['explain'][] = $explain;    // z.B. "Bitte überprüfen Sie ihre Zugangsdaten".

	}    // END function addMessage(...)


}   // END abstract class CoreMessage extends CoreDefaultConfig