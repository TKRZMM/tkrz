<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.04.2016
 * Time: 14:41
 */

namespace fileImport;


use classes\core\CoreExtends;


class FileImportCentron extends CoreExtends
{

	// Objekt Handler aus dem Core - Klassen - System.
	public $myDynObj;

	// Globale Variable aus der Start-Klasse.
	public $coreGlobal;


	// Daten aus der Import-Datei
	public $myImportFile;










	// FileImport constructor.
	function __construct($flagUseGlobalCoreClassObj = true)
	{

		parent::__construct();

	}   // END function __construct(...)



	// Initial Methode für den Import der Stammdaten!!!
	//
	// Achtung!!!
	// Mehtoden-Name wird bei Aufruf dynamisch zusammengesetzt:
	// 'fileImport' . {fileUploadDirName} <- Aus DB-Tabelle source_typ
	function fileImportbaseData()
	{

		// Lese CSV - bzw. Import-Datei ein
		$this->readImportFile();


		// Stammdaten in Datenbank schreiben
		$this->insertIntoDBBaseData();

		return true;

	}    // END function fileImportbaseData()










	// Lese CSV - bzw. Import-Datei ein
	private function readImportFile()
	{

		// Datei einlesen
		$preData = file($this->coreGlobal['curFilePath']);

		$myNewData = array();
		$myData = array();

		// Array aufbereiten
		foreach($preData as $newLine) {
			$myNewData[][0] = $newLine;
		}
		$Data = $myNewData;


		foreach($Data as $index => $row) {

			// Centron Buchungsdaten?
			if (($this->coreGlobal['curSourceTypeID'] == '2') && ($this->coreGlobal['curSourceSystemID'] == '2')) {
				$eachValueArray = str_getcsv($row[0], "\t");
			}
			else {
				$eachValueArray = str_getcsv($row[0], ";");
			}

			$myData[$index] = $eachValueArray;
		}

		// Speichere csv - Daten zur weiteren Verarbeitung in der globalen - Klassen - Variable
		$this->coreGlobal['ImportValue'] = $myData;

		return true;

	}    // END private function readImportFile()










