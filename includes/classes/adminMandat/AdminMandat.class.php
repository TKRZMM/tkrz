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










	// Initial Methode der Klasse die aus der SystemAction - Klasse aufgerufen wird
	public function initialAdminMandat()
	{

		// Per Default leiten wird auf Seite 1 der Benutzer-Eingaben
		$goToPage = 1;


		// Klasse nur ausführen wenn wir im Menue der SEPA Verwaltung sind
		if ((isset($this->coreGlobal['POST']['callAction'])) && ($this->coreGlobal['POST']['callAction'] == 'adminMandat')) {

			// Sub-Action Unterscheidung


			// Neues Mandat anlegen?
			if ((isset($this->coreGlobal['GET']['subAction'])) && ($this->coreGlobal['GET']['subAction'] == 'newMandat'))
				$goToPage = $this->initialHandelNewMandatData();


			// Suchen Bearbeiten?
			if ((isset($this->coreGlobal['GET']['subAction'])) && ($this->coreGlobal['GET']['subAction'] == 'searchEdit'))
				$goToPage = $this->initialHandelSearchEditData();


			// Neuer Eintrag Seite 1 (User - Eingabe erwartet)
			$this->createPage($this->coreGlobal['GET']['subAction'], $goToPage);

			return true;
		}


		// Neuer Eintrag Seite 1 (User - Eingabe erwartet)
		$this->createPage($this->coreGlobal['GET']['subAction'], $goToPage);

		return false;

	}    // END public function initialAdminMandat()










	// Initial Methode für Suchen / Bearbeiten
	private function initialHandelSearchEditData()
	{

		$goToPage = 1;

		if (!$this->handelSearchData())
			$goToPage = 1;


		/*
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
		*/

		return $goToPage;

	}    // END private function initialHandelNewMandatData()










	// Verarbeitet die vom Benutzer übergebenen neuen Mandat-Daten
	private function handelSearchData()
	{



		// Leerzeichen aus den Eingaben entfernen lassen
		$this->myCleanSpaceInString($this->coreGlobal['GET']['subAction']);


		// SuchQuery - erstellen
		if (!$getSearchQuery = $this->createQueryForSearchMandat())
			return false;


		// Suche jetzt ausführen lassen
		if (!$this->doDBSearch($getSearchQuery))
			return false;

//		// Prüfen ob schon Datensatz vorhanden ist
//		if ($this->checkForDoubleEntryOnNewMandat())
//			return false;
//
//
//		// Alles ok ... lasse jetzt die Daten als neuen Eintrag in die DB schreiben
//		if (!$this->writeNewMandatToDB())
//			return false;

		// return true;
		return false;

	}    // END private function handelNewMandatData()










	// Methode führt Such-Query aus
	private function doDBSearch($query)
	{
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);

		if ($num_rows >= '1') {
			while ($row = $result->fetch_object()) {

				// $mandRefArray[$row->personenkonto] = $row->mandatsnummer;
				// TODO Daten gefunden... zwischenspeichern... Edit-Seite ausgeben usw.
			}
			$this->free_result($result);
		}
		else {
			$this->free_result($result);

			$this->addMessage('Keine Daten gefunden!','Zu den gesuchten Angaben sind keine Daten gespeichert.','Info','Suchen / Bearbeiten');

			return false;
		}

		return true;

	}    // END private function doDBSearch($query)










	// Methode erstellt die MySQL - Suchquery anhand der gesetzten Such-Felder
	private function createQueryForSearchMandat()
	{

		$add = '';

		// Format: xyz = array('HMTLInputNamen' => 'DatenbankFeldNamen')
		$htmlToDBName = array('customerID'     => 'personenkonto',
							  'mandatNumber'   => 'mandatsnummer',
							  'lsArt'          => 'lsArt',
							  'lsType'         => 'lsType',
							  'lsStatus'       => 'lsStatus',
							  'dateOfExpire'   => 'dateOfExpire',
							  'createdOn'      => 'createdOn',
							  'gotMandatOn'    => 'gotMandatOn',
							  'dateOfFirstUse' => 'dateOfFirstUse',
							  'recalledOn'     => 'recalledOn',
							  'IBAN'           => 'IBAN',
							  'BIC'            => 'BIC'
		);

		// Durchlauf der möglichen Variable ... wenn gesetzt werden sie in die Query integriert.
		foreach($htmlToDBName as $htmlName => $fieldName) {
			if ((isset($this->coreGlobal['POST'][$htmlName])) && (strlen($this->coreGlobal['POST'][$htmlName]) > 0))
				$add .= " AND (" . $fieldName . " = '" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST'][$htmlName]) . "') ";
		}

		// Erstelle Query
		$query = "SELECT * FROM `centron_mand_ref` WHERE 1 " . $add . "ORDER BY personenkonto";

		return $query;

	}    // END	private function createQueryForSearchMandat()










	// Initial Methode für Neuer Eintrag
	private function initialHandelNewMandatData()
	{

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

		return $goToPage;

	}    // END private function initialHandelNewMandatData()










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
		if (($getSubAction == 'searchEdit') || ($getSubAction == 'newMandat')) {
			// customerID ggf. Leerzeichen entfernen ... Methode ist in der CoreBase-Klasse
			$this->coreGlobal['POST']['customerID'] = $this->cleanSpaceInString($this->coreGlobal['POST']['customerID']);

			// mandatNumber ggf. Leerzeichen entfernen ... Methode ist in der CoreBase-Klasse
			$this->coreGlobal['POST']['mandatNumber'] = $this->cleanSpaceInString($this->coreGlobal['POST']['mandatNumber']);

			// IBAN ggf. Leerzeichen entfernen ... Methode ist in der CoreBase-Klasse
			$this->coreGlobal['POST']['IBAN'] = $this->cleanSpaceInString($this->coreGlobal['POST']['IBAN']);

			// BIC ggf. Leerzeichen entfernen ... Methode ist in der CoreBase-Klasse
			$this->coreGlobal['POST']['BIC'] = $this->cleanSpaceInString($this->coreGlobal['POST']['BIC']);

			return true;

		}

		return false;

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

		// Auswahl: Suchen / Bearbeiten
		if ($getSubAction == 'searchEdit') {
			// Seiten - Unterscheidung
			switch ($getPageNumber) {
				case 1:
					// User-Eingabe erwartet
					$this->coreGlobal['Load']['FramesetBody'] = 'adminMandat/body/adminMandatBodySetFrame.inc.php';        // Setze zu ladendes Body Frameset
					$this->coreGlobal['Load']['IncludeBody'] = 'adminMandatSearchBody.inc.php';                    // Setze zu ladendes Include in der Body Frameset
					break;

				default:
					break;
			}

			return true;
		}



		// Auswahl: Neuer Eintrag (Mandat anlegen)
		if ($getSubAction == 'newMandat') {

			// Seiten - Unterscheidung
			switch ($getPageNumber) {
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




}   // END class FileImport extends CoreExtends