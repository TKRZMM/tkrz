<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:52
 *
 * Vererbungsfolge der (Basis) - Klassen:
 *  Abstract CoreBase                                               Adam/Eva
 *      '-> Abstract CoreSystemConfig                               Child
 *          '-> Abstract CoreDefaultConfig                          Child
 *              '-> Abstract CoreMessages                           Child
 *                  '-> Abstract CoreDebug                          Child
 *                      '-> Abstract CoreQuery                      Child
 * ==>                      '-> Abstract CoreMySQLi                 Child
 *                              '-> CoreObject                      Child
 *                                  '-> CoreExtends                 Child
 *                                      '-> ConcreteClass1          AnyCreature as Child via - extends CoreExtends
 *                                      |-> ...                     AnyCreature as Child via - extends CoreExtends
 *  -> ClassXYZ                                                    AnyCreature from Outerspace
 *  -> ...                                                            AnyCreature from Outerspace
 *
 */
namespace classes\core;


use mysqli;


abstract class CoreMySQLi extends CoreQuery
{

	// Initialsiere Variable
	private $lastResult = '';       // MySQLi Letztes Resultat
	private $lastInsertID = 0;      // Letzte eingefügte ID in einer Tabelle


	// Definiere Initial - Variable!
	// Achtung! Die tatsächlichen Werte werden in der 'includes/config/databaseConfig.inc.php' gesetzt!
	private $DBHOST = '';        // Datenbank Host
	private $DBNAME = '';        // (Default) Datenbank Name
	private $DBUSER = '';        // Datenbank Benutzer
	private $DBPASSWORD = '';    // Datenbank Passwort










	// Klassen eigener Konstruktor
	function __construct()
	{

		parent::__construct();

		// Setze Default Initial-Variable!
		$this->DBHOST = 'localhost';
		$this->DBNAME = '';
		$this->DBUSER = 'root';
		$this->DBPASSWORD = '';

	}   // END function __construct()










	// Placebo - Methode / Funktion
	function doNothing()
	{

		RETURN true;

	}   // END function doNothing()










	// Erzeuge Datenbankverbindung
	function createDBConnection()
	{

		// Setze Initial-Variable!
		$this->DBHOST = $_SESSION['Cfg']['Default']['DBSettings']['DBHOST'];
		$this->DBNAME = $_SESSION['Cfg']['Default']['DBSettings']['DBNAME'];
		$this->DBUSER = $_SESSION['Cfg']['Default']['DBSettings']['DBUSER'];
		$this->DBPASSWORD = $_SESSION['Cfg']['Default']['DBSettings']['DBPASSWORD'];


		// Art der Verbindung entsprechend der Einstellung aus der systemConfig.inc.php setzen
		if (strtolower($_SESSION['Cfg']['System']['DBSettings']['DBConnectionType']) == 'connect')
			$DBObject = new mysqli($this->DBHOST, $this->DBUSER, $this->DBPASSWORD, $this->DBNAME);
		else
			$DBObject = new mysqli('p:' . $this->DBHOST, $this->DBUSER, $this->DBPASSWORD, $this->DBNAME);


		// DB Verbindung fehlgeschlagen?
		if ($DBObject->connect_errno) {

			header('Content-Type: text/html; charset=Utf-8');

			print ("<pre>");
			$message = 'FEHLER -KRITISCH FÜHRT ZU EXIT-<br>Versuch Aufbau Datenbankverbindung fehlgeschlagen!<br>MySQL-Fehlermeldung: <br>';
			$message .= "Failed to connect to MySQL: (" . $DBObject->connect_errno . ") " . $DBObject->connect_error;
			print($message);
			print ("</pre>");
			exit;

		}


		// Speichere Verbindungs - Objekt
		$this->coreGlobal['Objects']['DBConnection'] = $DBObject;

		// Gebe das erzeugte Datenbank-Objekt zurück
		return $DBObject;

	}   // END function createDBConnection()










	// Liefert den aktuellen Objekt - Handler für die DB - Verbindung
	function getDBConnection()
	{

		if ((isset($this->coreGlobal['Objects']['DBConnection'])) && (is_object($this->coreGlobal['Objects']['DBConnection'])))
			return $this->coreGlobal['Objects']['DBConnection'];
		else
			return false;

	}   // END function getDBConnection()










	// MySQLi Query ausführen
	public function query($query, $debug = false)
	{

		if ($debug)
			$this->addDebugMessage($query);


		// Aktuelle DB - Verbindung holen
		$mysqli = $this->getDBConnection();


		// Query ausführen und in Resultat speichern
		$result = $mysqli->query($query);


		// Wurde Autoincrement hochgezählt? Wenn ja, letzte ID speichern
		/** @noinspection PhpUndefinedFieldInspection */
		if ($mysqli->insert_id > 0)
			/** @noinspection PhpUndefinedFieldInspection */
			$this->lastInsertID = $mysqli->insert_id;


		// Resultat in Klassen - Var speichern
		$this->lastResult = $result;


		RETURN $result;

	}    // END public function query(...)










	// MySQLi Funktion mysql_num_rows
	public function num_rows($result = null)
	{

		if ($result === null)
			$inc = $this->lastResult;
		else
			$inc = $result;

		/** @noinspection PhpUndefinedFieldInspection */
		$num_rows = $inc->num_rows;

		return ($num_rows);

	}    // END public function num_result(...)










	// MySQL Speicher freigeben
	public function free_result($result = null)
	{

		if ($result === null)
			$result = $this->lastResult;

		mysqli_free_result($result);

		return true;

	}   // END public function free_result(...)










	// Liefere Feldnamen einer angegebenen/gesuchten Tabelle
	public function dbGetFieldnamesByTablename($getTablename)
	{

		$returnArray = array();

		$myQuery = "SHOW COLUMNS FROM " . $getTablename;

		// Führe Query aus
		$result = $this->query($myQuery);

		if ($this->num_rows($result) >= 1) {

			while ($row = $result->fetch_object()) {

				$returnArray[] = $row->Field;

			}

			$this->free_result($result);

			return $returnArray;

		}

		$this->free_result($result);

		return $returnArray;

	}    // END public function dbGetFieldnamesByTablename(...)


}   // END abstract class CoreMySQLi extends CoreObject