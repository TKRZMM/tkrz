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
			if ((isset($this->coreGlobal['GET']['subAction'])) && ($this->coreGlobal['GET']['subAction'] == 'newMandat')) {
				$goToPage = $this->initialHandelNewMandatData();
			}


			// Suchen Bearbeiten?
			if ((isset($this->coreGlobal['GET']['subAction'])) && ($this->coreGlobal['GET']['subAction'] == 'searchEdit')) {
				$goToPage = $this->initialHandelSearchEditData();
			}


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

		// Seite 1 - Suchmaske ausgeben
		if ((!isset($this->coreGlobal['POST']['centron_mand_refID'])) && (!$this->handelSearchData())) {
			$goToPage = 1;
		}

		// Seite 2 - Resultat der Suche ausgeben und Datensatz-Auswahl ausgeben
		elseif ((!isset($this->coreGlobal['POST']['centron_mand_refID'])) || ($this->coreGlobal['POST']['centron_mand_refID'] < 1)) {
			// Datensatz Auswahl - Seite
			$goToPage = 2;
		}

		// Seite 3 - Daten bearbeitet?
		elseif ((isset($this->coreGlobal['POST']['callUpdate'])) && ($this->coreGlobal['POST']['callUpdate'] == 'yes')) {

			// Datensatz löschen?
			if ((isset($this->coreGlobal['POST']['delMandat'])) && ($this->coreGlobal['POST']['delMandat'] == 'yes')) {
				// Löschen DONE
				$this->delDataSetByID($this->coreGlobal['POST']['centron_mand_refID']);

				// Speicher freigeben
				unset($_POST);
				unset($this->coreGlobal['POST']);

				$this->addMessage('Mandat erfolgreich gelöscht!', 'Die Mandat-Nummer wird in künftigen Exports nicht mehr verwendet.', 'Erfolg', 'Suchen / Bearbeiten');

				return 1;
			}

			if (!$this->handelUpdateData()) {
				$this->coreGlobal['tmp']['forceReadDB'] = 'yes';

				// Datensatz einlesen:
				$this->doReadDBForSingleData($this->coreGlobal['POST']['centron_mand_refID']);

				$goToPage = 3;
			}
			else {
				// UPDATE DONE
				$this->addMessage('Mandat erfolgreich bearbeitet!', 'Die geänderten Mandats-Daten wurden erfolreich übernommen und werden bei künftigen Exports verwendet.', 'Erfolg', 'Suchen / Bearbeiten');

				// Datensatz einlesen:
				$this->doReadDBForSingleData($this->coreGlobal['POST']['centron_mand_refID']);

				$goToPage = 3;
			}
		}

		else {
			// Datensatz einlesen:
			$this->doReadDBForSingleData($this->coreGlobal['POST']['centron_mand_refID']);

			// Datensatz - Edit - Seite (single Datensatz)
			$goToPage = 3;
		}


		return $goToPage;

	}    // END private function initialHandelNewMandatData()










	// Löscht einen Mandat-Eintrag
	private function delDataSetByID($getID)
	{

		$query = "DELETE FROM `centron_mand_ref` WHERE centron_mand_refID = '" . $getID . "' LIMIT 1";

		$this->query($query);

		return true;
	}    // END private function delDataSetByID($getID)










	private function handelUpdateData()
	{

		// Leerzeichen aus den Eingaben entfernen lassen
		$this->myCleanSpaceInString($this->coreGlobal['GET']['subAction']);


		// Rquire - Eingaben abfangen
		if (!$this->checkRequireEntrysForNewMandat())
			return false;

		// Prüfen auf Doppeleintrag
		if ($this->checkForDoubleEntryOnUpdateMandat())
			return false;

		// Alles ok ... lasse jetzt die Daten als neuen Eintrag in die DB schreiben
		if (!$this->writeUpdateMandatToDB())
			return false;

		return true;

	}    // END	private function handelUpdateData()










	// Update eine Mandats-Referenz-Nr.
	private function writeUpdateMandatToDB()
	{

		// TODO Überlegung ob ich über den SEPA - Status auch den Status des Mandat-Eintrages steuer... das ist für den Export wichtig!
		// $activeStatus = 'no';
		// if ($this->coreGlobal['POST']['lsStatus'] == 'Aktiv')
		// $activeStatus = 'yes';

		$activeStatus = 'yes';

		$query = "UPDATE centron_mand_ref SET 	`personenkonto` = '" . $this->coreGlobal['POST']['customerID'] . "',
												`mandatsnummer` = '" . $this->coreGlobal['POST']['mandatNumber'] . "',
												`activeStatus` = '" . $activeStatus . "',
												`lsArt` = '" . $this->coreGlobal['POST']['lsArt'] . "',
												`lsType` = '" . $this->coreGlobal['POST']['lsType'] . "',
												`lsStatus` = '" . $this->coreGlobal['POST']['lsStatus'] . "',
												`dateOfExpire` = '" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST']['dateOfExpire']) . "',
												`createdOn` = '" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST']['createdOn']) . "',
												`gotMandatOn` = '" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST']['gotMandatOn']) . "',
												`dateOfFirstUse` = '" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST']['dateOfFirstUse']) . "',
												`useUntil` = '" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST']['useUntil']) . "',
												`recalledOn` = '" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST']['recalledOn']) . "',
												`IBAN` = '" . $this->coreGlobal['POST']['IBAN'] . "',
												`BIC` = '" . $this->coreGlobal['POST']['BIC'] . "'
										  WHERE `centron_mand_refID` = '" . $this->coreGlobal['POST']['centron_mand_refID'] . "' LIMIT 1
		";

		$this->query($query);

		return true;

	}    // END private function writeUpdateMandatToDB()










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


		return true;

	}    // END private function handelNewMandatData()










	// Methode führt Such-Query aus und speichert das Ergebnis in der coreGlobal unter DBResult
	private function doReadDBForSingleData($getCentron_mand_refID)
	{

		$query = "SELECT * FROM `centron_mand_ref` WHERE centron_mand_refID = '" . $getCentron_mand_refID . "' LIMIT 1";

		$result = $this->query($query);
		$num_rows = $this->num_rows($result);

		if ($num_rows == '1') {

			// Resultat in der coreGlobal speichern, damit es auf der Auswahlseite ausgegeben und verarbeitet werden kann.
			$this->coreGlobal['dbResult'] = $result;

			return true;
		}

		$this->free_result($result);

		$this->addMessage('Kein Datensatz gefunden!', 'Zu dem gewählten Datensatz sind keine Daten gespeichert.', 'Info', 'Suchen / Bearbeiten');

		return false;

	}    // END private function doDBSearch($query)










	// Methode führt Such-Query aus und speichert das Ergebnis in der coreGlobal unter DBResult
	private function doDBSearch($query)
	{

		$result = $this->query($query);
		$num_rows = $this->num_rows($result);

		if ($num_rows >= '1') {

			// Resultat in der coreGlobal speichern, damit es auf der Auswahlseite ausgegeben und verarbeitet werden kann.
			$this->coreGlobal['dbResult'] = $result;

			return true;
		}

		$this->free_result($result);

		$this->addMessage('Keine Daten gefunden!', 'Zu den gesuchten Angaben sind keine Daten gespeichert.', 'Info', 'Suchen / Bearbeiten');

		return false;

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
							  'useUntil'       => 'useUntil',
							  'recalledOn'     => 'recalledOn',
							  'IBAN'           => 'IBAN',
							  'BIC'            => 'BIC'
		);

		// Durchlauf der möglichen Variable ... wenn gesetzt werden sie in die Query integriert.
		foreach($htmlToDBName as $htmlName => $fieldName) {
			if ((isset($this->coreGlobal['POST'][$htmlName])) && (strlen($this->coreGlobal['POST'][$htmlName]) > 0)) {

				if ((isset($this->coreGlobal['POST']['likeSearchEnable'])) && ($this->coreGlobal['POST']['likeSearchEnable'] == 'on'))
					$add .= " AND (" . $fieldName . " LIKE '%" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST'][$htmlName]) . "%') ";
				else
					$add .= " AND (" . $fieldName . " = '" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST'][$htmlName]) . "') ";

			}
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
												`useUntil`,
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
											'" . $this->formatDateForMySQLWithNoSlash($this->coreGlobal['POST']['useUntil']) . "',
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
			$this->addMessage('Mandat und/oder Kunde bereits vorhanden!', 'Die vorhanden Daten können unter: "Suchen / Bearbeiten" eingesehen und bearbeitet werden.', 'Fehler', 'Neuer Eintrag');

			$this->free_result($result);

			return true;
		}

		$this->free_result($result);

		return false;

	}    // END	private function checkForDoubleEntryOnNewMandat()










	// Prüft ob eine Mandats-Nummer oder eine Centron - Kunden-Nummer schon in der Datenbank (Mand-Ref) vorhanden ist.
	private function checkForDoubleEntryOnUpdateMandat()
	{

		// Parameter für getQuery setzen
		$paramArray = array('customerID'         => $this->coreGlobal['POST']['customerID'],
							'mandatNumber'       => $this->coreGlobal['POST']['mandatNumber'],
							'centron_mand_refID' => $this->coreGlobal['POST']['centron_mand_refID']);

		// Query holen
		$query = $this->getQuery('getMandatCheckOnUpdate', $paramArray);

		// Query ausführen
		$result = $this->query($query);

		if ($this->num_rows($result) == 1) {

			$row = $result->fetch_object();
			$selCentronMandRefIF = $row->centron_mand_refID;

			// Message für Fehler aufgetreten
			$this->addMessage('Mandat und/oder Kunde bereits vorhanden!', 'Die vorhanden Daten können unter: "Suchen / Bearbeiten" eingesehen und bearbeitet werden.', 'Fehler', 'Suchen / Bearbeiten');

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

				case 2:
					// Datensatz Auswahl
					$this->coreGlobal['Load']['FramesetBody'] = 'adminMandat/body/adminMandatBodySetFrame.inc.php';        // Setze zu ladendes Body Frameset
					$this->coreGlobal['Load']['IncludeBody'] = 'adminMandatSelectDataBody.inc.php';                    // Setze zu ladendes Include in der Body Frameset
					break;

				case 3:
					// Datensatz Bearbeiten
					$this->coreGlobal['Load']['FramesetBody'] = 'adminMandat/body/adminMandatBodySetFrame.inc.php';        // Setze zu ladendes Body Frameset
					$this->coreGlobal['Load']['IncludeBody'] = 'adminMandatEditDataBody.inc.php';                    // Setze zu ladendes Include in der Body Frameset
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