<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:52
 *
 * Vererbungsfolge der (Basis) - Klassen:
 *  Abstract CoreBase                                               Adam/Eva
 *      '-> Abstract CoreSystemConfig                               Child
 *          '-> Abstract CoreDefaultConfig                          Child
 *              '-> Abstract CoreMessages                           Child
 *                  '-> Abstract CoreDebug                          Child
 * ==>                  '-> Abstract CoreQuery                      Child
 *                          '-> Abstract CoreMySQLi                 Child
 *                              '-> CoreObject                      Child
 *                                  '-> CoreExtends                 Child
 *                                      '-> ConcreteClass1          AnyCreature as Child via - extends CoreExtends
 *                                      |-> ...                     AnyCreature as Child via - extends CoreExtends
 *  -> ClassXYZ                                                    AnyCreature from Outerspace
 *  -> ...                                                            AnyCreature from Outerspace
 *
 */
namespace classes\core;


abstract class CoreQuery extends CoreDebug
{

	// Klassen eigener Konstruktor
	function __construct()
	{

		parent::__construct();

	}   // END function __construct()










// Sonderzeichen der Eingabe vor der DB - Nutzung säubern
	function getCleanDBInput($paramArray)
	{

		$retArray = array();

		foreach($paramArray as $key => $value) {

			// TODO Sicherheit ... brauch ich msqli_real_escape_string für Eingaben?
			// Habe an dieser STelle die Methode getDBConnection() nicht!
			// $curCleanValue = mysqli_real_escape_string($this->getDBConnection(), $value);
			$curCleanValue = $value;

			$retArray[$key] = $curCleanValue;

		}

		return $retArray;

	}   // END function getCleanDBInput($paramArray)










