<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.04.2016
 * Time: 10:59
 */

namespace fileImport;


use classes\core\CoreExtends;
use classes\system\SystemLayout;


class FileImport extends CoreExtends
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