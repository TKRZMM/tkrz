<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 25.02.2016
 * Time: 12:51
 */

print("<table border='1' class=\"standard debugInformation\"><tr><td>");


$query = "SELECT * FROM user";
$result = $hCore->query($query);

while ($row = $result->fetch_object()){
    echo $row->userName."<br>";
}
$hCore->free_result($result);



echo "<hr>";
echo "<pre>";
echo "hCore->coreGlobal<br>";
print_r($hCore->coreGlobal);
echo "</pre><br>";



echo "<hr>";
echo "<pre>";
echo "SESSION<br>";
print_r($_SESSION);
echo "</pre><br>";

print ("</td></tr></table>");