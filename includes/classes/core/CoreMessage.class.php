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




//$hCore->gCore['Messages']['Type'][]      = 'Info';
//$hCore->gCore['Messages']['Code'][]      = 'Login';
//$hCore->gCore['Messages']['Headline'][]  = 'Erfolgreicher Login!';
//$hCore->gCore['Messages']['Message'][]   = 'Willkommen '.$_SESSION['Login']['User']['userName'].'!';

    function addMessage($headline=NULL, $message, $type=NULL, $code=NULL, $explain=NULL)
    {
        $this->coreMessages['headline'][]   = $headline;
        $this->coreMessages['message'][]    = $message;
        $this->coreMessages['type'][]       = $type;
        $this->coreMessages['code'][]       = $code;
        $this->coreMessages['explain'][]    = $explain;
    }



    function messageTest()
    {
        $numArg = func_num_args();

        echo $numArg;

    }


}   // END abstract class CoreMessage extends CoreDefaultConfig