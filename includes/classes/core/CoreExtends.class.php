<?php

/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:54
 */
class CoreExtends extends CoreObject
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


        // DefaultConfig laden?
        if ( (!isset($this->coreGlobal['Flag']['Cfg']['Loaded']['Default'])) || ($this->coreGlobal['Flag']['Cfg']['Loaded']['Default'] != 'yes') )
            $this->loadDefaultConfigs();


        // SystemConfig laden?
        if ( (!isset($this->coreGlobal['Flag']['Cfg']['Loaded']['System'])) || ($this->coreGlobal['Flag']['Cfg']['Loaded']['System'] != 'yes') )
            $this->loadSystemConfigs();


        // Datenbank - Verbindung aufbauen?
        if ( (!isset($this->coreGlobal['Objects']['DBConnection'])) || (!is_object($this->coreGlobal['Objects']['DBConnection'])) )
            $this->createDBConnection();
        else
            $this->getDBConnection();

    }   // END function __construct(...)





    // Benutze in der Klasse das globale Core-Klassen-Objekt
    private function getGlobalCoreObject()
    {

        // Sicher stellen das wir den Core-Klassen-Objekt - Handler der Basisklassen nur einmal haben / benutzen
        $this->myDynObj = CoreObject::getSingleton();

        // Lokales coreGlobal referenzieren
        $this->coreGlobal = & $this->myDynObj->coreGlobal;

    }   // END private function getGlobalCoreObject()



}   // END abstract class CoreExtends extends CoreObject