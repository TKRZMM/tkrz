<?php

/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:52
 */
abstract class CoreMySQLi extends CoreQuery
{

    //public $coreGlobal;     // Kopiere globale Variable aus der Start-Klasse
    public $myValue;        // Klassen eigene Variable


    // Defniere Initial - Variable!
    // Achtung! Die tatsächlichen Werte werden in der 'includes/configs/customConfig.inc.php' gesetzt!
    private $DBHOST			= '';	// Datenbank Host
    private $DBNAME			= '';	// (Default) Datenbank Name
    private $DBUSER			= '';	// Datenbank Benutzer
    private $DBPASSWORD		= '';	// Datenbank Passwort





    // Klassen eigener Konstruktor
    function __construct()
    {

        parent::__construct();

        // Setze Initial-Variable!
        $this->DBHOST		= 'localhost';
        $this->DBNAME		= 'tkrz';
        $this->DBUSER		= 'root';
        $this->DBPASSWORD	= '';

    }   // END function __construct()





    // Placebo - Methode / Funktion
    function doNothing()
    {

        RETURN TRUE;

    }





    // Erzeuge Datenbankverbindung
    function createDBConnection()
    {

        //TODO In Config einstellbar machen ob pconnect oder normale Verbindung genutzt werden soll

//        $DBObject = new mysqli($this->DBHOST, $this->DBUSER, $this->DBPASSWORD, $this->DBNAME);
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
        $this->coreGlobal['DBObject'] = $DBObject;

        return $DBObject;

    }   // END function createDBConnection()





    // Liefer den aktuellen Objekt - Handler für die DB - Verbindung
    function getDBConnection()
    {
        if ( (isset($this->coreGlobal['DBObject'])) && (is_object($this->coreGlobal['DBObject'])) )
            return $this->coreGlobal['DBObject'];
        else
            return false;
    }



}   // END abstract class CoreMySQLi extends CoreObject