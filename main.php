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



// Lade: Frameset - Setting
require_once 'includes/system/systemFramesetLoader.inc.php';


// Achtung keine Webausgabe mehr noch dieser Zeile!!! Footer und HTML - Ende wird in loadFrameset.inc.php geladen!
