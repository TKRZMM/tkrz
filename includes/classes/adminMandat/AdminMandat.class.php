<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.04.2016
 * Time: 10:59
 */

namespace adminMandat;


use classes\core\CoreExtends;
use classes\system\SystemLayout;


class AdminMandat extends CoreExtends
{

	// Objekt Handler aus dem Core - Klassen - System.
	public $myDynObj;

	// Globale Variable aus der Start-Klasse.
	public $coreGlobal;










	// FileImport constructor.
	function __construct($flagUseGlobalCoreClassObj = true)
	{

		parent::__construct();

	}   // END function __construct(...)










	public function initialAdminMandat()
	{

		// Todo Steuerung "Neuer Eintrag" ... "Suchen / Bearbeiten"

		// Default leiten wird auf Seite 1 der Benutzer-Eingaben
		$goToPage = 1;

		// Neuer Eintrag Daten vom User wurden übermittelt?
		if ((isset($this->coreGlobal['POST']['callAction'])) && ($this->coreGlobal['POST']['callAction'] == 'adminMandat')) {

			// Unvollständige Daten übergeben? ... Dann wieder auf Seite 1 (Dateneingabe) leiten
			if (!$this->handelNewMandatData())
				$goToPage = 1;
			else {
				$this->addMessage('Mandat erfolgreich angelegt!', 'Die neuen Mandats-Daten wurden erfolreich übernommen und werden bei künftigen Exports verwendet.', 'Erfolg', 'Neuer Eintrag');

				// Speicher freigeben
				unset($this->coreGlobal['POST']);
				unset($_POST);

				// Wieder auf Seite 1 leiten damit weitere Mandate eingetragen werden können
				$goToPage = 1;
			}


		}

		// Neuer Eintrag Seite 1 (User - Eingabe erwartet)
		$this->createPage($this->coreGlobal['GET']['subAction'], $goToPage);


	}    // END public function initialAdminMandat()










	// Verarbeitet die vom Benutzer übergebenen neuen Mandat-Daten
	private function handelNewMandatData()
	{

		// Leerzeichen aus den Eingaben entfernen lassen
		$this->myCleanSpaceInString($this->coreGlobal['GET']['subAction']);


		// Rquire - Eingaben abfangen
		if (!$this->checkRequireEntrysForNewMandat())
			return false;


		// Prüfen ob schon Datensatz vorhanden ist
		if ($this->checkForDoubleEntryOnNewMandat())
			return false;


		// Alles ok ... lasse jetzt die Daten als neuen Eintrag in die DB schreiben
		if (!$this->writeNewMandatToDB())
			return false;

		return true;

	}    // END private function handelNewMandatData()










