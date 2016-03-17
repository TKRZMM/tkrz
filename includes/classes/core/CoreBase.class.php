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
	public $coreMessages;    // Globale Variable für alle weiteren Klassen

	const LOGINFRAMESET = 'frsLogin.inc.php';
	const HOMEFRAMESET = 'frsStandard.inc.php';






	// Klassen eigener Konstruktor
	function __construct()
	{

		// $_GET  und $_POST abfangen und auf Sicherheit prüfen
		$this->setGetPostVar();

	}   // END function __construct()






	// Gibt das zu ladende Frameset zurück
	function getFrameset()
	{

		if ((isset($this->coreGlobal['Load']['Frameset'])) && (strlen($this->coreGlobal['Load']['Frameset']) > 0)) {
			return $this->coreGlobal['Load']['Frameset'];
		}

		return LOGINFRAMESET;

	}    // END function getFrameset()






	// $_GET  und $_POST abfangen und auf Sicherheit prüfen
	private function setGetPostVar()
	{

		if (isset($_GET)) {
			$this->coreGlobal['GET'] = $this->cleanGetPost($_GET);
		}

		if (isset($_POST)) {
			$this->coreGlobal['POST'] = $this->cleanGetPost($_POST);
		}

		return true;

	}    // END function saveGetPostVar()






	// Säubert $_GET und $_POST - Variable
	private function cleanGetPost($arg)
	{

		$retArray = array();

		if (is_array($arg)) {

			foreach($arg as $index => $value)
				$retArray[$index] = $this->cleanGetPost($value);

			return $retArray;

		} else {
			return $this->checkAddslashes($arg);
		}

	}    // END function cleanGetPost($arg)






	// Führt addslahes an einem übergebenen String durch und liefert ihn entsprechend zurück
	private function checkAddslashes($arg)
	{

		if (strpos(str_replace("\'", "", " $arg"), "'") != false)
			return addslashes($arg);

		else
			return $arg;

	}    // END function checkAddslashes(...){


}   // END class CoreBase