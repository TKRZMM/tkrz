<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:35
 */
// Starte: Session
session_start();




// Lade: Klassen - Autoloader
require_once 'includes/system/systemClassAutoLoad.inc.php';






// Lade: Action Steuerung via Action - KLasse
$hAction = new classes\system\SystemAction();




// $hCore->addDebugMessage('Add aus index.php');
//$hCore->simpleout('Hallo Markus');




// TODO Dynmisches Laden der Frameset - Datei geregelt über die Action
// Lade: Frameset - Setting
require_once 'includes/system/systemFramesetLoader.inc.php';


// Achtung keine Webausgabe mehr noch dieser Zeile!!! Footer und HTML - Ende wird in loadFrameset.inc.php geladen!
