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
 *                                      '-> ConcreteClass1          AnyCreature as Child via - extends CoreExtends
 *                                      |-> ...                     AnyCreature as Child via - extends CoreExtends
 *     -> ClassXYZ                                                    AnyCreature from Outerspace
 *     -> ...                                                        AnyCreature from Outerspace
 *
 */
namespace classes\core;


abstract class CoreBase
{

	// Initialsiere Variable
	public $coreGlobal;      // Globale Variable für alle weiteren Klassen
	public $coreMessages;    // Globale CoreMessage Variable für alle weiteren Klassen

	// Definiere das default Frameset... es wird geladen wenn kein anderes Frameset gesetzt wurde
	const LOGINFRAMESET = 'frsLogin.inc.php';        // Frameset für Login - Form/Prozedur

	// Definiere das default Frameset nach dem Login... es wird gealden wenn der User eingeloggt ist, aber keine
	// weiteres Frameset gesetzt wurde
	const HOMEFRAMESET = 'frsStandard.inc.php';        // Frameset nach dem Login










	// Klassen eigener Konstruktor
	function __construct()
	{

		// $_GET  und $_POST abfangen und auf Sicherheit prüfen
		$this->setGetPostVar();

	}   // END function __construct()










	// Gibt den Inhalt von globalCore[GET oder POST][xxxAction] zurück
	function getActionAsString($actionType)
	{

		if (isset($this->coreGlobal['GET'][$actionType]))
			return $this->coreGlobal['GET'][$actionType];

		elseif (isset($this->coreGlobal['POST'][$actionType]))
			return $this->coreGlobal['POST'][$actionType];

		else
			return null;

	}    // END function getCallActionAsString()










	// Gibt das aktuell zu ladende Frameset zurück
	function getFrameset()
	{

		// Wenn ein Frameset gesetzt wurde, gebe das zurück
		if ((isset($this->coreGlobal['Load']['Frameset'])) && (strlen($this->coreGlobal['Load']['Frameset']) > 0))
			return $this->coreGlobal['Load']['Frameset'];

		// Kein Frameset gesetzt, gebe das default Login - Frameset zurück
		return self::LOGINFRAMESET;

	}    // END function getFrameset()










	// Gibt eine formatierte Nummer mit führenden Nullen gewünscht zurück
	function getFormatedNumberWithLeadingNulls($getNumber, $maxNumbers)
	{

		return sprintf("%'.0" . $maxNumbers . "d", $getNumber);

	}    // END function getFormatedNumberWithLeadingNulls(...)










	// $_GET und $_POST abfangen und auf Sicherheit prüfen
	private function setGetPostVar()
	{

		// Übernehme die $_GET Argumente nach dem "Cleanup"
		if (isset($_GET))
			$this->coreGlobal['GET'] = $this->cleanGetPost($_GET);


		// Übernehme die $_POS Argumente nach dem "Cleanup"
		if (isset($_POST))
			$this->coreGlobal['POST'] = $this->cleanGetPost($_POST);

		return true;

	}    // END function setGetPostVar()










	// "Cleanup" Säubert $_GET und $_POST - Variable
	private function cleanGetPost($arg)
	{

		$retArray = array();

		if (is_array($arg)) {

			// Selbstaufruf bei übergebenem Array als Parameter
			foreach($arg as $index => $value)
				$retArray[$index] = $this->cleanGetPost($value);

			return $retArray;

		}
		else
			return $this->checkAddslashes($arg);

	}    // END function cleanGetPost(...)










	// Führt addslahes an einem übergebenen String durch und liefert ihn entsprechend zurück
	public function checkAddslashes($arg)
	{

		if (strpos(str_replace("\'", "", " $arg"), "'") != false)
			return addslashes($arg);

		else
			return $arg;

	}    // END function checkAddslashes(...)










	// Führt addslahes an einem übergebenen String durch und liefert ihn entsprechend zurück
	public function myAddslashes($arg)
	{

		$suchmuster = '/\//i';
		$ersetzung = '\/';
		$arg = preg_replace($suchmuster, $ersetzung, $arg);

		return $arg;

	}    // END function checkAddslashes(...)










	// Methode bereitet Datei-Groesse lesbarer auf
	function formatSizeUnits($bytes)
	{

		if ($bytes >= 1073741824)
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';

		elseif ($bytes >= 1048576)
			$bytes = number_format($bytes / 1048576, 2) . ' MB';

		elseif ($bytes >= 1024)
			$bytes = number_format($bytes / 1024, 2) . ' KB';

		elseif ($bytes > 1)
			$bytes = $bytes . ' bytes';

		elseif ($bytes == 1)
			$bytes = $bytes . ' byte';

		else
			$bytes = '0 bytes';

		return $bytes;

	}   // END function formatSizeUnits(...)










	// Methode prüft (übergebene) min/max Länge eines übergebenen Strings (true/false)
	function checkMinMaxByString($min, $max, $string)
	{

		// Min - Max - Länge prüfen
		if ((strlen($string) < $min) || (strlen($string) > $max))
			return false;

		return true;

	}    // END function checkMinMaxByString($min, $max, $string)










	// Entfernt Leerzeichen in einem String
	function cleanSpaceInString($string)
	{

		$search = '/ /';

		$string = preg_replace($search, '', $string);

		return $string;

	}    // END function cleanSpaceInString($string)










	// Formatiert ein Datumsformat für MySQL
	function formatDateForMySQLWithNoSlash($getDate)
	{

		// Wenn kein gültiges Datum übergeben wurde... gebe ich den eigenen Wert zurück
		if (strlen($getDate) < 10)
			return $getDate;

		// Sollte newDate nicht überschrieben werden (weil kein passendes Datumsformat) gebe ich den Eingangswert zurück
		$newDate = $getDate;

		// Bekomme: YYYY-MM-DD
		$search = '/(\d+)\-(\d+)\-(\d+)/';
		preg_match($search, $getDate, $matches);

		// Gebe zurück: YYYY-MM-DD
		if ((isset($matches[1])) && (isset($matches[2])) && (isset($matches[3])))
			$newDate = $matches[1] . '-' . $matches[2] . '-' . $matches[3];

		return $newDate;

	}    // END function formatDateForMySQLWithNoSlash($getDate)

}   // END class CoreBase