<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 23.03.2016
 * Time: 16:49
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
namespace fileUpload;


use classes\core\CoreExtends;


/**
 * Class FileUpload
 *
 * Die Klasse handelt den regulären Datei-Upload via HTML-Format ab.
 *
 * WICHTIG:
 * Benötigt werden insbesondere für die Ordnerstruktur:
 *        ... $this->coreGlobal['GET']['subAction'] ... z.B. baseDate oder bookingData
 *        ... $this->coreGlobal['GET']['valueAction'] ... z.B. Centron
 *
 * Der erzeugte Pfad ist dann z.B.:
 * /{WebRoot}/uploads/baseData/Centron/{Jahr}/{Monat}/{Tag}/...Datei
 *
 * @package fileUpload
 */
class FileUpload extends CoreExtends
{

	/**
	 * Objekt Handler aus dem Core - Klassen - System.
	 *
	 * @var
	 */
	public $myDynObj;


	/**
	 * Globale Variable aus der Start-Klasse.
	 *
	 * @var
	 */
	public $coreGlobal;










	/**
	 * FileUpload constructor.
	 *
	 * @param bool $flagUseGlobalCoreClassObj
	 */
	function __construct($flagUseGlobalCoreClassObj = true)
	{

		parent::__construct();

	}   // END function __construct(...)










	/**
	 * Initial Methode zur Abhandlung von "Datei - Upload".
	 *
	 * ... Setze das Body-Frameset auf das File-Upload-Frameset
	 *        ... Das Frameset beinhaltet die html-Form zum Upload einer Datei
	 *
	 * ... Übergebe bzw. überprüfe ob eventuell schon ein Dateup-Upload vorliegt.
	 *        ... Das Form-Frameset wird dann resettet
	 *
	 * Aufruf aus Klasse: SystemAction.class.php
	 */
	public function initialFileUpload()
	{

		// Setze zu ladendes Body Frameset
		$this->coreGlobal['Load']['FramesetBody'] = 'fileUpload/body/fileUploadBodySetFrame.inc.php';


		// Prüfung ob Datei hochgeladen wurde, oder hätte hochgeladen werden können:
		$this->checkFileUploadFullHandling();

	}    // END public function initialFileUpload()










	/**
	 * Methode handelt einen möglichen Datei - Upload ab.
	 *
	 * ... Prüfe ob es ein Datei-Upload gegeben haben könnte
	 *        ... wenn ja, Weiterleitung -> moveUploadedFileFullHandling
	 */
	private function checkFileUploadFullHandling()
	{

		// Prüfung ob es einen Upload gegeben haben könnte
		if ((isset($this->coreGlobal['POST']['callAction'])) && ($this->coreGlobal['POST']['callAction'] == 'fileUpload')) {


			// FileUpload via Post liegt vor... haben wir auch eine Datei?
			if ((isset($_FILES['file'])) && (strlen($_FILES['file']['name']) > 0)) {

				// Eventuell ein Fehler aufgetreten?
				if ($_FILES['file']['error'] == 0) {

					// Nein kein Fehler, versuche jetzt die Datei in den Zielordner zu verschieben / moven ... übergebe an Methode...
					$this->moveUploadedFileFullHandling();
				}
				else {
					// Fehler bei Datei-Upload... gebe diese Information an den User aus.
					$explain = 'Überprüfen Sie die Rechte auf dem Webserver, oder wenden Sie sich an den zuständigen Administrator.<br>Server Error Code:<br>' . $_FILES['file']['error'];
					$this->addMessage('Fehler bei Datei Upload!', 'Die gewünschte Datei konnte nicht auf den Server hochgeladen werden.', 'Fehler', 'File Upload', $explain);

				}    // END // Eventuell ein Fehler aufgetreten?

			}    // END // FileUpload via Post liegt vor... haben wir auch eine Datei?


		}    // END // Prüfung ob es einen Upload gegeben haben könnte

	}    // END private function checkFileUploadFullHandling()










