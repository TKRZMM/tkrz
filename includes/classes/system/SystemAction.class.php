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
class SystemAction extends CoreExtends
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

        // Login - Status prüfen
        $this->coreGlobal['Objects']['hLogin'] = new SystemLogin();

        $hLogin = $this->coreGlobal['Objects']['hLogin'];

        if (!$checkUserID = $hLogin->checkLoginStatus()) {

            // Keine userID also noch kein eingeloggter User

            // Login - Action aufgerufen? ---> Weiterleiten zur Login - Prüfung
            if ( (isset($_POST['callAction'])) && ($_POST['callAction'] == 'callLogin')) {

                // Login - Daten ok! User wurde in der Login - Klasse eingeloggt!
                if ($hLogin->callLogin()) {
                    // Setzte Default Frameset ... wird ggf. später überschrieben
                    $this->coreGlobal['Load']['Frameset'] = 'frsStandard.inc.php';
                    return true;
                }
            }

            // Setze Frameset auf Login - Maske
            $this->coreGlobal['Load']['Frameset'] = 'frsLogin.inc.php';

            return true;
        }




        // Hier geht es nur wetier wenn der User eingeloggt ist (userID vorhanden)!



        // Logout aufgerufen
        if ( (isset($_GET['callAction'])) && ($_GET['callAction'] == 'callLogout')) {
            $hLogin->callLogout();

            return true;
        }





        // Setzte Default Frameset ... wird ggf. später überschrieben
        $this->coreGlobal['Load']['Frameset'] = 'frsStandard.inc.php';

        // TODO ... Action - Steuerung!!




        return true;

    }   // END private function loadOnInit()



}   // END class SystemAction extends CoreExtends