	// Gibt die gewnüschte Query zurück
	public function getQuery($queryName, $paramArray = array())
	{

		// Parameter - und Eingabe sichern sprich Injections verhindern
		$paramArray = $this->getCleanDBInput($paramArray);

		$getQuery = '';

		switch ($queryName) {
			case 'tryUserLogin':
				// Login - Abfrage

				$getQuery = "SELECT u.*
                            		,r.userRoleID
                            		,r.userRoleName
                               FROM user u
                          LEFT JOIN user_role r ON (u.userRoleID = r.userRoleID)
                              WHERE u.userName      = '" . $paramArray['userName'] . "'
                                AND u.userPassword  = md5('" . $paramArray['userPassword'] . "')
                                AND u.activeStatus  = 'yes'
                                AND r.activeStatus  = 'yes'
                              LIMIT 1";
				break;



			case 'doLogUserLogin':
				// Login - Vorgang in DB schreiben

				$getQuery = "INSERT INTO log_user_login (userID,
									REMOTE_ADDR,
									HTTP_USER_AGENT,
									HTTP_REFERER,
									HTTP_COOKIE,
									REQUEST_URI,
									SCRIPT_NAME,
									PHP_SELF
								  ) VALUES ('" . $paramArray['userID'] . "',
								  			'" . $_SERVER['REMOTE_ADDR'] . "',
								  			'" . $_SERVER['HTTP_USER_AGENT'] . "',
								  			'" . $_SERVER['HTTP_REFERER'] . "',
								  			'" . $_SERVER['HTTP_COOKIE'] . "',
								  			'" . $_SERVER['REQUEST_URI'] . "',
								  			'" . $_SERVER['SCRIPT_NAME'] . "',
								  			'" . $_SERVER['PHP_SELF'] . "')";
				break;



			case 'getUserLastLoginDate':
				// Letzte Login-Informationen eines Betnutzers ermitteln

				$getQuery = "SELECT `lastLogin`
					          FROM log_user_login
					          WHERE `userID` LIKE '" . $paramArray['userID'] . "'
					       ORDER BY log_user_loginID DESC
					          LIMIT 0,2";
				break;



			case 'getActiveSourceTypeOrderByArg':
				// Liest Informationen über die Sourcetpyen anhand der übergebenen ID

				$getQuery = "SELECT * FROM `source_type` WHERE active = 'yes' ORDER BY " . $paramArray['orderBy'];
				break;



			case 'getActiveSourceTypeByID':
				// Liest Informationen über die Sourcetype anhand der übergebenen ID

				$getQuery = "SELECT * FROM `source_type` WHERE active = 'yes' AND sourceTypeID = '" . $paramArray['sourceTypeID'] . "' LIMIT 1";
				break;



			case 'getActiveSourceSystemsOrderByArg':
				// Liest Informationen über das Sourcesystem anhand der übergebenen ID

				$getQuery = "SELECT * FROM `source_system` WHERE active = 'yes' ORDER BY " . $paramArray['orderBy'];
				break;



			case 'getActiveSourceSystemByID':
				// Liest Informationen über das Sourcesystem anhand der übergebenen ID

				$getQuery = "SELECT * FROM `source_system` WHERE active = 'yes' AND sourceSystemID = '" . $paramArray['sourceSystemID'] . "' LIMIT 1";
				break;



			case 'getNavMenue':
				// Liest das komplette Navigations-Menue ein

				$getQuery = "SELECT sst.*
          							,st.*
          							,ss.*
     						   FROM source_system_type sst
						  LEFT JOIN source_type st    ON st.sourceTypeID = sst.sourceTypeID
						  LEFT JOIN source_system ss  ON ss.sourceSystemID = sst.sourceSystemID
    						  WHERE sst.active = 'yes'
      							AND st.active  = 'yes'
      							AND ss.active  = 'yes'
 						   ORDER BY st.orderBy,
          							ss.orderBy";
				break;



			case 'getActiveSourceTypeDataByX':
				// Liefert die Daten aus "source_type" anhand des gesuchten Parameter

				$getQuery = "SELECT * FROM `source_type` WHERE active = 'yes' AND " . $paramArray['WHERE'] . " = '" . $paramArray['SEARCH'] . "' ORDER BY " . $paramArray['WHERE'] . " LIMIT 1";
				break;



			case 'getActiveSourceSystemDataByX':
				// Liefert die Daten aus "source_system" anhand des gesuchten Parameter

				$getQuery = "SELECT * FROM `source_system` WHERE active = 'yes' AND  " . $paramArray['WHERE'] . " = '" . $paramArray['SEARCH'] . "' ORDER BY " . $paramArray['WHERE'] . " LIMIT 1";
				break;



			case 'getActiveSourceXDataByX':
				// Liefert die Daten aus Tabelle X anhand des gesuchten Parameter

				$getQuery = "SELECT * FROM `" . $paramArray['FROM'] . "` WHERE active = 'yes' AND  " . $paramArray['WHERE'] . " = '" . $paramArray['SEARCH'] . "' ORDER BY " . $paramArray['WHERE'] . " LIMIT 1";
				break;



			case 'getlogFileUpload':
				// Inser Query für den Datei - Upload

				$getQuery = "INSERT INTO file_upload (
									sourceTypeID,
									sourceSystemID,
									userID,
									uploadDateTime,
									fileOriginName,
									fileTmpName,
									fileTargetName,
									fileTargetPath,
									fileTargetFullPath,
									fileSize,
									downloadLink
								  ) VALUES (
									'" . $paramArray['sourceTypeID'] . "',
									'" . $paramArray['sourceSystemID'] . "',
								  	'" . $paramArray['userID'] . "',
								  	now(),
								  	'" . $paramArray['file_name'] . "',
								  	'" . $paramArray['file_tmp_name'] . "',
								  	'" . $paramArray['newFilename'] . "',
								  	'" . $paramArray['uploadPath'] . "',
								  	'" . $paramArray['fullUploadPath'] . "',
								  	'" . $paramArray['file_size'] . "',
								  	'" . $paramArray['downloadLink'] . "')";
				break;



			case 'getFileImportList':
				// Lade Daten die zur Auswahl stehen

				$getQuery = "SELECT *
                      		   FROM `file_Upload`
                          LEFT JOIN `source_system` 	ON source_system.sourceSystemID	= file_upload.sourceSystemID
                          LEFT JOIN `source_type`   	ON source_type.sourceTypeID 	= file_upload.sourceTypeID
                          LEFT JOIN `user`          	ON user.userID                  = file_upload.userID
                          LEFT JOIN `user_role`			ON user_role.userRoleID			= user.userRoleID
                      		  WHERE file_upload.sourceTypeID     = '" . $paramArray['sourceTypeID'] . "'
                        		AND file_upload.sourceSystemID   = '" . $paramArray['sourceSystemID'] . "'
                        		AND source_type.active           = 'yes'
                        		AND source_system.active         = 'yes'
                      	   ORDER BY file_upload.uploadDateTime DESC
                        ";
				break;



