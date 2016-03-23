<?php

/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:51
 *
 * Vererbungsfolge der (Basis) - Klassen:
 *  Abstract CoreBase                                               Adam/Eva
 *      '-> Abstract CoreSystemConfig                               Child
 *          '-> Abstract CoreDefaultConfig                          Child
 *              '-> Abstract CoreMessages                           Child
 * ==>              '-> Abstract CoreDebug                          Child
 *                      '-> Abstract CoreQuery                      Child
 *                          '-> Abstract CoreMySQLi                 Child
 *                              '-> CoreObject                      Child
 *                                  '-> CoreExtends                 Child
 *                                      '-> ConcreteClass1          CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ...                     CoreExtends - Child - AnyCreature
 *                                      |-> ConcreteClass20         CoreExtends - Child - AnyCreature
 *
 */
abstract class CoreDebug extends CoreMessage
{

    // Initialsiere Variable
    public $messagesDebug;      // Globale DebugMessage Variable für alle weiteren Klassen
    public $messagesSimpleout;  // Globale SimpleoutMessage Variable für alle weiteren Klassen


    // Klassen eigener Konstruktor
    function __construct()
    {

        parent::__construct();

    }   // END function __construct()






    // Methode fügt eine Debugausgabe an eventuell schon vohandene Debugausgaben an.
    function addDebugMessage($var, $setExtraKey=false)
    {
        $this->messagesDebug = $var;

        if ($setExtraKey)
            $this->coreGlobal['messagesDebug'][][$setExtraKey] = $this->messagesDebug;
        else
            $this->coreGlobal['messagesDebug'][] = $this->messagesDebug;

    }   // END function addDebugMessage(...)







    // Methode fügt eine Debugausgabe an eventuell schon vohandene Simpleout Debugausgaben an.
    function simpleout($var, $setExtraKey=false)
    {
        $this->messagesSimpleout = $var;

        if ($setExtraKey)
            $this->coreGlobal['messagesSimpleout'][][$setExtraKey] = $this->messagesSimpleout;
        else
            $this->coreGlobal['messagesSimpleout'][] = $this->messagesSimpleout;
    }


}   // END abstract class CoreDebug extends CoreMessage