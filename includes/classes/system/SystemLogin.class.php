<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:53
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
 *  -> ClassXYZ                                                    AnyCreature from Outerspace
 *  -> ...                                                            AnyCreature from Outerspace
 *
 */
namespace classes\system;


use classes\core\CoreExtends;


class SystemLogin extends CoreExtends
{

	// Initialsiere Variable
	public $myDynObj;       // Objekt Handler aus dem Core - Klassen - System
	public $coreGlobal;     // Kopiere globale Variable aus der Start-Klasse










	// Klassen eigener Konstruktor
	function __construct($flagUseGlobalCoreClassObj = true)
	{

		parent::__construct();

	}   // END function __construct(...)










	// Prüft ob der User eingeloggt ist und gibt bei Erfolg die userID zuürck : false
	function checkLoginStatus()
	{

		return $this->getLoginStatus();

	}   // END function checkLoginStatus()










	// Bei eingeloggtem User gibt die Methode die Login - ID zurück : false
	function getLoginStatus()
	{

		if ((isset($_SESSION['Login']['userID'])) && ($_SESSION['Login']['userID'] > 0))
			return $_SESSION['Login']['userID'];
		else
			return false;

	}   // END function getLoginStatus()










	// Login - Versuch des Users
	function callLogin()
	{

		$userName = $this->coreGlobal['POST']['userName'];
		$userPassword = $this->coreGlobal['POST']['userPassword'];


		// Query zum Login ermitteln
		$paramArray = array('userName' => $userName, 'userPassword' => $userPassword);
		$query = $this->getQuery('tryUserLogin', $paramArray);

		$result = $this->query($query);

		if ($this->num_rows($result) != 1) {

			// Message für fehlerhaften Login
			$this->addMessage('Fehlerhafter Login!', 'Benutzername und/oder Passwort-Kombination unbekannt.', 'Fehler', 'Login');

			$this->free_result($result);

			return false;
		}

		$row = $result->fetch_object();

		$_SESSION['Login']['userID'] = $row->userID;
		$_SESSION['Login']['userName'] = $row->userName;
		$_SESSION['Login']['userRoleID'] = $row->userRoleID;
		$_SESSION['Login']['userRoleName'] = $row->userRoleName;

		$this->free_result($result);


		// Vorgang des Logins speichern
		$this->doWriteUserLoginToDB();


		// Letzen Login einlesen
		$this->getUserLastLogin();

		// Message für erfolgreicher Login
		$this->addMessage('Erfolgreicher Login!', 'Herzlich willkommen ' . $_SESSION['Login']['userName'] . '!', 'Info', 'Login');

		return true;

	}   // END function callLogin()










	// Speichert den Login-Vorgang eines Benutzers
	private function doWriteUserLoginToDB()
	{

		// Query zum Login ermitteln
		$paramArray = array('userID' => $_SESSION['Login']['userID']);
		$query = $this->getQuery('doLogUserLogin', $paramArray);

		$this->query($query);

		return true;

	}    // END private function doWriteUserLoginToDB()










	// Liest letzte Login-Informationen eines Betnutzers
	private function getUserLastLogin()
	{

		// Query ermitteln
		$paramArray = array('userID' => $_SESSION['Login']['userID']);
		$query = $this->getQuery('getUserLastLoginDate', $paramArray);

		// Führe Query aus
		$result = $this->query($query);

		if ($this->num_rows($result) >= 1) {

			$bGotLast = false;

			while ($row = $result->fetch_object()) {

				if (!$bGotLast) {
					// Speichere beide Info-Variable auf dateLastLogin
					// Im zweiten Durchlauf der Schleife ist dann alles richtig.
					// So fange ich ab, wenn der User sich zum ersten mal einlogt

					$_SESSION['Login']['dateCurLogin'] = $row->lastLogin;
					$_SESSION['Login']['dateLastLogin'] = $row->lastLogin;

					$bGotLast = true;
				}
				else
					$_SESSION['Login']['dateLastLogin'] = $row->lastLogin;

			}

		}
		else {
			$this->free_result($result);

			return false;
		}

		$this->free_result($result);

		return true;

	}    // END private function getUserLastLogin()










	// Logge User aus
	function callLogout()
	{

		// Soll gleich wohin leiten?
		$redirectTo = $_SESSION['Cfg']['Default']['WebsiteSettings']['ExternToHome'];

		// Session - Save initial mit Array
		$_SESSION = array();

		// Session - loeschen
		session_destroy();

		// Header Redirect
		header('Location: ' . $redirectTo . '');

		exit;

	}   // END function callLogout()


}   // END class SystemLogin extends CoreExtends