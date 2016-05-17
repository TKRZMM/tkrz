<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:50
 *
 * Vererbungsfolge der (Basis) - Klassen:
 *  Abstract CoreBase                                               Adam/Eva
 *      '-> Abstract CoreSystemConfig                               Child
 * ==>      '-> Abstract CoreDefaultConfig                          Child
 *              '-> Abstract CoreMessages                           Child
 *                  '-> Abstract CoreDebug                          Child
 *                      '-> Abstract CoreQuery                      Child
 *                          '-> Abstract CoreMySQLi                 Child
 *                              '-> CoreObject                      Child
 *                                  '-> CoreExtends                 Child
 *                                      '-> ConcreteClass1          AnyCreature as Child via - extends CoreExtends
 *                                      |-> ...                     AnyCreature as Child via - extends CoreExtends
 *  -> ClassXYZ                                                    AnyCreature from Outerspace
 *  -> ...                                                            AnyCreature from Outerspace
 *
 */
namespace classes\core;


abstract class CoreDefaultConfig extends CoreSystemConfig
{

	// Config - Datein die geladen werden sollen
	const FULLCFGFILEDEFAULT = 'includes/configs/defaultConfig.inc.ini';

	const FULLCFGFILEDATABASE = 'includes/configs/databaseConfig.inc.ini';










	// Klassen eigener Konstruktor
	function __construct()
	{

		parent::__construct();

	}   // END function __construct()










	// Lade Config - Files
	function loadDefaultConfigs()
	{

		// Datenbank Config - File einlesen
		$this->loadSingleConfig(self::FULLCFGFILEDATABASE);

		// Default Config - File einlesen
		$this->loadSingleConfig(self::FULLCFGFILEDEFAULT);

		// Flag für "Config geladen" setzen
		$this->coreGlobal['Flag']['Cfg']['Loaded']['Default'] = 'yes';

	}   // END loadDefaultConfigs()










	// Lade Config - File
	private function loadSingleConfig($curConfigFile)
	{

		if (!file_exists($curConfigFile))
			$this->mySimpleout(1, $curConfigFile);


		if (!$iniArray = @parse_ini_file($curConfigFile, true))
			$this->mySimpleout(2, $curConfigFile);


		// Übergebe an Methode zur Session - Übergabe
		$this->setDefaultConfig($iniArray);

	}   // END function loadSingleConfig(...)










	// Setzt die geladenen Config - Werte in der Session
	private function setDefaultConfig($getConfigArray)
	{

		if (isset($_SESSION['Cfg']['Default']))
			$_SESSION['Cfg']['Default'] = array_merge($_SESSION['Cfg']['Default'], $getConfigArray);
		else
			$_SESSION['Cfg']['Default'] = $getConfigArray;

	}   // END private function setDefaultConfig(...)










	// Fehlermeldungen die nicht über die Message - Klasse abgefangen werden können
	private function mySimpleout($getCaseNum, $addArg = '')
	{

		header('Content-Type: text/html; charset=Utf-8');
		print ("<pre>");

		$message = 'FEHLER -KRITISCH FÜHRT ZU EXIT-<br>Versuch Konfigurationsdatei einzulesen fehlgeschlagen!<br>Fehlermeldung: <br>';

		switch ($getCaseNum) {
			case 1:
				$message .= "Datei '" . $addArg . "' existiert nicht oder kann nicht gelesen werden!";
				break;

			case 2:
				$message .= "Datei '" . $addArg . "' Syntax error!";
				break;

			default:
				$message .= "Unbekannter Fehler bei Konfigurationsdatei: " . $addArg;
		}

		print($message);
		print ("</pre>");
		exit;

	}    // END private function mySimpleout(...)


}   // END abstract class CoreDefaultConfig extends CoreSystemConfig