	private function writeNewMandatToDB()
	{

		// TODO Überlegung ob ich über den SEPA - Status auch den Status des Mandat-Eintrages steuer... das ist für den Export wichtig!
		// $activeStatus = 'no';
		// if ($this->coreGlobal['POST']['lsStatus'] == 'Aktiv')
		// $activeStatus = 'yes';

		$activeStatus = 'yes';

		$query = "INSERT INTO centron_mand_ref (`userID`,
												`personenkonto`,
												`mandatsnummer`,
												`activeStatus`,
												`lsArt`,
												`lsType`,
												`lsStatus`,
												`dateOfExpire`,
												`createdOn`,
												`gotMandatOn`,
												`dateOfFirstUse`,
												`recalledOn`,
												`IBAN`,
												`BIC`
											) VALUES (
											'" . $_SESSION['Login']['userID'] . "',
											'" . $this->coreGlobal['POST']['customerID'] . "',
											'" . $this->coreGlobal['POST']['mandatNumber'] . "',
											'" . $activeStatus . "',
											'" . $this->coreGlobal['POST']['lsArt'] . "',
											'" . $this->coreGlobal['POST']['lsType'] . "',
											'" . $this->coreGlobal['POST']['lsStatus'] . "',
											'" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST']['dateOfExpire']) . "',
											'" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST']['createdOn']) . "',
											'" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST']['gotMandatOn']) . "',
											'" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST']['dateOfFirstUse']) . "',
											'" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST']['recalledOn']) . "',
											'" . $this->coreGlobal['POST']['IBAN'] . "',
											'" . $this->coreGlobal['POST']['BIC'] . "'
											) ";


		$this->query($query);

		return true;

	}    // END private function writeNewMandatToDB()










	// Prüft ob eine Mandats-Nummer oder eine Centron - Kunden-Nummer schon in der Datenbank (Mand-Ref) vorhanden ist.
	private function checkForDoubleEntryOnNewMandat()
	{

		// Parameter für getQuery setzen
		$paramArray = array('customerID'   => $this->coreGlobal['POST']['customerID'],
							'mandatNumber' => $this->coreGlobal['POST']['mandatNumber']);

		// Query holen
		$query = $this->getQuery('getMandatCheck', $paramArray);

		// Query ausführen
		$result = $this->query($query);

		if ($this->num_rows($result) == 1) {

			$row = $result->fetch_object();
			$selCentronMandRefIF = $row->centron_mand_refID;

			// Message für Fehler aufgetreten
			$this->addMessage('Mandat und/oder Kunde bereits vorhanden!', 'Die vorhanden Daten können unter folgendem Link eingesehen und bearbeitet werden.', 'Fehler', 'Neuer Eintrag', 'Link und ID:' . $selCentronMandRefIF);

			$this->free_result($result);

			return true;
		}

		$this->free_result($result);

		return false;

	}    // END	private function checkForDoubleEntryOnNewMandat()










	// Methode lässt Leerzeichen für die Formulare entfernen
	private function myCleanSpaceInString($getSubAction)
	{

		// Formular: Neuer Eintrag
		if ($getSubAction == 'newMandat') {
			// customerID ggf. Leerzeichen entfernen ... Methode ist in der CoreBase-Klasse
			$this->coreGlobal['POST']['customerID'] = $this->cleanSpaceInString($this->coreGlobal['POST']['customerID']);

			// mandatNumber ggf. Leerzeichen entfernen ... Methode ist in der CoreBase-Klasse
			$this->coreGlobal['POST']['mandatNumber'] = $this->cleanSpaceInString($this->coreGlobal['POST']['mandatNumber']);

			// IBAN ggf. Leerzeichen entfernen ... Methode ist in der CoreBase-Klasse
			$this->coreGlobal['POST']['IBAN'] = $this->cleanSpaceInString($this->coreGlobal['POST']['IBAN']);

			// BIC ggf. Leerzeichen entfernen ... Methode ist in der CoreBase-Klasse
			$this->coreGlobal['POST']['BIC'] = $this->cleanSpaceInString($this->coreGlobal['POST']['BIC']);
		}

		return true;

	}    // END private function myCleanSpaceInString()










	// Prüft (true/false) ob die benötitgen Eingaben für ein neues Mandat gegeben sind.
	private function checkRequireEntrysForNewMandat()
	{

		$boolGotError = false;

		// customerID  min. 1 max 20 prüfen ... Methode ist in der CoreBase-Klasse
		if (!$this->checkMinMaxByString(1, 20, $this->coreGlobal['POST']['customerID'])) {
			$this->addMessage('Dateneingabe unültig', 'Fehlende oder falsche Dateneingabe bei "Centron Kunden-Nr.".', 'Fehler', 'Neuer Eintrag', 'Das Feld ist ein Pflicht-Feld und muss entsprechend ausgefüllt werden.');
			$boolGotError = true;
		}

		// mandatNumber min. 1 max 20 prüfen ... Methode ist in der CoreBase-Klasse
		if (!$this->checkMinMaxByString(1, 20, $this->coreGlobal['POST']['mandatNumber'])) {
			$this->addMessage('Dateneingabe unültig', 'Fehlende oder falsche Dateneingabe bei "SEPA Mandats-Nr.".', 'Fehler', 'Neuer Eintrag', 'Das Feld ist ein Pflicht-Feld und muss entsprechend ausgefüllt werden.');
			$boolGotError = true;
		}

		if ($boolGotError)
			return false;

		return true;

	}    // END private function checkRequireEntrysForNewMandat()










	// Lässt die angeforderte Webseite erzeugen
	private function createPage($getSubAction, $getPageNumber = 0)
	{

		// Auswahl: Neuer Eintrag (Mandat anlegen)
		if ($getSubAction == 'newMandat') {

			// Seiten - Unterscheidung
			switch ($getPageNumber) {
				case 0:
				case 1:
					// User-Eingabe erwartet
					$this->coreGlobal['Load']['FramesetBody'] = 'adminMandat/body/adminMandatBodySetFrame.inc.php';        // Setze zu ladendes Body Frameset
					$this->coreGlobal['Load']['IncludeBody'] = 'adminMandatGetNewEntryBody.inc.php';                    // Setze zu ladendes Include in der Body Frameset
					break;


				default:
					break;
			}

			return true;
		}

		return false;

	}    // END private function createPage($getPageNumber=0)



	//////////////////////////////////////////////////////////////////////////////////////////


	/**
	 * Initial Methode zur Abhandlung von "Datei - Import".
	 *
	 * ... Setze das Body-Frameset auf das File-Import-Frameset
	 *        ... Das Frameset beinhaltet die erste html-Form (Auswahl der zu importierenden Daten)
	 *
	 * ... Übergebe bzw. der Auswahl an die Import-Methode.
	 *        ... Das Form-Frameset wird dann resettet
	 *
	 * Aufruf aus Klasse: SystemAction.class.php
	 */
	public function initialFileImport()
	{

		// Wurden Daten für den Import ausgewählt?
		$boolGotImportSelection = $this->checkDataSelection();

		// Keine Daten für den Import ausgewählt... Zeige Seite 1 (Datenauswahl)
		if (!$boolGotImportSelection) {

			// Seite 1 ... User Auswahl benötigt ... Auswahl der zu importierenden Daten ausgeben
			$this->createPageOneFileImport();
		}
		else {

			// Daten identifizieren (Typ und System)
			// Hole mir einen Teil des aufzurufenden Klassen-Namens
			// Hole mir einen Teil der aufzurufenden Methode
			$boolGotClassAndMethode = $this->getClassAndMethodByFileUploadID($this->coreGlobal['POST']['selFileUploadID'], $getClassPart, $getMethodPart);

			// Sicherheitsabbruch wenn die vorherige Datenbeschaffung nicht erfolgreich war
			if (!$boolGotClassAndMethode)
				return false;


			// Aufzurufender Klassen-Name (MIT Namespace!!!):
			$callClass = 'fileImport\FileImport' . $getClassPart;

			// Aufzurunfende Methode:
			$callMethod = 'fileImport' . $getMethodPart;

			// Rufe Klasse auf
			$hFileImport = new $callClass();

			// Rufe Methode der Klasse auf
			$hFileImport->$callMethod();

		}

		return true;

	}    // END public function initialFileImport()










	// Daten identifizieren (Typ und System) und Klassenname (Teil) und Methode (Teil) auf Pointer zurückgeben
	private function getClassAndMethodByFileUploadID($getFileUploadID, & $getClassPart, & $getMethodPart)
	{

		// Parameter für getQuery setzen
		$paramArray = array('fileUploadID' => $getFileUploadID);

		// Query holen
		$query = $this->getQuery('getInformationFromFileUploadByFileUploadID', $paramArray);

		// Query ausführen
		$result = $this->query($query);

		if ($this->num_rows($result) < 1) {

			// Message für Fehler aufgetreten
			$this->addMessage('Keine oder fehlerhafte Import-Daten!', 'Die gewählten Daten für den Import sind nicht vorhanden oder ungültig.', 'Info', 'Datenbank - Import');

			$this->free_result($result);

			return false;
		}


		$row = $result->fetch_object();

		$getClassPart = $row->sourceSystemName;
		$getMethodPart = $row->fileUploadDirName;


		// Speichere einige Informationen damit die Datei gleich eingelesen werden kann
		$this->coreGlobal['curDownloadLink'] = $row->downloadLink;      // Link zur gewählten Datei
		$this->coreGlobal['curSourceTypeID'] = $row->sourceTypeID;      // IDt = ID Type    (Stammdaten, Buchungssatz)
		$this->coreGlobal['curSourceSystemID'] = $row->sourceSystemID;  // IDs = ID System  (Diamri, Centron usw)
		$this->coreGlobal['curFilePath'] = $row->fileTargetFullPath;    // Pfad zum öffnen der Datei

		$this->free_result($result);

		return true;

	}    // END private function getClassAndMethodByFileUploadID(...)










	// Wurden Daten für den Import ausgewählt? ... Wenn nicht... dann zeige Datenauswahl
	private function checkDataSelection()
	{

		if ((isset($this->coreGlobal['POST']['callAction'])) && ($this->coreGlobal['POST']['callAction'] == 'dbImport')) {

			if ((isset($this->coreGlobal['POST']['selFileUploadID'])) && ($this->coreGlobal['POST']['selFileUploadID'] > 0)) {

				// Daten wurdn ausgewählt ... Start import
				return true;

			}

		}

		return false;

	}    // END private function checkDataSelectionFullHandling()










	// Seite 1 ... User Auswahl benötigt ... Auswahl der zu importierenden Daten ausgeben
	private function createPageOneFileImport()
	{

		// subAction & valueAction zuwesien
		$subAction = $this->coreGlobal['GET']['subAction'];
		$valueAction = $this->coreGlobal['GET']['valueAction'];

		$hMyLayout = new SystemLayout();

		// Typ ID ermitteln
		$getSourceTypeData = $hMyLayout->getActiveSourceTypeDataByX('fileUploadDirName', $subAction);
		$sourceTypeID = $getSourceTypeData['sourceTypeID'];

		// System ID ermitteln
		$getSourceSystemData = $hMyLayout->getActiveSourceSystemDataByX('sourceSystemName', $valueAction);
		$sourceSystemID = $getSourceSystemData['sourceSystemID'];


		// Parameter für getQuery setzen
		$paramArray = array('sourceTypeID'   => $sourceTypeID,
							'sourceSystemID' => $sourceSystemID);

		// Query holen
		$query = $this->getQuery('getFileImportList', $paramArray);

		// Query ausführen
		$result = $this->query($query);

		if ($this->num_rows($result) < 1) {

			// Message für Fehler aufgetreten
			$this->addMessage('Keine Import Daten vorhanden!', 'Für das gewählte System und zur gewählten Import-Art sind keine gütligen Daten vorhanden.', 'Info', 'Datenbank - Import');

			$this->free_result($result);

			return false;
		}

		// Resultat coreGlobal zuweisen damit es in der HTML-Datei verarbeitet werden kann.
		$this->coreGlobal['dbResult'] = $result;


		// Setze zu ladendes Body Frameset
		$this->coreGlobal['Load']['FramesetBody'] = 'fileImport/body/fileImportBodySetFrame.inc.php';

		// Setze zu ladendes Include in der Body Frameset
		$this->coreGlobal['Load']['IncludeBody'] = 'fileImportGetUserDataSelectionBody.inc.php';

		return true;

	}    // END private function createPageOneFileImport()


}   // END class FileImport extends CoreExtends