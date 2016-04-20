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
 *                                  '-> CoreExtends                 Child
 * ==>                                  '-> ConcreteClass1          AnyCreature as Child via - extends CoreExtends
 *                                      |-> ...                     AnyCreature as Child via - extends CoreExtends
 *  -> ClassXYZ                 									AnyCreature from Outerspace
 *  -> ...         													AnyCreature from Outerspace
 *
 */
namespace classes\system;


use classes\core\CoreExtends;
use fileUpload\FileUpload;
use fileImport\FileImport;
use fileExport\FileExport;


class SystemAction extends CoreExtends
{

	// Initialsiere Variable
	public $myDynObj;       // Objekt Handler aus dem Core - Klassen - System
	public $coreGlobal;     // Kopiere globale Variable aus der Start-Klasse










	// Klassen eigener Konstruktor
	function __construct($flagUseGlobalCoreClassObj = true)
	{

		parent::__construct();

		// Initial Aufruf für generelle Überprüfungen (Login usw.)
		$this->loadOnInit();

	}   // END function __construct(...)










	// Initial Methode bei Aufruf/Init der Klasse
	private function loadOnInit()
	{

		// Login - Status prüfen
		$hLogin = new SystemLogin();

		$this->coreGlobal['Objects']['hLogin'] = $hLogin;

		if (!$checkUserID = $hLogin->checkLoginStatus()) {

			// Keine userID also noch kein eingelogter User

			// Login - Action aufgerufen? ---> Weiterleiten zur Login - Prüfung
			if ((isset($this->coreGlobal['POST']['callAction'])) && ($this->coreGlobal['POST']['callAction'] == 'callLogin')) {

				// Login - Daten ok! User wurde in der Login - Klasse eingeloggt!
				if ($hLogin->callLogin()) {

					// Setzte Default Frameset ... wird ggf. später überschrieben
					$this->coreGlobal['Load']['Frameset'] = 'frsStandard.inc.php';

					return true;

				}
			}

			// Setze Frameset auf Login - Maske
			$this->coreGlobal['Load']['Frameset'] = 'frsLogin.inc.php';

			return true;
		}


		//////////////////////////////////////////////////////////////////////////////
		// Hier geht es nur weiter wenn der User eingeloggt ist (userID vorhanden)! //
		//////////////////////////////////////////////////////////////////////////////


		// Logout aufgerufen
		if ($this->checkCallActionByMethodType('GET', 'callLogout')) {

			$hLogin->callLogout();

			return true;

		}



		// Setzte Default Frameset ... wird ggf. später überschrieben
		$this->coreGlobal['Load']['Frameset'] = 'frsStandard.inc.php';


		//////////////////////////////////////////////
		// Ab hier ist die Action - Steuerung aktiv //
		//////////////////////////////////////////////


		// Datei Upload?
		if ($this->checkCallActionByAnyMethod('fileUpload')) {

			// Lade FileUpload - Klasse
			$objFileUpload = new FileUpload();

			// Übergebe an Initial-Methode der FileUpload - Klasse
			$objFileUpload->initialFileUpload();

			// Keine weiteren Prüfungen in der Action an dieser Stelle
			return true;

		}    // END // Datei Upload?




		// Datei Import?
		elseif ($this->checkCallActionByAnyMethod('dbImport')){

			// Lade FileUpload - Klasse
			$objFileImport = new FileImport();

			// Übergebe an Initial-Methode der FileImport - Klasse
			$objFileImport->initialFileImport();

			// Keine weiteren Prüfungen in der Action an dieser Stelle
			return true;

		}	// END // Datei Import?




		// Daten / Datei Export?
		elseif ($this->checkCallActionByAnyMethod('dbExport')){

			// Lade FileExport - Klasse
			$objFileExport = new FileExport();

			// Übergebe an Initial-Methode der FileExport - Klasse
			$objFileExport->initialFileExport();

			// Keine weiteren Prüfungen in der Action an dieser Stelle
			return true;

		}	// END // Datei Import?



		return true;

	}   // END private function loadOnInit()










	// Prüfung GET und POST (true/false) auf eine callAction inkl. Rechte - Prüfung wenn nicht anders übergeben
	private function checkCallActionByAnyMethod($checkCallActionValue, $noRightCheck = false)
	{

		// Prüfe zunächste auf GET
		if ($this->checkCallActionByMethodType('GET', $checkCallActionValue, $noRightCheck))
			return true;

		// Wenn noch nicht ok, prüfe ich jetzt auf POST
		if ($this->checkCallActionByMethodType('POST', $checkCallActionValue, $noRightCheck))
			return true;

		// Keine der Methoden liefert true... also gebe ich auch false zurück
		return false;

	}    // END private function checkCallActionByAnyMethod(...)










	// Prüfung GET oder POST (true/false) auf eine callAction inkl. Rechte - Prüfung wenn nicht anders übergeben
	private function checkCallActionByMethodType($methodType, $checkCallActionValue, $noRightCheck = false)
	{

		// TODO Rechte - Klasse?

		if ((isset($this->coreGlobal[$methodType]['callAction'])) && ($this->coreGlobal[$methodType]['callAction'] == $checkCallActionValue)) {

			// Rechteprüfung nicht gewünscht?
			if ($noRightCheck == false)
				return true;
			else {
				// Zur Rechteprüfung
				if (!$this->checkRightForCallActionByUserID($checkCallActionValue))
					return false;

				return true;
			}
		}

		return false;

	}    // END private function checkCallActionByGET(...)










	// Prüft ob die übergebene callAction vom User aufgerufen werden darf.
	private function checkRightForCallActionByUserID($checkCallActionValue)
	{

		// TODO Rechte - Klasse?

		// TODO hier muss der Rechte-Check eingebaut werden und bei fail eine Message gesetzt werden
		if ($checkCallActionValue) {
			// irgendwas in DB nachsehen
		}

		return true;

	}    // END private function checkRightForCallActionByUserID(...)


}   // END class SystemAction extends CoreExtends