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



$query = "SELECT * FROM user";
$result = $hCore->query($query);

while ($row = $result->fetch_object()){
    echo $row->userName."<br>";
}
$hCore->free_result($result);


