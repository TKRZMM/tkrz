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
		foreach($preData as $newLine)
			$myNewData[][0] = $newLine;

		$Data = $myNewData;


		foreach($Data as $index => $row) {

			// Centron Buchungsdaten?
			if (($this->coreGlobal['curSourceTypeID'] == '2') && ($this->coreGlobal['curSourceSystemID'] == '2'))
				$eachValueArray = str_getcsv($row[0], "\t");

			else
				$eachValueArray = str_getcsv($row[0], ";");


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
		// $setRowZahlungstyp = 7;
		$setIBAN = 8;
		$setSWIFT = 9;
		$setLandCode = 10;
		// $setLandName = 11;
		$setBKLZ = 12;
		$setBankNr = 13;

		$zeilen = $this->coreGlobal['ImportValue'];
		// $downloadLink = $this->coreGlobal['curDownloadLink'];
		// $IDt = $this->coreGlobal['curSourceTypeID'];
		// $IDs = $this->coreGlobal['curSourceSystemID'];


		$cntKunden = 0;
		$errorArray = array();
		$warningArray = array();


		// Tabelle leeren!
		$this->query('TRUNCATE TABLE `basedata_centron`');

		// Jede Zeile / Kunde durchgehen und entsprechend in die DB speichern
		foreach($zeilen as $kunde) {

			// Headline in Rohdatei? Wenn ja, überspringe ich die erste Zeile
			if (($skipHeadline) && ($cntKunden == 0)) {
				$skipHeadline = false;
				continue;
			}


			// Aktuelle Kundennummer!
			$curKundenNummer = trim($kunde[$setRowKDNummer]);


			// Haben wir eine Kundennummer?
			if (strlen($curKundenNummer) < 1) {
				$this->addMessage('Fehlende Kunden-Nr.', 'Fehlende Kundennummer, hier in " die vorliegenden Daten: "' . $curKundenNummer . '""', 'Warnung', 'Datenbank-Import');
				continue;
			}


			//HARDCODE Kundennummern die übersprungen werden können und wegen fehlerafter Ursprungsdaten nicht verwendet werden
			if (($curKundenNummer == '10148') || ($curKundenNummer == '10371') || ($curKundenNummer == '10131'))
				continue;


			// Strassenstring für Hausnummer und Hausnummer-Zusatz zerlegen
			if (!isset($kunde[$setRowStrasseHnr])) {
				$strassenname = '';
				$hausnummer = '';
				$hausnummerzusatz = '';
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


			// Straßenname sollte jetzt bekannt sein
			if (strlen($strassenname) < 2) {
				$errorArray[$curKundenNummer]['Strasse'] = 'Fehlender Straßenname';
				$this->addMessage('Fehlender Straßenname', 'Fehlender Straßenname bei Kunden-Nr.: ' . $curKundenNummer, 'Warnung', 'Datenbank-Import');
				continue;
			}


			// Escapen für DB - Insert z.B. bei Name: Up'n Nien Esch
			$strassenname = addslashes($strassenname);


			// Eintrag - Zähler erhöhen
			$cntKunden++;


			if (!isset($kunde[$setRowPLZ])) {
				$PLZ = '';
				$warningArray[$curKundenNummer]['PLZ'] = 'Fehlende PLZ';
			}
			else
				$PLZ = trim($kunde[$setRowPLZ]);



			if (!isset($kunde[$setRowOrt])) {
				$Ort = '';
				$warningArray[$curKundenNummer]['PLZ'] = 'Fehlender Ortsname';
			}
			else
				$Ort = trim($kunde[$setRowOrt]);



			if (!isset($kunde[$setRowTelefon])) {
				$Telefon = '';
				$warningArray[$curKundenNummer]['Telefon'] = 'Fehlende Telefonnummer';
			}
			else
				$Telefon = trim($kunde[$setRowTelefon]);



			if (!isset($kunde[$setRowEmail])) {
				$Email = '';
				$warningArray[$curKundenNummer]['Email'] = 'Fehlende Email';
			}
			else
				$Email = trim($kunde[$setRowEmail]);



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



			// Personenkonto sprich Kundennummer zuweisen
			$personenkonto = $curKundenNummer;



			// Welches System soll genutzt werden (das Alte nur mit SZ oder das Neue mit SZ und BL als Zahlart)
			if ($_SESSION['Cfg']['Default']['Centron']['ZahlungsartOldNew'] == 'new') {

				// Zahlungsart Lastschrift oder Überweisung?
				// Wenn eine BIC vorliegt... gehen wir von Lastschrift aus... festgelegt durch:
				// L. Koschin am  12.04.2016
				if (strlen($BIC) > 0)
					$zahlungsart = $_SESSION['Cfg']['Default']['Centron']['ZahlungsartBL'];
				else
					$zahlungsart = $_SESSION['Cfg']['Default']['Centron']['Zahlungsart'];

			}
			else
				$zahlungsart = $_SESSION['Cfg']['Default']['Centron']['Zahlungsart'];



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
							   `updateCounter`			= updateCounter + 1,
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

			// DB Eintrag erstellen oder Updaten (Query erstellen)!
			$query = "INSERT INTO basedata_centron " . $dynInsertQuery . " ON DUPLICATE KEY UPDATE " . $dynUpdateQuery;

			// Query ausführen
			$this->query($query);

		}   // END foreach ($zeilen as $kunde){



		// Belegten Speicher in der global wieder freigeben
		unset($this->coreGlobal['ImportValue']);
		unset($this->coreGlobal['curDownloadLink']);
		unset($this->coreGlobal['curSourceTypeID']);
		unset($this->coreGlobal['curSourceSystemID']);
		unset($this->coreGlobal['curDownloadLink']);
		unset($this->coreGlobal['curFilePath']);



		// Informationen ausgeben
		if ($cntKunden > 0)
			$this->addMessage('Datenbank - Import erfolgreich!', $cntKunden . ' Datensätze wurden erfolreich in die Datenbank übernommen.', 'Erfolg', 'Datenbank - Import');
		else
			$this->addMessage('Datenbank - Import durchgeführt!', 'Es wurden keine Datensätze in die Datenbank übernommen.', 'Erfolg', 'Datenbank - Import');


		return true;

	}    // END private function insertIntoDBBaseData()


}   // END class FileImportCentron