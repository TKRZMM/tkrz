<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.04.2016
 * Time: 14:41
 */

namespace fileExport;


use classes\core\CoreExtends;
use \DateTime;
use \DateInterval;


class FileExportCentron extends CoreExtends
{

	// Objekt Handler aus dem Core - Klassen - System.
	public $myDynObj;

	// Globale Variable aus der Start-Klasse.
	public $coreGlobal;


	// Daten aus der Import-Datei
	public $myExportFile;










	// FileImport constructor.
	function __construct($flagUseGlobalCoreClassObj = true)
	{

		parent::__construct();

	}   // END function __construct(...)










	// Initial Methode für den Export der Stammdaten!!!
	function fileExportbaseData()
	{

		// Achtung!!!
		// Mehtoden-Name wird bei Aufruf dynamisch zusammengesetzt:
		// 'fileExport' . {fileUploadDirName} <- Aus DB-Tabelle source_typ

		// Export Stammdaten
		$this->doExportsBaseData();


		// Stammdaten in Datenbank schreiben
		// $this->insertIntoDBBaseData();

		return true;

	}    // END function fileExportbaseData()



	// TODO In Ruhe durchsehen ... mit Seesion - Var usw ob das alles passt
	// Initial Methode für den Export der Buchungsdaten!!!
	function fileExportbookingData()
	{

		// Achtung!!!
		// Mehtoden-Name wird bei Aufruf dynamisch zusammengesetzt:
		// 'fileExport' . {fileUploadDirName} <- Aus DB-Tabelle source_typ

		// Benötigte Kundendaten anhand der anstehenden Rechnungen und KundenNr. ermitteln
		$this->getRelevantBaseData();

		// Buchungssatz einlesen
		$this->getBookingData();

		// Kundendaten zu Buchungssatz einlesen

		// A B C Stamm aufbauen
		$this->generateSets();

		// csv-Datei erstellen
		$this->generateBooginCSV();

		return true;

	}    // END function fileExportbookingData()










	private function doExportsBaseData()
	{

		// Feldnamen einlesen
		$query = "SHOW COLUMNS FROM centron_basedata";
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);

		if (!$num_rows >= '1')
			return false;


		while ($row = $result->fetch_object())
			$dbFieldnames[] = $row->Field;

		$this->free_result($result);