			case 'getInformationFromFileUploadByFileUploadID':
				// Lesen Informationen zu einer hochgeladenen Datei ein

				$getQuery = "SELECT *
                      		   FROM `file_Upload`
                          LEFT JOIN `source_system` 	ON source_system.sourceSystemID	= file_upload.sourceSystemID
                          LEFT JOIN `source_type`   	ON source_type.sourceTypeID 	= file_upload.sourceTypeID
                         	  WHERE file_upload.fileUploadID     = '" . $paramArray['fileUploadID'] . "'
                        		AND source_type.active           = 'yes'
                        		AND source_system.active         = 'yes'
                      	   ORDER BY file_upload.uploadDateTime
                      	   	  LIMIT 1
                        ";
				break;



			case 'getSumFromTableX':
				// Liest die Summe der Einträge aus der Tabelle X

				$getQuery = "SELECT COUNT(*) AS SUM FROM " . $paramArray['FROM'] . " WHERE 1";
				break;



			case 'getSumMandateFromTableX':
				// Liest die Summe der Einträge aus der Tabelle X die eine Mandatsrefrennummer haben

				$getQuery = "SELECT COUNT(*) AS SUM FROM " . $paramArray['FROM'] . " WHERE Mandatsreferenznummer != ''";
				break;



			case 'getNewestLastUpdateFromTableX':
				// Ermittelt das neueste Update aus der Tabelle X

				$getQuery = "SELECT lastUpdate FROM " . $paramArray['FROM'] . " WHERE 1 ORDER BY lastUpdate DESC LIMIT 1";
				break;



			case 'getOldestLastUpdateFromTableX':
				// Ermittelt das älteste Update aus der Tabelle X

				$getQuery = "SELECT lastUpdate FROM " . $paramArray['FROM'] . " WHERE 1 ORDER BY lastUpdate ASC LIMIT 1";
				break;



			case 'getSammelkontenFromTableX':
				// Ermittelt alle Sammelkonten von Tabelle X

				$getQuery = "SELECT Sammelkonto FROM " . $paramArray['FROM'] . " WHERE 1 GROUP BY Sammelkonto";
				break;



			case 'getZahlungsartenFromTableX':
				// Ermittelt alle Zahlungsarten von Tabelle X

				$getQuery = "SELECT Zahlungsart FROM " . $paramArray['FROM'] . " WHERE 1 GROUP BY Zahlungsart";
				break;



			case 'getUploadUserFromTableX':
				// Ermittelt den Upload-Benutzer zu einem Datensatz in der Tabelle X

				$getQuery = "SELECT userName FROM user u, " . $paramArray['FROM'] . " AS b WHERE u.userID = b.userID GROUP BY u.userID";
				break;


			case 'getMwSTFromTableX':
				// Ermittelt alle MwST von Tabelle X

				$getQuery = "SELECT MwSt FROM " . $paramArray['FROM'] . " WHERE 1 GROUP BY MwSt";
				break;


			case 'getXGroupByX':
				// Ermittelt alle Werte zu X Gruppiert bei X

				$getQuery = "SELECT " . $paramArray['GROUP'] . " FROM " . $paramArray['FROM'] . " WHERE 1 GROUP BY " . $paramArray['GROUP'] . " ORDER BY " . $paramArray['GROUP'] . " ";
				break;


			case 'getMandatCheck':
				// Ermittelt ob schon eine Mandat-Ref Nummer bzw. Centron-Mandat-Ref - Nummer vorhanden ist.

				$getQuery = "SELECT * FROM `centron_mand_ref` WHERE personenkonto = '" . $paramArray['customerID'] . "' OR mandatsnummer = '" . $paramArray['mandatNumber'] . "' LIMIT 1";
				break;


			case 'getMandatCheckOnUpdate':
				// Ermittelt ob schon eine Mandat-Ref Nummer bzw. Centron-Mandat-Ref - Nummer vorhanden ist.

				$getQuery = "SELECT * FROM `centron_mand_ref` WHERE (personenkonto = '" . $paramArray['customerID'] . "' OR mandatsnummer = '" . $paramArray['mandatNumber'] . "') AND centron_mand_refID != '" . $paramArray['centron_mand_refID'] . "' LIMIT 1";
				break;


			default:
				break;
		}

		return $getQuery;

	}   // END public function getQuery(...)


}   // END abstract class CoreQuery extends CoreDebug