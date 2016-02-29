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


        // Benutze das globale Core-Klassen-Objekt in der Klasse!
        if ($flagUseGlobalCoreClassObj)
            $this->getGlobalCoreObject();


        // Initial Aufruf für generelle Überprüfungen (Login usw.)
        $this->loadOnInit();

    }   // END function __construct(...)





    // Benutze in der Klasse das globale Core-Klassen-Objekt
    private function getGlobalCoreObject()
    {

        // Sicher stellen das wir den Core-Klassen-Objekt - Handler der Basisklassen nur einmal haben / benutzen
        $this->myDynObj = CoreObject::getSingleton();

        // Lokales coreGlobal referenzieren
        $this->coreGlobal = & $this->myDynObj->coreGlobal;

    }   // END private function getGlobalCoreObject()





    // Initial Methode bei Aufruf/Init der Klasse
    private function loadOnInit()
    {
        $this->coreGlobal['Objects']['hLogin'] = new SystemLogin();

        $hLogin = $this->coreGlobal['Objects']['hLogin'];

        // Login - Status prüfen
        $hLogin->initCheckLoginStatus();
    }



}   // END class SystemAction extends CoreExtends