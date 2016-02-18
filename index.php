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







echo "<pre>";
print_r($hCore->coreGlobal);
echo "</pre><br>";
