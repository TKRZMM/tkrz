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
 * ==>          '-> Abstract CoreMessages                           Child
 *                  '-> Abstract CoreDebug                          Child
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
abstract class CoreMessage extends CoreDefaultConfig
{
    // Public Message - Var
    public $messages;





    // Klassen eigener Konstruktor
    function __construct()
    {

        parent::__construct();

    }   // END function __construct()






    function addMessage($var)
    {
       // echo $this->coreGlobal;

        $this->messages[] = $var;
    }

}   // END abstract class CoreMessage extends CoreDefaultConfig