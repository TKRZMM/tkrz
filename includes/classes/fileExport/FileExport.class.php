<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.04.2016
 * Time: 10:59
 */

namespace fileExport;


use classes\core\CoreExtends;
use classes\system\SystemLayout;


class FileExport extends CoreExtends
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










	/**
	 * Initial Methode zur Abhandlung von "Datei - Export".
	 *
	 * ... Setze das Body-Frameset auf das File-Export-Frameset
	 *        ... Das Frameset beinhaltet die erste html-Form (Auswahl der zu exportierenden Daten)
	 *
	 * ... Übergebe bzw. der Auswahl an die Export-Methode.
	 *        ... Das Form-Frameset wird dann resettet
	 *
	 * Aufruf aus Klasse: SystemAction.class.php
	 */
	public function initialFileExport()
	{

		// Wurden Daten für den Export ausgewählt?
		$boolGotUserSelection = $this->checkDataSelection();

		// Keine Daten für den Import ausgewählt... Zeige Seite 1 (Datenauswahl)
		if (!$boolGotUserSelection) {

			// Seite 1 ... User Auswahl benötigt ... Auswahl der zu exportierenden Daten ausgeben
			$this->createPageOneFileExport();
		}
		else {

			// Aufzurufender Klassen-Name (MIT Namespace!!!):
			$callClass = 'fileExport\FileExport' . $this->coreGlobal['GET']['valueAction'];

			// Aufzurunfende Methode:
			$callMethod = 'fileExport' . $this->coreGlobal['GET']['subAction'];

			// Rufe Klasse auf
			$hFileExport = new $callClass();

			// Rufe Methode der Klasse auf
			$hFileExport->$callMethod();
		}

		return true;

	}    // END public function initialFileExport()










	// Wurden Daten für den Export ausgewählt? ... Wenn nicht... dann zeige Datenauswahl
	private function checkDataSelection()
	{

		if ((isset($this->coreGlobal['POST']['callAction'])) && ($this->coreGlobal['POST']['callAction'] == 'dbExport')) {

			if ((isset($this->coreGlobal['POST']['dbExportConfirmed'])) && ($this->coreGlobal['POST']['dbExportConfirmed'] == 'true')) {

				// Daten wurdn ausgewählt ... Start Export
				return true;

			}

		}

		return false;

	}    // END private function checkDataSelection()










	// Seite 1 ... User Auswahl benötigt ... Auswahl der zu importierenden Daten ausgeben
	private function createPageOneFileExport()
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


		// Parameter setzen
		$paramArray = array('sourceTypeID'   => $sourceTypeID,
							'sourceSystemID' => $sourceSystemID);


		// Informations-Daten einlesen
		// Stammdaten oder Buchungsdaten
		if ($sourceTypeID == '1') {    // Stammdaten
			$this->coreGlobal['informationArray'] = $this->getDatasetInformationForBaseData($paramArray);    // if ! Fehler abfangen
			$IncludeFramesetBody = 'fileExport/body/fileExportBodySetFrame.inc.php';
			$IncludeBody = 'fileExportGetUserDataSelectionBaseDataBody.inc.php';
		}
		elseif ($sourceTypeID == '2') {    // Buchungsdaten
			$this->coreGlobal['informationArray'] = $this->getDatasetInformationForBookingData($paramArray);    // if ! Fehler abfangen
			$IncludeFramesetBody = 'fileExport/body/fileExportBodySetFrame.inc.php';
			$IncludeBody = 'fileExportGetUserDataSelectionBookingDataBody.inc.php';
		}
		else {
			$IncludeFramesetBody = 'standard/body/stdBodySetFrame.inc.php';
			$IncludeBody = 'stdBody.inc.php';
		}


		// Setze zu ladendes Body Frameset
		$this->coreGlobal['Load']['FramesetBody'] = $IncludeFramesetBody;

		// Setze zu ladendes Include in der Body Frameset
		$this->coreGlobal['Load']['IncludeBody'] = $IncludeBody;

		return true;

	}    // END private function createPageOneFileExport()










	// Informationsdaten für Benutzer-Stammdaten-Auswahl einlesen
	private function getDatasetInformationForBaseData($getParamArray)
	{

		$informationArray = array();

		// Datensätze ermitteln
		// Aktuellen Tabellen(Pre-Teil)-Namen holen
		$paramArray['FROM'] = 'source_system';
		$paramArray['WHERE'] = 'sourceSystemID';
		$paramArray['SEARCH'] = $getParamArray['sourceSystemID'];
		$query = $this->getQuery('getActiveSourceXDataByX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if ($num_rows != 1)
			return false;

		$row = $result->fetch_object();
		$preDBTableName = $row->sourceSystemDBName;
		$this->free_result($result);


		// Aktuellen Tabellen(Post-Teil)-Namen holen
		unset($paramArray);
		$paramArray['FROM'] = 'source_type';
		$paramArray['WHERE'] = 'sourceTypeID';
		$paramArray['SEARCH'] = $getParamArray['sourceTypeID'];
		$query = $this->getQuery('getActiveSourceXDataByX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if ($num_rows != 1)
			return false;

		$row = $result->fetch_object();
		$postDBTableName = $row->dbPostName;
		$this->free_result($result);


		// Jetzt Summe der Datensätze ermitteln
		unset($paramArray);
		$paramArray['FROM'] = $preDBTableName . '_' . $postDBTableName;
		$query = $this->getQuery('getSumFromTableX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if ($num_rows != 1)
			$numDatasets = 0;
		else {
			$row = $result->fetch_object();
			$numDatasets = $row->SUM;
		}
		$this->free_result($result);
		$informationArray['numDatasets'] = $numDatasets;


		// Mandatsreferenzen
		$query = $this->getQuery('getSumMandateFromTableX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if ($num_rows != 1)
			$numMandate = 0;
		else {
			$row = $result->fetch_object();
			$numMandate = $row->SUM;
		}
		$this->free_result($result);
		$informationArray['numMandate'] = $numMandate;



		// Neuster Datensatz
		$query = $this->getQuery('getNewestLastUpdateFromTableX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if ($num_rows != 1)
			$lastDataset = '-';
		else {
			$row = $result->fetch_object();
			$lastDataset = $row->lastUpdate;
		}
		$this->free_result($result);
		$informationArray['lastDataset'] = $lastDataset;



		// Ältester Datensatz
		$query = $this->getQuery('getOldestLastUpdateFromTableX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if ($num_rows != 1)
			$oldestDataset = '-';
		else {
			$row = $result->fetch_object();
			$oldestDataset = $row->lastUpdate;
		}
		$this->free_result($result);
		$informationArray['oldestDataset'] = $oldestDataset;



		// Sammelkonten
		$query = $this->getQuery('getSammelkontenFromTableX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if (!$num_rows >= '1') {
			$Sammelkonten[] = '';
		}
		else {
			while ($row = $result->fetch_object()) {
				$Sammelkonten[] = $row->Sammelkonto;
			}
		}
		$this->free_result($result);
		$informationArray['Sammelkonten'] = $Sammelkonten;



		// Zahlungsarten
		$query = $this->getQuery('getZahlungsartenFromTableX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if (!$num_rows >= '1') {
			$Zahlungsarten[] = '';
		}
		else {
			while ($row = $result->fetch_object()) {
				$Zahlungsarten[] = $row->Zahlungsart;
			}
		}
		$this->free_result($result);
		$informationArray['Zahlungsarten'] = $Zahlungsarten;



		// Upload Benutzer
		$query = $this->getQuery('getUploadUserFromTableX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if ($num_rows != 1)
			$uploadUserName = '-';
		else {
			$row = $result->fetch_object();
			$uploadUserName = $row->userName;
		}
		$this->free_result($result);
		$informationArray['uploadUserName'] = $uploadUserName;


		return $informationArray;

	}    // END private function getDatasetInformationForBaseData(...)










	// Informationsdaten für Benutzer-Buchungsdaten-Auswahl einlesen
	private function getDatasetInformationForBookingData($getParamArray)
	{

		$informationArray = array();

		// Datensätze ermitteln
		// Aktuellen Tabellen(Pre-Teil)-Namen holen
		$paramArray['FROM'] = 'source_system';
		$paramArray['WHERE'] = 'sourceSystemID';
		$paramArray['SEARCH'] = $getParamArray['sourceSystemID'];
		$query = $this->getQuery('getActiveSourceXDataByX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if ($num_rows != 1)
			return false;

		$row = $result->fetch_object();
		$preDBTableName = $row->sourceSystemDBName;
		$this->free_result($result);


		// Aktuellen Tabellen(Post-Teil)-Namen holen
		unset($paramArray);
		$paramArray['FROM'] = 'source_type';
		$paramArray['WHERE'] = 'sourceTypeID';
		$paramArray['SEARCH'] = $getParamArray['sourceTypeID'];
		$query = $this->getQuery('getActiveSourceXDataByX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if ($num_rows != 1)
			return false;

		$row = $result->fetch_object();
		$postDBTableName = $row->dbPostName;
		$this->free_result($result);


		// Jetzt Summe der Datensätze ermitteln
		unset($paramArray);
		$paramArray['FROM'] = $preDBTableName . '_' . $postDBTableName;
		$query = $this->getQuery('getSumFromTableX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if ($num_rows != 1)
			$numDatasets = 0;
		else {
			$row = $result->fetch_object();
			$numDatasets = $row->SUM;
		}
		$this->free_result($result);
		$informationArray['numDatasets'] = $numDatasets;


		// ImportDatum
		$paramArray['GROUP'] = 'importDate';
		$query = $this->getQuery('getXGroupByX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if (!$num_rows >= '1') {
			$ImportDatumArray[] = '';
		}
		else {
			while ($row = $result->fetch_object()) {
				$ImportDatumArray[] = $row->importDate;
			}
		}
		$this->free_result($result);
		$informationArray['ImportDatumArray'] = $ImportDatumArray;



		// Rechnungsdatum
		$paramArray['GROUP'] = 'Datum';
		$query = $this->getQuery('getXGroupByX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if (!$num_rows >= '1') {
			$RDatumArray[] = '';
		}
		else {
			while ($row = $result->fetch_object()) {
				$RDatumArray[] = $row->Datum;
			}
		}
		$this->free_result($result);
		$informationArray['RDatumArray'] = $RDatumArray;



		// Erloeskonto
		$paramArray['GROUP'] = 'Erloeskonto';
		$query = $this->getQuery('getXGroupByX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if (!$num_rows >= '1') {
			$Erloeskonten[] = '';
		}
		else {
			while ($row = $result->fetch_object()) {
				$Erloeskonten[] = $row->Erloeskonto;
			}
		}
		$this->free_result($result);
		$informationArray['Erloeskonten'] = $Erloeskonten;



		// Kostenstelle
		$paramArray['GROUP'] = 'Kostenstelle';
		$query = $this->getQuery('getXGroupByX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if (!$num_rows >= '1') {
			$Kostenstellen[] = '';
		}
		else {
			while ($row = $result->fetch_object()) {
				$Kostenstellen[] = $row->Kostenstelle;
			}
		}
		$this->free_result($result);
		$informationArray['Kostenstellen'] = $Kostenstellen;


		// MwST
		$paramArray['GROUP'] = 'MwSt';
		$query = $this->getQuery('getXGroupByX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if (!$num_rows >= '1') {
			$MwSts[] = '';
		}
		else {
			while ($row = $result->fetch_object()) {
				$MwSts[] = $row->MwSt;
			}
		}
		$this->free_result($result);
		$informationArray['MwSts'] = $MwSts;



		// Upload Benutzer
		$query = $this->getQuery('getUploadUserFromTableX', $paramArray);
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);
		if ($num_rows != 1)
			$uploadUserName = '-';
		else {
			$row = $result->fetch_object();
			$uploadUserName = $row->userName;
		}
		$this->free_result($result);
		$informationArray['uploadUserName'] = $uploadUserName;


		return $informationArray;

	}    // END private function getDatasetInformationForBaseData(...)




	function checkUploadDir($category, $systemName)
	{

		$mainUploadPath = $_SESSION['Cfg']['Default']['WebsiteSettings']['MainUploadPath'];

		// Prüfung ob Haupt-Upload-Verzeichnis existiert
		if (!is_dir($mainUploadPath)) {

			// Fehler brauche mindestens den Main - Upload Pfade. ... Gebe Information an User weiter
			$explain = 'Der Haupt-Upload Pfad konnte nicht geöffnet werden, bitte wenden Sie sich an den zuständingen Administrator.<br>Gesuchter Pfad: ' . $mainUploadPath;
			$this->addMessage('Fehler bei Datei Upload!', 'Die gewünschte Datei konnte nicht auf den Server hochgeladen werden.', 'Fehler', 'File Upload', $explain);

			return false;
		}


		// Prüfung Kategorie-Verzeichnis vorhanden?
		$curPath = $mainUploadPath . '/' . $category;
		If (!$this->checkCreatePath($curPath))
			return false;


		// Prüfung System-Verzeichnis vorhanden?
		$curPath = $curPath . '/' . $systemName;
		If (!$this->checkCreatePath($curPath))
			return false;


		// Prüfung auf Jahres-Verzeichnis vorhanden?
		$curPath = $curPath . '/' . date('Y');
		If (!$this->checkCreatePath($curPath))
			return false;


		// Prüfung auf Monats-Verzeichnis vorhanden?
		$curPath = $curPath . '/' . date('m');
		If (!$this->checkCreatePath($curPath))
			return false;


		// Prüfung auf Tages-Verzeichnis vorhanden?
		$curPath = $curPath . '/' . date('d');
		If (!$this->checkCreatePath($curPath))
			return false;


		return $curPath;

	}    // END private function checkUploadDir(...)





	// Methode prüft ob ein angegebenes Verzeichnis vorhanden ist und legt diese bei Bedarf an.
	function checkCreatePath($getPath)
	{

		if (!is_dir($getPath)) {

			if (!mkdir($getPath)) {

				// Fehler beim Versuch einen Ordner zu erstellen ... Gebe Information an User weiter
				$explain = 'Das Verzeichnis konnte nicht erstellt werden, bitte wenden Sie sich an den zuständingen Administrator.<br>Versuch auf Verzeichnis: ' . $getPath;
				$this->addMessage('Fehler bei Verzeichnis-Überprüfung!', 'Das gewünschte Verzeichnis ist nicht vorhanden und konnte nicht erzeugt werden.', 'Fehler', 'Directory Check', $explain);

				return false;
			}

			return true;

		}

		return true;

	}    // END private function checkCreatePath(...)

}   // END class FileExport extends CoreExtends