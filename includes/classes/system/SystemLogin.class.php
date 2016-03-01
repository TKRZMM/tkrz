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
 * ==>                                  '-> ConcreteClass1          CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ConcreteClass20         CoreExtends - Child - AnyCreature
 *
 */
class SystemLogin extends CoreExtends
{

    public $myDynObj;       // Objekt Handler aus dem Core - Klassen - System
    public $coreGlobal;     // Kopiere globale Variable aus der Start-Klasse





    // Klassen eigener Konstruktor
    function __construct($flagUseGlobalCoreClassObj=TRUE)
    {

        parent::__construct();


        // Initial Aufruf für generelle Überprüfungen (Login usw.)
        $this->loadOnInit();

    }   // END function __construct(...)





    // Initial Methode bei Aufruf/Init der Klasse
    private function loadOnInit()
    {

    }




    function checkLoginStatus()
    {
        return $this->getLoginStatus();
    }






    // Bei eingeloggtem User gibt die Methode die Login - ID zurück
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
        // TODO SAVE POST - Variable abfangen
        $userName       = $_POST['userName'];
        $userPassword   = $_POST['userPassword'];

        // TODO Query auslagern

        $query = "SELECT u.*
                            ,r.userRoleID
                            ,r.userRoleName
                              FROM user u
                                LEFT JOIN userrole r ON (u.userRoleID = r.userRoleID)
                              WHERE u.userName      = '".$userName."'
                                AND u.userPassword  = md5('".$userPassword."')
                                AND u.activeStatus  = 'yes'
                                AND r.activeStatus  = 'yes'
                                LIMIT 1";

        $result = $this->query($query);

        if ($this->num_rows($result) != 1) {

            // TODO Fehlerhaften Login ausgeben

            $this->free_result($result);
            return false;
        }

        $row = $result->fetch_object();

        $_SESSION['Login']['userID']        = $row->userID;
        $_SESSION['Login']['userName']      = $row->userName;
        $_SESSION['Login']['userRoleID']    = $row->userRoleID;
        $_SESSION['Login']['userRoleName']  = $row->userRoleName;

        $this->free_result($result);


        // Vorgang des Logins speichern
        $this->loginWriteUserLoginToDB();


        // Letzen Login einlesen
        $this->loginGetUserLastLogin();

        return true;

    }   // END function callLogin()






    // Speichert den Login-Vorgang eines Benutzers
    private function loginWriteUserLoginToDB()
    {

        // Login - Vorgang Loggen
        $query = "INSERT INTO log_user_login (userID,
									REMOTE_ADDR,
									HTTP_USER_AGENT,
									HTTP_REFERER,
									HTTP_COOKIE,
									REQUEST_URI,
									SCRIPT_NAME,
									PHP_SELF
								  ) VALUES ('".$_SESSION['Login']['userID']."',
								  	'".$_SERVER['REMOTE_ADDR']."',
								  	'".$_SERVER['HTTP_USER_AGENT']."',
								  	'".$_SERVER['HTTP_REFERER']."',
								  	'".$_SERVER['HTTP_COOKIE']."',
								  	'".$_SERVER['REQUEST_URI']."',
								  	'".$_SERVER['SCRIPT_NAME']."',
								  	'".$_SERVER['PHP_SELF']."')";

        $this->query($query);

        RETURN TRUE;

    }	// END private function loginWriteUserLoginToDB(...)





    // Liest letzte Login-Informationen eines Betnutzers
    private function loginGetUserLastLogin()
    {
        // Hole mir die Query
        $query  = "SELECT `lastLogin`
					          FROM log_user_login
					          WHERE `userID` LIKE '".$_SESSION['Login']['userID']."'
					          ORDER BY log_user_loginID DESC
					          LIMIT 0,2";

        // Führe Query aus
        $result = $this->query($query);

        if ($this->num_rows($result) >= 1) {

            $bGotLast = false;

            while($row = $result->fetch_object()){

                if (!$bGotLast){
                    // Speichere beide Info-Variable auf dateLastLogin
                    // Im zweiten Durchlauf der Schleife ist dann alles richtig.
                    // So fange ich ab, wenn der User sich zum ersten mal einlogt

                    $_SESSION['Login']['dateCurLogin']  = $row->lastLogin;
                    $_SESSION['Login']['dateLastLogin'] = $row->lastLogin;

                    $bGotLast = true;
                }
                else {
                    $_SESSION['Login']['dateLastLogin'] = $row->lastLogin;
                }

            }

        }
        else {
            $this->free_result($result);

            RETURN FALSE;
        }

        $this->free_result($result);

        RETURN TRUE;

    }	// END private function loginGetUserLastLogin(...)





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
        header('Location: '.$redirectTo.'');

        exit;

    }   // END function callLogout()




}   // END class SystemLogin extends CoreExtends