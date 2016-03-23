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
 *                                      '-> ConcreteClass1          CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ConcreteClass20         CoreExtends - Child - AnyCreature
 *
 */
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
			case 'SystemLogin_callLogin_tryUserLogin':
				// Login - Abfrage

				$getQuery = "SELECT u.*
                            ,r.userRoleID
                            ,r.userRoleName
                              FROM user u
                                LEFT JOIN userrole r ON (u.userRoleID = r.userRoleID)
                              WHERE u.userName      = '" . $paramArray['userName'] . "'
                                AND u.userPassword  = md5('" . $paramArray['userPassword'] . "')
                                AND u.activeStatus  = 'yes'
                                AND r.activeStatus  = 'yes'
                                LIMIT 1";

				break;



			case 'SystemLogin_doWriteUserLoginToDB_logUserLogin':
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



			case 'SystemLogin_getUserLastLogin_getUserLastLoginDate':
				// letzte Login-Informationen eines Betnutzers ermitteln

				$getQuery = "SELECT `lastLogin`
					          FROM log_user_login
					          WHERE `userID` LIKE '" . $paramArray['userID'] . "'
					          ORDER BY log_user_loginID DESC
					          LIMIT 0,2";

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

			$curCleanValue = mysqli_real_escape_string($this->getDBConnection(), $value);

			$curCleanValue = addcslashes($curCleanValue, '%_');

			$retArray[$key] = $curCleanValue;

		}

		return $retArray;

	}   // END function getCleanDBInput($paramArray)

}   // END abstract class CoreQuery extends CoreDebug