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

				$getQuery = "SELECT * FROM `source_type` WHERE active = 'yes' AND ".$paramArray['WHERE']." = '".$paramArray['SEARCH']."' ORDER BY ".$paramArray['WHERE']." LIMIT 1";
				break;



			case 'getActiveSourceSystemDataByX':
				// Liefert die Daten aus "source_system" anhand des gesuchten Parameter

				$getQuery = "SELECT * FROM `source_system` WHERE active = 'yes' AND  ".$paramArray['WHERE']." = '".$paramArray['SEARCH']."' ORDER BY ".$paramArray['WHERE']." LIMIT 1";
				break;



			case 'getActiveSourceXDataByX':
				// Liefert die Daten aus Tabelle X anhand des gesuchten Parameter

				$getQuery = "SELECT * FROM `".$paramArray['FROM']."` WHERE active = 'yes' AND  ".$paramArray['WHERE']." = '".$paramArray['SEARCH']."' ORDER BY ".$paramArray['WHERE']." LIMIT 1";
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
									'".$paramArray['sourceTypeID']."',
									'".$paramArray['sourceSystemID']."',
								  	'".$paramArray['userID']."',
								  	now(),
								  	'".$paramArray['file_name']."',
								  	'".$paramArray['file_tmp_name']."',
								  	'".$paramArray['newFilename']."',
								  	'".$paramArray['uploadPath']."',
								  	'".$paramArray['fullUploadPath']."',
								  	'".$paramArray['file_size']."',
								  	'".$paramArray['downloadLink']."')";

				break;


			default:
				break;
		}

		return $getQuery;

	}   // END public function getQuery(...)










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

}   // END abstract class CoreQuery extends CoreDebug