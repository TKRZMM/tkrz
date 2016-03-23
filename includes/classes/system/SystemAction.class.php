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
 * ==>                                  '-> ConcreteClass1          CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ConcreteClass20         CoreExtends - Child - AnyCreature
 *
 */
namespace classes\system;


use classes\core\CoreExtends;
use fileUpload\FileUpload;


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



		// Hier geht es nur wetier wenn der User eingeloggt ist (userID vorhanden)!



		// Logout aufgerufen
		if ($this->checkCallActionByGET('callLogout')) {
			$hLogin->callLogout();

			return true;

		}



		// Setzte Default Frameset ... wird ggf. später überschrieben
		$this->coreGlobal['Load']['Frameset'] = 'frsStandard.inc.php';



		// TODO ... Action - Steuerung!!


		// Datei Upload?
		if ($this->checkCallActionByGET('fileUpload')) {

			// Setzte zu ladendes Frameset
			//$this->coreGlobal['Load']['Frameset'] = 'frsFileUpload.inc.php';

			// Setze zu ladendes Body Frameset
			$this->coreGlobal['Load']['FramesetBody'] = 'fileUpload/body/fileUploadBodySetFrame.inc.php';

			// TODO fileUpload - Klasse laden
			$curObj = new FileUpload();
			$curObj->callMe();
		}


		return true;

	}   // END private function loadOnInit()










	// Prüfung (true/false) auf eine callAction inkl. Rechte - Prüfung wenn nicht anders übergeben
	private function checkCallActionByGET($checkCallActionValue, $noRightCheck = false)
	{

		// TODO Rechte - Klasse?

		if ((isset($this->coreGlobal['GET']['callAction'])) && ($this->coreGlobal['GET']['callAction'] == $checkCallActionValue)) {

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