	// Stammdaten in Datenbank schreiben
	private function insertIntoDBBaseData()
	{

		// Die erste Reihe in der .csv - Datei ist eine "Ueberschrift"?
		$skipHeadline = false;

		// Setting in welcher Spalte steht was?
		$setRowKDNummer = 0;
		$setRowName1 = 1;
		$setRowStrasseHnr = 2;
		$setRowPLZ = 3;
		$setRowOrt = 4;
		$setRowTelefon = 5;
		$setRowEmail = 6;
		$setRowZahlungstyp = 7;
		$setIBAN = 8;
		$setSWIFT = 9;
		$setLandCode = 10;
		$setLandName = 11;
		$setBKLZ = 12;
		$setBankNr = 13;

		$zeilen = $this->coreGlobal['ImportValue'];
		$downloadLink = $this->coreGlobal['curDownloadLink'];
		$IDt = $this->coreGlobal['curSourceTypeID'];
		$IDs = $this->coreGlobal['curSourceSystemID'];


		$cnt_kunden = 0;
		$errorArray = array();


		// Tabelle leeren!
		$this->query('TRUNCATE TABLE `basedata_centron`');

		foreach($zeilen as $kunde) {
			$daten['errorArray']['Kd.-Nr.'] = array();

			// Headline in Rohdatei? Wenn ja, überspringe ich die erste Zeile
			if (($skipHeadline) && ($cnt_kunden == 0)) {
				$skipHeadline = false;
				continue;
			}


			//TODO WORKAROUND für 3 kundennumern... keine Anschriftdaten!
			$tmpKdNr = trim($kunde[$setRowKDNummer]);
			if (($tmpKdNr == '10148') || ($tmpKdNr == '10371') || ($tmpKdNr == '10131')) {

				continue;
			}

			if (trim($kunde[$setRowKDNummer]) == "") {
				continue;
			}



			// Strassenstring auseinandernehmen
			if (!isset($kunde[$setRowStrasseHnr])) {
				$strassenname = '';
				$hausnummer = '';
				$hausnummerzusatz = '';
				$daten['errorArray']['Kd.-Nr.'] = $kunde[$setRowKDNummer];
			}
			else {
				$strassenname = trim($kunde[$setRowStrasseHnr]);
				$hausnummer = "";
				$hausnummerzusatz = "";
				$search = '/([^\d]+)(\d+)?([^\d]+)?/i';
				if (preg_match_all($search, $strassenname, $result)) {
					$strassenname = trim($result[1][0]);
					$hausnummer = trim($result[2][0]);
					$hausnummerzusatz = trim($result[3][0]);
				}
			}



			// TODO ELEGANTER Datensatz bei Doppel abfangen
			if (strlen($strassenname) < 2) {
				continue;
			}

			// Escapen für DB - Insert z.B. bei: Up'n Nien Esch
			$strassenname = addslashes($strassenname);


			$cnt_kunden++;


			if (!isset($kunde[$setRowPLZ])) {
				$PLZ = '';
				$daten['errorArray']['Kd.-Nr.'] = $kunde[$setRowKDNummer];
			}
			else {
				$PLZ = trim($kunde[$setRowPLZ]);
			}


			if (!isset($kunde[$setRowOrt])) {
				$Ort = '';
				$daten['errorArray']['Kd.-Nr.'] = $kunde[$setRowKDNummer];
			}
			else {
				$Ort = trim($kunde[$setRowOrt]);
			}


			if (!isset($kunde[$setRowTelefon])) {
				$Telefon = '';
				$daten['errorArray']['Kd.-Nr.'] = $kunde[$setRowKDNummer];
			}
			else {
				$Telefon = trim($kunde[$setRowTelefon]);
			}


			if (!isset($kunde[$setRowEmail])) {
				$Email = '';
				$daten['errorArray']['Kd.-Nr.'] = $kunde[$setRowKDNummer];
			}
			else {
				$Email = trim($kunde[$setRowEmail]);
			}

			// Calar neu

			if (!isset($kunde[$setIBAN]))
				$IBAN = '';
			else
				$IBAN = trim($kunde[$setIBAN]);


			if (!isset($kunde[$setLandCode]))
				$Laendercode = '';
			else
				$Laendercode = trim($kunde[$setLandCode]);


			if (!isset($kunde[$setBankNr]))
				$Kontonummer = '';
			else
				$Kontonummer = trim($kunde[$setBankNr]);


			if (!isset($kunde[$setBKLZ]))
				$BLZ = '';
			else
				$BLZ = trim($kunde[$setBKLZ]);


			if (!isset($kunde[$setSWIFT]))
				$BIC = '';
			else
				$BIC = trim($kunde[$setSWIFT]);



			// Anschriftsname
			$anschrifts_name1 = trim($kunde[$setRowName1]);
			$anschrifts_name2 = "";
			if (strlen($anschrifts_name1) > 35) {
				$anschrifts_name2 = substr($anschrifts_name1, 35);
				$anschrifts_name1 = substr($anschrifts_name1, 0, 35);
			}


			$name1 = trim($kunde[$setRowName1]);
			$name2 = "";
			if (strlen($name1) > 30) {
				$name2 = substr($name1, 30);
				$name1 = substr($name1, 0, 30);
			}

			$search = '/,$/i';
			if (preg_match_all($search, $name1, $result)) {
				$newValue = '';
				$name1 = preg_replace($search, $newValue, $name1);
			}

			$search = '/,$/i';
			if (preg_match_all($search, $name2, $result)) {
				$newValue = '';
				$name2 = preg_replace($search, $newValue, $name2);
			}


			if (count($daten['errorArray']['Kd.-Nr.']) > 0) {
				$errorArray[] = $daten['errorArray'];
			}


			$personenkonto = trim($kunde[$setRowKDNummer]);    // Personenkonto sprich Kundennummer


			// Welches System soll genutzt werden (das Alte nur mit SZ oder das Neue mit SZ und BL als Zahlart)
			if ($_SESSION['Cfg']['Default']['Centron']['ZahlungsartOldNew'] == 'new') {

				// Zahlungsart Lastschrift oder Überweisung?
				if (strlen($BIC) > 0)
					$zahlungsart = $_SESSION['Cfg']['Default']['Centron']['ZahlungsartBL'];
				else
					$zahlungsart = $_SESSION['Cfg']['Default']['Centron']['Zahlungsart'];

			}
			else {
				$zahlungsart = $_SESSION['Cfg']['Default']['Centron']['Zahlungsart'];
			}



			$dynInsertQuery = "(
                                `userID`,
                                `Personenkonto`,
                                `Name1`,
                                `Name2`,
                                `Sammelkonto`,
                                `Laendercode`,
                                `BLZ`,
                                `BIC`,
                                `Kontonummer`,
                                `IBAN`,
                                `Zahlungsart`,
                                `Anschrift_Name1`,
                                `Anschrift_Name2`,
                                `Anschrift_PLZ`,
                                `Anschrift_Ort`,
                                `Anschrift_Strasse`,
                                `Anschrift_Hausnummer`,
                                `Zusatzhausnummer`,
                                `Telefon`,
                                `Email`
                                ) VALUES (
                                '" . $_SESSION['Login']['userID'] . "',
                                '" . $personenkonto . "',
                                '" . $name1 . "',
                                '" . $name2 . "',
                                '" . $_SESSION['Cfg']['Default']['Centron']['Sammelkonto'] . "',
                                '" . $Laendercode . "',
                                '" . $BLZ . "',
                                '" . $BIC . "',
                                '" . $Kontonummer . "',
                                '" . $IBAN . "',
                                '" . $zahlungsart . "',
                                '" . $anschrifts_name1 . "',
                                '" . $anschrifts_name2 . "',
                                '" . $PLZ . "',
                                '" . $Ort . "',
                                '" . $strassenname . "',
                                '" . $hausnummer . "',
                                '" . $hausnummerzusatz . "',
                                '" . $Telefon . "',
                                '" . $Email . "'
                                )
                                ";

			$dynUpdateQuery = "`userID`                 = '" . $_SESSION['Login']['userID'] . "',
                               `Name1`                  = '" . $name1 . "',
                               `Name2`                  = '" . $name2 . "',
                               `Sammelkonto`            = '" . $_SESSION['Cfg']['Default']['Centron']['Sammelkonto'] . "',
                               `Laendercode`            = '" . $Laendercode . "',
                               `BLZ`                    = '" . $BLZ . "',
                               `BIC`                    = '" . $BIC . "',
                               `Kontonummer`            = '" . $Kontonummer . "',
                               `IBAN`                   = '" . $IBAN . "',
                               `Zahlungsart`            = '" . $zahlungsart . "',
                               `Anschrift_Name1`        = '" . $anschrifts_name1 . "',
                               `Anschrift_Name2`        = '" . $anschrifts_name2 . "',
                               `Anschrift_PLZ`          = '" . $PLZ . "',
                               `Anschrift_Ort`          = '" . $Ort . "',
                               `Anschrift_Strasse`      = '" . $strassenname . "',
                               `Anschrift_Hausnummer`   = '" . $hausnummer . "',
                               `Zusatzhausnummer`       = '" . $hausnummerzusatz . "',
                               `Telefon`                = '" . $Telefon . "',
                               `Email`                  = '" . $Email . "'
            ";


			//TODO Eintrag nur wenn kein Fehler passiert ist... das fange ich hier nicht ab!
			// DB Eintrag erstellen oder Updaten (Query erstellen)!
			$query = "INSERT INTO basedata_centron " . $dynInsertQuery . " ON DUPLICATE KEY UPDATE " . $dynUpdateQuery;

			// DB Eintrag erstellen oder Updaten!
			$this->query($query);

		}   // END foreach ($zeilen as $kunde){

		return true;

	}    // END private function insertIntoDBBaseData()


}   // END class FileImportCentron