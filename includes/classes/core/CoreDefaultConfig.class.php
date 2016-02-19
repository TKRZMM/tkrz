<?php

/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:50
 */
abstract class CoreDefaultConfig extends CoreSystemConfig
{

    const FULLCONFIGFILE = 'includes/config/databaseConfig.inc.ini';





    // Klassen eigener Konstruktor
    function __construct()
    {

        parent::__construct();

    }   // END function __construct()





    // Lade Config - File
    function loadDefaultConfig()
    {

        //TODO Config existiert nicht abhandeln
        if (!file_exists(self::FULLCONFIGFILE))
            return false;


        //TODO Parse Fehler in Config - Datei abhandeln
        if (!$iniArray = @parse_ini_file(self::FULLCONFIGFILE, TRUE))
            return false;


        // Übergebe an Methode zur Session - Übergabe
        $this->setDefaultConfig($iniArray);


        // Config als geladen setzen
        $this->coreGlobal['Cfg']['Loaded']['Default'] = 'yes';

    }   // END function loadConfig()





    // Setzt die geladenen Config - Werte in der Session
    private function setDefaultConfig($getConfigArray)
    {
        $_SESSION = $getConfigArray;

    }   // END private function setDefaultConfig($getConfigArray)



}   // END abstract class CoreDefaultConfig extends CoreSystemConfig