		// Stammdaten einlesen
		$query = "SELECT * FROM centron_basedata ORDER BY Name1, Name2";
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);

		if ((!$num_rows >= '1') || (count($dbFieldnames) < 1))
			return false;



		$cntIndex = 0;
		while ($row = $result->fetch_object()) {

			foreach($dbFieldnames as $curFieldname) {
				$this->coreGlobal['csvDaten'][$cntIndex][$curFieldname] = $row->$curFieldname;  // Automatisch die Feldnamen als Variable benutzen
			}
			$cntIndex++;
		}
		$this->free_result($result);


		// Lastschriftmandate einlesen
		$query = "SELECT * FROM centron_mand_ref WHERE activeStatus = 'yes' ORDER BY personenkonto";
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);

		if ($num_rows >= '1') {
			$mandRefArray = array();
			while ($row = $result->fetch_object()) {

				$mandRefArray[$row->personenkonto] = $row->mandatsnummer;
			}
		}
		$this->free_result($result);

		$csv = "";

		$cnt_kunden = 0;

		foreach($this->coreGlobal['csvDaten'] as $kunde) {

			$cnt_kunden++;

			$personenkonto = trim($kunde['Personenkonto']);    // Personenkonto sprich Kundennummer

			$curKontoNummer = $kunde['Kontonummer'];
			$curLaendercode = $kunde['Laendercode'];
			$curBLZ = $kunde['BLZ'];
			$curMandRef = '';

			// Basisslastschrift ... Mandatsreferenz ermitteln
			if ($kunde['Zahlungsart'] == 'BL') {

				if (isset($mandRefArray[$personenkonto]))
					$curMandRef = $mandRefArray[$personenkonto];


				// Kontonummer und BLZ aus IBAN ermitteln ... Siehe Email S. Bruns vom 20.04.2016 12:46 Uhr
				$curIBAN = trim($kunde['IBAN']);

				// Mögliche Leerzeichen entfernen
				$curIBAN = preg_replace('/ /', '', $curIBAN);
				$tmpLandID = substr($curIBAN, 0, 2);

				if ($tmpLandID == 'DE') {

					// Deutsche IBAN
					if (strlen($curBLZ) < 2)
						$curBLZ = substr($curIBAN, 4, 8);

					if (strlen($curKontoNummer) < 2)
						$curKontoNummer = substr($curIBAN, 12);

				}
				elseif ($tmpLandID == 'NL') {

					// Niederländische IBAN
					if (strlen($curBLZ) < 2)
						$curBLZ = substr($curIBAN, 4, 4);

					if (strlen($curKontoNummer) < 2)
						$curKontoNummer = substr($curIBAN, 8);

				}

			}
			else {
				// Kontonummer 0 entfernen ... Siehe Email S. Bruns vom 20.04.2016 10:56 Uhr
				$curKontoNummer = '';

				// Laenderkennung entfernen ... Siehe Email S. Bruns vom 20.04.2016 10:56 Uhr
				$curLaendercode = '';
			}

			// Laendercode A sollte AT werden  ... Siehe Email S. Bruns vom 20.04.2016 10:56 Uhr
			if ($curLaendercode == 'A')
				$curLaendercode = 'AT';


			$tilde = '~';

			$csv .= "S~";
			$csv .= $personenkonto . $tilde;    // Personenkonto
			$csv .= $kunde['Name1'] . "~";               // Name1
			$csv .= $kunde['Name2'] . "~";               // Name2
			$csv .= $kunde['Sammelkonto'] . "~";                  // Sammelkonto
			$csv .= $kunde['Zahlungsart'] . "~";                      // Zahlungsart
			$csv .= $curMandRef . "~";                        // Mandatsreferenznummer
			$csv .= $curLaendercode . "~";                        // Ländercode
			$csv .= $curBLZ . "~";                        // BLZ
			$csv .= $kunde['BIC'] . "~";                        // BIC
			$csv .= $curKontoNummer . "~";                        // Kontonummer
			$csv .= $kunde['IBAN'] . "~";                        // IBAN
			$csv .= "~";                        // Anrede Brief
			$csv .= "~";                        // Anschrift - Anrede
			$csv .= $kunde['Anschrift_Name1'] . "~";    // Anschrift - Name1
			$csv .= $kunde['Anschrift_Name2'] . "~";    // Anschrift - Name2
			$csv .= "~";                        // Anschrift - Name3
			$csv .= "~";                        // Anschrift - Länderkennzeichen
			$csv .= $kunde['Anschrift_PLZ'] . $tilde;              // Anschrift - PLZ
			$csv .= $kunde['Anschrift_Ort'] . $tilde;              // Anschrift - Ort
			$csv .= $kunde['Anschrift_Strasse'] . "~";        // Anschrift - Straße
			$csv .= $kunde['Anschrift_Hausnummer'] . "~";          // Anschrift - Hausnummer
			$csv .= $kunde['Zusatzhausnummer'] . "~";    // Zusatzhausnummer
			$csv .= "~";                        // Anschrift - Postfach
			$csv .= "~";                        // Anschrift Name1 abw. Kontoinhaber
			$csv .= "~";                        // Anschrift Name2 abw. Kontoinhaber
			$csv .= "~";                        // Anschrift PLZ abw. Kontoinhaber
			$csv .= "~";                        // Anschrift Ort abw. Kontoinhaber
			$csv .= "~";                        // Anschrift Stra�e abw. Kontoinhaber
			$csv .= "~";                        // Anschrift Hnr abw. Kontoinhaber
			$csv .= "~";                        // Anschrift zus. Hnr abw. Kontoinhaber
			$csv .= $kunde['Telefon'] . $tilde;          // Telefon
			$csv .= "~";                        // Fax
			$csv .= $kunde['Email'] . $tilde;            // Email
			$csv .= "~";                        // Aktennummer
			$csv .= "~";                        // Sortierkennzeichen
			$csv .= "~";                        // EG-Identnummer
			$csv .= "~";                        // Branche
			$csv .= "~";                        // Zahl-bed. Auftr.wes
			$csv .= "~";                        // Preisgruppe Auftr.wes

			$csv .= "\r\n";

		}   // END foreach ($this->coreGlobal['csvDaten'] as $kunde){

		// Prüfsumme
		$csv .= "P~";
		$csv .= $cnt_kunden . "~";
		$csv .= "~"; // Gesamtanzahl der Sätze "A" innerhalb der Datei
		$csv .= "~"; // Gesamtsumme aller Bruttobeträge der Sätze "A"
		$csv .= "~"; // Gesamtanzahl der Sätze "B" innerhalb der Datei
		$csv .= "~"; // Gesamtsumme aller Bruttobeträge der Sätze "B"
		$csv .= "~"; // Gesamtanzahl der Sätze "C" innerhalb der Datei
		$csv .= "~"; // Gesamtsumme aller Nettobeträge der Sätze "A"
		$csv .= "~"; // Gesamtsumme aller Steuerbeträge der Sätze "A"

		$csv .= "\r\n";


		// Todo ... schicker machen... Dateinamen besser setzen
		$curPath = $this->checkUploadDir($this->coreGlobal['GET']['subAction'], $this->coreGlobal['GET']['valueAction']);
		$downloadFile = $this->coreGlobal['GET']['callAction'] . $this->coreGlobal['GET']['subAction'] . $this->coreGlobal['GET']['valueAction'];

		$storeFile = $curPath . '/' . $downloadFile . '.csv';

		$fp = fopen($storeFile, 'w');
		fwrite($fp, $csv);
		fclose($fp);

		$this->addMessage('Datenbank Erfolgreich!', 'Die Daten liegen jetzt hier bla blawurde erfolgreich hochgeladen.', 'Erfolg', 'File Upload');

		// PHP - Speicher wieder freigeben
		unset($this->coreGlobal['csvDaten']);

		return true;

	}   // END public function doExportsBaseDataCentron()










	private function checkUploadDir($category, $systemName)
	{

		$mainExportPath = $_SESSION['Cfg']['Default']['WebsiteSettings']['MainExportPath'];

		// Prüfung ob Haupt-Upload-Verzeichnis existiert
		if (!is_dir($mainExportPath)) {

			// Fehler brauche mindestens den Main - Upload Pfade. ... Gebe Information an User weiter
			$explain = 'Der Haupt-Export Pfad konnte nicht geöffnet werden, bitte wenden Sie sich an den zuständingen Administrator.<br>Gesuchter Pfad: ' . $mainExportPath;
			$this->addMessage('Fehler bei Datenbank Export!', 'Die gewünschten Daten konnten nicht exportiert werden.', 'Fehler', 'Datenbank Export', $explain);

			return false;
		}


		// Prüfung Kategorie-Verzeichnis vorhanden?
		$curPath = $mainExportPath . '/' . $category;
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



	///////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////// Buchungsdaten ////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////

	// Benötigte Kundendaten anhand der anstehenden Rechnungen und KundenNr. ermitteln
	private function getRelevantBaseData()
	{

		// Feldnamen einlesen
		$query = "SHOW COLUMNS FROM centron_basedata";
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);

		if (!$num_rows >= '1')
			return false;

		while ($row = $result->fetch_object())
			$dbFieldnames[] = $row->Field;

		$this->free_result($result);


		$query = "SELECT c.*
                    FROM centron_bookingdata as r,
                         centron_basedata as c
                    WHERE c.Personenkonto = r.KundenNummer
                    GROUP BY r.KundenNummer
                    ORDER BY r.KundenNummer";

		$result = $this->query($query);
		$num_rows = $this->num_rows($result);

		if (!$num_rows >= '1')
			return false;

		$cntIndex = 0;
		while ($row = $result->fetch_object()) {

			foreach($dbFieldnames as $curFieldname) {
				$this->coreGlobal['CustomerData'][$row->Personenkonto][$curFieldname] = utf8_encode($row->$curFieldname);  // Automatisch die Feldnamen als Variable benutzen
			}
			$cntIndex++;
		}

		$this->free_result($result);

		RETURN true;

	}   // END private function getBookingData()










	// Buchungssatz einlesen
	private function getBookingData()
	{

		// Feldnamen einlesen
		$query = "SHOW COLUMNS FROM centron_bookingdata";
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);

		if (!$num_rows >= '1')
			RETURN false;

		while ($row = $result->fetch_object())
			$dbFieldnames[] = $row->Field;
		$this->free_result($result);


		// Buchungssatz einlesen
		$query = "SELECT * FROM centron_bookingdata WHERE 1 ORDER BY RechnungsNr, KundenNummer";
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);

		if (!$num_rows >= '1')
			RETURN false;


		$cntIndex = 0;
		while ($row = $result->fetch_object()) {

			foreach($dbFieldnames as $curFieldname) {
				$this->coreGlobal['BuchungsDaten'][$cntIndex][$curFieldname] = utf8_encode($row->$curFieldname);  // Automatisch die Feldnamen als Variable benutzen
			}
			$cntIndex++;
		}
		$this->free_result($result);


		RETURN true;

	}   // END private function getBookingData()










	private function generateSets()
	{

		// Initial Variable definieren
		$lastBookingNumber = 0;
		$indexC = 0;

		foreach($this->coreGlobal['BuchungsDaten'] as $bookingSet) {


			$curCustomerNumber = $bookingSet['KundenNummer'];
			$curBookingNumber = $bookingSet['RechnungsNr'];

			$boolIsLastschriftCustomer = false;

			// Lastschrift Kunde?
			if ($this->coreGlobal['CustomerData'][$curCustomerNumber]['Zahlungsart'] == $_SESSION['Cfg']['Default']['Centron']['ZahlungsartBL'])
				$boolIsLastschriftCustomer = true;


			// Wenn Neue Rechnungsnummer, dann neuen Rechnungssatz erstellen
			if ($curBookingNumber != $lastBookingNumber) {

				// Index C resetten
				$indexC = 0;

				//2016-01-12
				preg_match_all("/(\d+)\-(\d+)\-(\d+)/i", $bookingSet['Datum'], $splitDate);
				$curBuchungsperiode = $splitDate[1][0] . '.' . $splitDate[2][0];
				$curBelegdatum = $splitDate[1][0] . $splitDate[2][0] . $splitDate[3][0];

				$curBuchungsdatumReadable = $splitDate[3][0] . "-" . $splitDate[2][0] . "-" . $splitDate[1][0];

				$curBuchungsdatum = $curBelegdatum;

				$curZahlungsbedingungen = $_SESSION['Cfg']['Default']['Centron']['Zahlungsbedingung'];


				if (!isset($this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']])) {
					// Habe den Stammdatensatz nicht!
					$this->addMessage('Fehler bei Datenbank Export!', 'FEHLER: fehlender Stammdatensatz KDNr.: ' . $bookingSet['KundenNummer'], 'Fehler', 'Datenbank Export');
					continue;
				}

				// Rechnungsnummer:
				if ($bookingSet['Brutto'] < 0) {
					$preReNummer = 'VR';
				}
				else {
					$preReNummer = 'AR';
				}
				$tmpReNummer = sprintf("%'.010d", $curBookingNumber);
				$curReNummer = $preReNummer . $tmpReNummer;


				// Zahlungseinzug berechnen und initislisieren
				$ankZahlungseinzugAm = '';
				$ankZahlungseinzugZum = '';

				// Prenotification
				$preNote = 'N';        // Bei Selbstzahler auf N

				// Lastschrift - Kunde?
				if ($boolIsLastschriftCustomer) {

					if ($_SESSION['Cfg']['Default']['Centron']['ZahlungseinzugCalc'] == 'yes') {
						$ankZahlungseinzugAm = $curBuchungsdatum;

						$myDate = new DateTime($curBuchungsdatumReadable . ' 08:00:00');
						$myDate->add(new DateInterval('P' . $curZahlungsbedingungen . 'D'));
						$ankZahlungseinzugZum = $myDate->format('Ymd');
					}

					// Prenotification ... nur bei Lastschriftkunden mit J
					$preNote = 'J';
				}



				// Erzeuge neuen A Satz
				// PFLICHT
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Satzart'] = 'A';                                      // Satzart
				// PFLICHT
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Personenkonto'] = $bookingSet['KundenNummer'];              // Personenkonto
				// PFLICHT
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Belegnummer'] = $bookingSet['KundenNummer'];              // Belegnummer
				// PFLICHT
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Rechnungsnummer'] = $curReNummer;      // Rechnungsnummer
				// PFLICHT
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Buchungsperiode'] = $curBuchungsperiode;                      // Buchungsperiode
				// PFLICHT
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Belegdatum'] = $curBelegdatum;                           // Belegdatum
				// PFLICHT
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Buchungsdatum'] = $curBuchungsdatum;                        // Buchungsdatum
				// PFLICHT (Wird im B - Teil behandelt und gesetzt)
//                $this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Bruttobetrag'] = ''; // Bruttobetrag
				// PFLICHT
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Waehrung'] = $_SESSION['Cfg']['Default']['Centron']['Waehrung'];   // Waehrung
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Skonto'] = '';    // Skontofähiger Betrag
				// PFLICHT
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Zahlungsbedingungen'] = $curZahlungsbedingungen;    // Zahlungsbedingungen
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWZahlungsart'] = '';   // Abweichende Zahlungsart
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Faelligkeit'] = '';   // Fälligkeit
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Valuta'] = '';   // Valuta
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['PLZ'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Anschrift_PLZ'];      // PLZ
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Ort'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Anschrift_Ort'];   // Ort
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Strasse'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Anschrift_Strasse'];   // Strasse
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Hausnummer'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Anschrift_Hausnummer'];   // Hausnummer
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Zusatzhausnummer'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Zusatzhausnummer'];   // Zusatzhausnummer
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Wohnungsnummer'] = '';   // Wohnungsnummer
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWKontoInhaberName1'] = '';   // Abweichen-der-Kontoinhaber_Name1
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWKontoInhaberName2'] = '';   // Abweichen-der-Kontoinhaber_Name2
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Laendercode'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Laendercode'];   // Laendercode
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWBLZ'] = '';   // BLZ abw. Kontoinhaber
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWKontoNr'] = '';   // Konto_Nr abw. Kontoinhaber
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWIBAN'] = '';   // IBAN abw. Kontoinhaber
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWAnschriftName1'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Anschrift_Name1_abw_Kontoinhaber'];   // Anschrift - Name 1 abw. Kontoinhaber
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWAnschriftName2'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Anschrift_Name2_abw_Kontoinhaber'];   // Anschrift - Name 2 abw. Kontoinhaber
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWAnschriftPLZ'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Anschrift_PLZ_abw_Kontoinhaber'];   // Anschrift - PLZ abw. Kontoinhaber
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWAnschriftOrt'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Anschrift_Ort_abw_Kontoinhaber'];   // Anschrift - Ort abw. Kontoinhaber
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWAnschriftStrasse'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Anschrift_Strasse_abw_Kontoinhaber'];   // Anschrift - Strasse abw. Kontoinhaber
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWAnschriftHausNr'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Anschrift_Hnr_abw_Kontoinhaber'];   // Anschrift - HausNr. abw. Kontoinhaber
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWAnschriftHausNrZusatz'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Anschrift_zus_Hnr_abw_Kontoinhaber'];   // Anschrift - Zus. HausNr. abw. Kontoinhaber
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Prenotifcation'] = $preNote;  // Prenotification erfolgt (J)
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['MandatsRefNr'] = $this->coreGlobal['CustomerData'][$bookingSet['KundenNummer']]['Mandatsreferenznummer'];   // Mandatsreferenz-nummer
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['AnkZahlungseinzgZum'] = $ankZahlungseinzugZum;   // Anküendigung des Zahlungseinzugs zum
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['AnkZahlungseinzgAm'] = $ankZahlungseinzugAm;   // Ankündigung des Zahlungseinzugs am

				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['ABWAnkZahlungseinzg'] = '';   // Ankündigung des Zahlunseinzugs am für den abw. Kontoinhaber
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['BuchungszeichenAvviso'] = '';   // Buchungszeichen Avviso



				// Erzeuge neuen B Satz
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['B']['Satzart'] = 'B';                            // Satzart
				//$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['B']['Bruttobetrag']         = $bookingSet['Brutto'];   // Bruttobetrag
				//$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['B']['Nettobetrag']        = '';                       // Nettobetrag
