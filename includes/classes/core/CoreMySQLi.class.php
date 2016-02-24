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
 *                                      '-> ConcreteClass1          CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ConcreteClass20         CoreExtends - Child - AnyCreature
 *
 */
abstract class CoreMySQLi extends CoreQuery
{

    private $lastResult	    = '';       // MySQLi Letztes Resultat
    private $lastInsertID   = 0;        // Letzte eingefügte ID in einer Tabelle


    // Defniere Initial - Variable!
    // Achtung! Die tatsächlichen Werte werden in der 'includes/config/databaseConfig.inc.php' gesetzt!
    private $DBHOST			= '';	    // Datenbank Host
    private $DBNAME			= '';	    // (Default) Datenbank Name
    private $DBUSER			= '';	    // Datenbank Benutzer
    private $DBPASSWORD		= '';	    // Datenbank Passwort





    // Klassen eigener Konstruktor
    function __construct()
    {

        parent::__construct();

        // Setze Default Initial-Variable!
        $this->DBHOST		= 'localhost';
        $this->DBNAME		= '';
        $this->DBUSER		= 'root';
        $this->DBPASSWORD	= '';

    }   // END function __construct()





    // Placebo - Methode / Funktion
    function doNothing()
    {

        RETURN TRUE;

    }   // END function doNothing()





    // Erzeuge Datenbankverbindung
    function createDBConnection()
    {
        // Setze Initial-Variable!
        $this->DBHOST		= $_SESSION['Cfg']['Default']['DBSettings']['DBHOST'];
        $this->DBNAME		= $_SESSION['Cfg']['Default']['DBSettings']['DBNAME'];
        $this->DBUSER		= $_SESSION['Cfg']['Default']['DBSettings']['DBUSER'];
        $this->DBPASSWORD	= $_SESSION['Cfg']['Default']['DBSettings']['DBPASSWORD'];


        // Art der Verbindung entsprechend der Einstellung aus der systemConfig.inc.php setzen
        if (strtolower($_SESSION['Cfg']['System']['DBSettings']['DBConnectionType']) == 'connect')
            $DBObject = new mysqli($this->DBHOST, $this->DBUSER, $this->DBPASSWORD, $this->DBNAME);
        else
            $DBObject = new mysqli('p:'.$this->DBHOST, $this->DBUSER, $this->DBPASSWORD, $this->DBNAME);


        // DB Verbindung fehlgeschlagen?
        if ($DBObject->connect_errno) {

            header('Content-Type: text/html; charset=Utf-8');

            print ("<pre>");
            $message = "FEHLER -KRITISCH FÜHRT ZU EXIT-<br>";
            $message .= "Versuch Aufbau Datenbankverbindung fehlgeschlagen!<br>";
            $message .= "MySQL-Fehlermeldung: <br>";
            $message .= "Failed to connect to MySQL: (" . $DBObject->connect_errno . ") " . $DBObject->connect_error;
            print($message);
            print ("</pre>");
            exit;

        }


        // Speichere Verbindungs - Objekt
        $this->coreGlobal['Objects']['DBConnection'] = $DBObject;


        return $DBObject;

    }   // END function createDBConnection()





    // Liefert den aktuellen Objekt - Handler für die DB - Verbindung
    function getDBConnection()
    {

        if ( (isset($this->coreGlobal['Objects']['DBConnection'])) && (is_object($this->coreGlobal['Objects']['DBConnection'])) )
            return $this->coreGlobal['Objects']['DBConnection'];
        else
            return false;

    }   // END function getDBConnection()





    // MySQLi Query ausführen
    public function query($query, $debug=false)
    {

        // Aktuelle DB - Verbindung holen
        $mysqli = $this->getDBConnection();


        // Query ausführen und in Resultat speichern
        $result = $mysqli->query($query);


        // Wurde Autoincrement hochgezählt? Wenn ja, letzte ID speichern
        if ($mysqli->insert_id > 0)
            $this->lastInsertID = $mysqli->insert_id;


        // Resultat in Klassen - Var speichern
        $this->lastResult = $result;


        RETURN $result;

    }	// END public function query(...)





    // MySQLi Funktion mysql_num_rows
    public function num_rows($result = NULL)
    {

        if($result === NULL)
            $inc = $this->lastResult;
        else
            $inc = $result;

        $num_rows = $inc->num_rows;

        return($num_rows);

    }	// END public function num_result(...)





    // MySQL Speicher freigeben
    public function free_result($result = NULL)
    {

        if($result === NULL)
            $result = $this->lastResult;

        mysqli_free_result($result);

        return TRUE;

    }   // END public function free_result(...)


}   // END abstract class CoreMySQLi extends CoreObject