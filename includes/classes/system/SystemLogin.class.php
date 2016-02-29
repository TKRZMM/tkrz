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



    function initCheckLoginStatus()
    {
        $this->addDebugMessage('Add von SystemLogin');
        $this->addDebugMessage("hallo markus");
    }



}   // END class SystemLogin extends CoreExtends