//                $this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['B']['Steuerkennzeichen']    = $bookingSet['MwSt'];      // Steuerkennzeichen

				$curSteuerkennzeichen = '';
				if (!isset($_SESSION['Cfg']['Default']['Centron']['Steuerkennzeichen'][$bookingSet['MwSt']]))
					$this->addMessage('Fehler bei Datenbank Export!', 'FEHLER: fehlendes Steuerkennzeichen KDNr.: ' . $bookingSet['KundenNummer'], 'Fehler', 'Datenbank Export');
				else
					$curSteuerkennzeichen = $_SESSION['Cfg']['Default']['Centron']['Steuerkennzeichen'][$bookingSet['MwSt']];

				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['B']['Steuerkennzeichen'] = $curSteuerkennzeichen;      // Steuerkennzeichen
				$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['B']['Geschaeftsbereich'] = $_SESSION['Cfg']['Default']['Centron']['GeschaeftsbereichNonPrivate'];    // Geschäftsbereich


			}  //  END if ($curBookingNumber != $lastBookingNumber)


			// C Satz hinzufügen
			$brutto = $bookingSet['Brutto'];
			$brutto = $this->cleanMoney($brutto);

			$mwst = $bookingSet['MwSt'];
			$mwst = $this->cleanMoney($mwst);


			$prozentBerechnung = 100 + $mwst;
			$curCSteuerbetrag = $brutto * ($mwst / $prozentBerechnung);
			$curCSteuerbetrag = $this->cleanMoney($curCSteuerbetrag);
			$curCNetto = $brutto - $curCSteuerbetrag;
			$curCNetto = $this->cleanMoney($curCNetto);

			$curBNetto = 0;
			$curBBrutto = 0;
			if (isset($this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['B']['Nettobetrag'])) {
				$curBNetto = $this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['B']['Nettobetrag'];
				$curBBrutto = $this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['B']['Bruttobetrag'];
			}


			$curNewNetto = $curBNetto + $curCNetto;
			$curNewNetto = $this->cleanMoney($curNewNetto);
			$curNewBBrutto = $curBBrutto + $brutto;
			$curNewBBrutto = $this->cleanMoney($curNewBBrutto);

			// B Netto / Brutto Betrag:
			$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['B']['Nettobetrag'] = $curNewNetto;
			$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['B']['Bruttobetrag'] = $curNewBBrutto;



			// A Brutto Betrag
			if (isset($this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Bruttobetrag'])) {
				$curABrutto = $this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Bruttobetrag'];
			}
			else {
				$curABrutto = 0;
			}
			$curNewABrutto = $curABrutto + $brutto;
			$curNewABrutto = $this->cleanMoney($curNewABrutto);
			$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['A']['Bruttobetrag'] = $curNewABrutto;



			$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['C'][$indexC]['Satzart'] = 'C';
			$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['C'][$indexC]['Erloeskonto'] = $bookingSet['Erloeskonto'];          // Konto/Erlöskonto
			$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['C'][$indexC]['Nettobetrag'] = $curCNetto;                          // Nettobetrag
			$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['C'][$indexC]['Steuerbetrag'] = $curCSteuerbetrag;                   // Steuerbetrag
			$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['C'][$indexC]['KST'] = $bookingSet['Kostenstelle'];         // KST
			$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['C'][$indexC]['KTR'] = '';                                  // KTR
			$this->coreGlobal['ExportBuchungsDaten']['Rechnungen'][$curBookingNumber]['C'][$indexC]['Buchungstext'] = $bookingSet['Buchungstext'];         // Buchungstext



			// C Index erhöhen
			$indexC++;

			// Verarbeitete Rechnungsnummer speichern
			$lastBookingNumber = $curBookingNumber;
			$lastCustomerNumber = $curCustomerNumber;

		}   // END foreach ($this->coreGlobal['BuchungsDaten'] as $bookingSet){


		RETURN true;

	}   // END private function generateSetA()










	private function cleanMoney($arg)
	{

		$arg = str_replace(",", ".", $arg);
		$arg = round($arg, 2);
		$arg = number_format($arg, 2, '.', '');

		return $arg;
	}










	// CSV - Datei Buchungssatz erstellen
	private function generateBooginCSV()
	{

		// Lastschriftmandate einlesen
		$query = "SELECT * FROM centron_mand_ref WHERE activeStatus = 'yes' ORDER BY personenkonto";
		$result = $this->query($query);
		$num_rows = $this->num_rows($result);

		if ($num_rows >= '1') {
			$mandRefArray = array();
			while ($row = $result->fetch_object()) {

				$mandRefArray[$row->personenkonto] = $row->mandatsnummer;
			}
		}
		$this->free_result($result);


		if (!isset($this->coreGlobal['ExportBuchungsDaten']['Rechnungen']))
			return false;

		$tilde = '~';

		$csv = '';

		$cntA = 0;
		$cntB = 0;
		$cntC = 0;
		$cntSum = 0;
		$sumBruttoA = 0;
		$sumBruttoB = 0;
		$sumNettoC = 0;
		$sumSteuerBetragC = 0;

		foreach($this->coreGlobal['ExportBuchungsDaten']['Rechnungen'] AS $index => $set) {

			// Mandatsreferenznummer
			$tmpKdNr = $set['A']['Personenkonto'];
			$curMandRef = '';
			if (isset($mandRefArray[$tmpKdNr])) {
				$curMandRef = $mandRefArray[$tmpKdNr];
			}

			// Für Prüfsumme berechnen
			$sumBruttoA += $set['A']['Bruttobetrag'];

			// A Satz erstellen
			$csv .= $set['A']['Satzart'] . $tilde;
			$csv .= $set['A']['Personenkonto'] . $tilde;
			$csv .= $set['A']['Belegnummer'] . $tilde;
			$csv .= $set['A']['Rechnungsnummer'] . $tilde;
			$csv .= $set['A']['Buchungsperiode'] . $tilde;
			$csv .= $set['A']['Belegdatum'] . $tilde;
			$csv .= $set['A']['Buchungsdatum'] . $tilde;
			$csv .= $set['A']['Bruttobetrag'] . $tilde;
			$csv .= $set['A']['Waehrung'] . $tilde;
			$csv .= $set['A']['Skonto'] . $tilde;
			$csv .= $set['A']['Zahlungsbedingungen'] . $tilde;
			$csv .= $set['A']['ABWZahlungsart'] . $tilde;
			$csv .= $set['A']['Faelligkeit'] . $tilde;
			$csv .= $set['A']['Valuta'] . $tilde;
			$csv .= $set['A']['Ort'] . $tilde;
			$csv .= $set['A']['Strasse'] . $tilde;
			$csv .= $set['A']['Hausnummer'] . $tilde;
			$csv .= $set['A']['Zusatzhausnummer'] . $tilde;
			$csv .= $set['A']['Wohnungsnummer'] . $tilde;
			$csv .= $set['A']['ABWKontoInhaberName1'] . $tilde;
			$csv .= $set['A']['ABWKontoInhaberName2'] . $tilde;
			$csv .= $set['A']['Laendercode'] . $tilde;
			$csv .= $set['A']['ABWBLZ'] . $tilde;
			$csv .= $set['A']['ABWKontoNr'] . $tilde;
			$csv .= $set['A']['ABWIBAN'] . $tilde;
			$csv .= $set['A']['ABWAnschriftName1'] . $tilde;
			$csv .= $set['A']['ABWAnschriftName2'] . $tilde;
			$csv .= $set['A']['ABWAnschriftPLZ'] . $tilde;
			$csv .= $set['A']['ABWAnschriftOrt'] . $tilde;
			$csv .= $set['A']['ABWAnschriftStrasse'] . $tilde;
			$csv .= $set['A']['ABWAnschriftHausNr'] . $tilde;
			$csv .= $set['A']['ABWAnschriftHausNrZusatz'] . $tilde;
			$csv .= $tilde;                                             // Leeres Feld, Workaround weil Fehler in der kVASy Beschreibung(!)
			$csv .= $set['A']['Prenotifcation'] . $tilde;
			$csv .= $curMandRef . $tilde;
			$csv .= $set['A']['AnkZahlungseinzgZum'] . $tilde;
			$csv .= $set['A']['AnkZahlungseinzgAm'] . $tilde;
			$csv .= $set['A']['ABWAnkZahlungseinzg'] . $tilde;
			$csv .= $set['A']['BuchungszeichenAvviso'] . $tilde;
			$csv .= "\r\n";
			$cntA++;


			// Für Prüfsumme berechnen
			$sumBruttoB += $set['B']['Bruttobetrag'];

			// B Satz erstellen
			$csv .= $set['B']['Satzart'] . $tilde;
			$csv .= $set['B']['Bruttobetrag'] . $tilde;
			$csv .= $set['B']['Nettobetrag'] . $tilde;
			$csv .= $set['B']['Steuerkennzeichen'] . $tilde;
			$csv .= $set['B']['Geschaeftsbereich'] . $tilde;
			$csv .= "\r\n";
			$cntB++;



			// C Satz erstellen
			foreach($set['C'] as $indexC => $valueC) {

				// Für Prüfsumme berechnen
				$sumNettoC += $valueC['Nettobetrag'];

				// Für Prüfsumme berechnen
				$sumSteuerBetragC += $valueC['Steuerbetrag'];

				$csv .= $valueC['Satzart'] . $tilde;
				$csv .= $valueC['Erloeskonto'] . $tilde;
				$csv .= $valueC['Nettobetrag'] . $tilde;
				$csv .= $valueC['Steuerbetrag'] . $tilde;
				$csv .= $valueC['KST'] . $tilde;
				$csv .= $valueC['KTR'] . $tilde;
				$csv .= $valueC['Buchungstext'] . $tilde;
				$csv .= "\r\n";
				$cntC++;
			}

		}   // END foreach ($this->coreGlobal['ExportBuchungsDaten']['KdNr'] AS $kdNummer=>$setArray){

		// Prüfsumme
		$csv .= "P~";
		$csv .= "~";                    // Gesamtanzahl der Sätze "S" innerhalb der Datei
		$csv .= $cntA . "~";            // Gesamtanzahl der Sätze "A" innerhalb der Datei
		$csv .= $sumBruttoA . "~";       // Gesamtsumme aller Bruttobeträge der Sätze "A"
		$csv .= $cntB . "~";            // Gesamtanzahl der Sätze "B" innerhalb der Datei
		$csv .= $sumBruttoB . "~";       // Gesamtsumme aller Bruttobeträge der Sätze "B"
		$csv .= $cntC . "~";            // Gesamtanzahl der Sätze "C" innerhalb der Datei
		$csv .= $sumNettoC . "~";        // Gesamtsumme aller Nettobeträge der Sätze "C"
		$csv .= $sumSteuerBetragC . "~";  // Gesamtsumme aller Steuerbeträge der Sätze "C"
		$csv .= "\r\n";


		$this->coreGlobal['BookingCSV'] = $csv;



		// Todo ... schicker machen... Dateinamen besser setzen
		$curPath = $this->checkUploadDir($this->coreGlobal['GET']['subAction'], $this->coreGlobal['GET']['valueAction']);
		$downloadFile = $this->coreGlobal['GET']['callAction'] . $this->coreGlobal['GET']['subAction'] . $this->coreGlobal['GET']['valueAction'];

		$storeFile = $curPath . '/' . $downloadFile . '.csv';

		$fp = fopen($storeFile, 'w');
		fwrite($fp, $csv);
		fclose($fp);

		$this->addMessage('Datenbank Export erfolgreich!', 'Die Daten liegen jetzt hier bla blawurde erfolgreich hochgeladen.', 'Erfolg', 'File Export');


		// PHP - Speicher wieder freigeben
		unset($this->coreGlobal['CustomerData']);
		unset($this->coreGlobal['BuchungsDaten']);
		unset($this->coreGlobal['ExportBuchungsDaten']);
		unset($this->coreGlobal['BookingCSV']);


		RETURN true;

	}   // END private function generateBooginCSV()


}   // END class FileImportCentron