	/**
	 * Methode verschiebt die tmp-Upload-Datei ins Zielverzeichnis...
	 * ... das/die Zielverzeichnisse werden bei Bedarf erzeugt
	 *
	 * ... Prüft ob das Upload-Verzeichnis vorhanden ist
	 *        ... wenn nicht, anlegen lassen
	 *
	 * ... Prüft ob die vorliegende Datei eine reguläre "Upload-Datei" ist
	 *        ... wenn ja, wird die Datei in den Zielordner verschoben
	 *
	 * @return bool
	 */
	private function moveUploadedFileFullHandling()
	{

		// Überpüfe ob Ordner vorhaden ist... wenn nicht... anlegen lassen
		if (!$uploadPath = $this->checkUploadDir($this->coreGlobal['GET']['subAction'], $this->coreGlobal['GET']['valueAction']))
			return false;

		// Damit ein Hack (z.B. passwd) zu verhindern gehen wir über die if-Bedingung und PHP-eigene Funktion is_upload_file
		if (is_uploaded_file($_FILES['file']['tmp_name'])) {

			// Erstelle neuen Dateinamen:...
			$origFileName = $_FILES['file']['name'];

			// Format - Beispiel: baseData_Centron_20160324_byUID_001__
			$curDay = date('Ymd');
			$prefixNewFileName = $this->coreGlobal['GET']['subAction'];
			$prefixNewFileName .= '_';
			$prefixNewFileName .= $this->coreGlobal['GET']['valueAction'];
			$prefixNewFileName .= "_";
			$prefixNewFileName .= $curDay;
			$prefixNewFileName .= "_byUID_";
			$prefixNewFileName .= $this->getFormatedNumberWithLeadingNulls($_SESSION['Login']['userID'], 3);
			$prefixNewFileName .= "__";

			// Neuer Filename ist...
			$newFilename = $prefixNewFileName . $origFileName;

			// Voller Pfad inkl. neuer Dateiname
			$fullUploadPath = $uploadPath . '/' . $newFilename;


			// Verschiebe jetzt die tmp-Datei in das eigentliche Zielverzeichnis
			if (move_uploaded_file($_FILES['file']['tmp_name'], $fullUploadPath)) {

				// Hat alles geklappt ... Info-Ausgabe
				// $expain = 'Neuer Ordner und Dateiname:<br>' . $fullUploadPath;
				$this->addMessage('Datei Upload erfolgreich!', 'Die Datei: "' . $origFileName . '"" wurde erfolgreich hochgeladen.', 'Erfolg', 'File Upload');

				// Reset zu ladendes Body Frameset
				unset($this->coreGlobal['Load']['FramesetBody']);

				// Reset GET und POST in der coreGlobal
				unset($this->coreGlobal['GET']);
				unset($this->coreGlobal['POST']);
			}
			else {

				// Fehler aufgetreten
				$explain = 'Fehler Code: ' . $_FILES['file']['error'];
				$this->addMessage('Fehler bei Datei Upload!', 'Die gewünschte Datei konnte nicht auf den Server hochgeladen werden.', 'Fehler', 'File Upload', $explain);

			}

		}
		else {

			$this->addMessage('Fehler bei Datei Upload!', 'Die gewünschte Datei konnte nicht auf den Server hochgeladen werden.', 'Fehler', 'File Upload', 'Die Datei ist keine gültie "Upload-Datei"!');

			return false;

		}

		return true;

	}    // END private function moveUploadedFileFullHandling()










	/**
	 * Methode lässt die Upload-Verzeichnisstruktur step-by-step überprüfen.
	 *        ... Strukur Beispiel: baseData/Centron/{YYYY}/{MM}/{DD}/
	 *                              bookingData/Centron/{YYYY}/{MM}/{DD}/
	 *
	 * ... das jeweilige Verzeichnis wird im Bedarfsfall entsprechend angelegt.
	 *
	 * @param $category
	 * @param $systemName
	 *
	 * @return bool|string
	 */
	private function checkUploadDir($category, $systemName)
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










	/**
	 *  Methode prüft ob ein angegebenes Verzeichnis vorhanden ist und legt diese bei Bedarf an.
	 *
	 * @param $getPath
	 *
	 * @return bool
	 */
	private function checkCreatePath($getPath)
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


}   // END class FileUpload
