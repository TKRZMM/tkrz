<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 12:42
 */

// Basic - Header laden ... ggf. später überschreibbar
include 'includes/html/basic/head/bscHead.inc.php';



// Lade: Frameset - Template
$curFrameset = 'includes/html/framesets/' . $hCore->getFrameset();
include($curFrameset);



// Basic - Footer laden ... ggf. später überschreibbar
include 'includes/html/basic/footer/bscFooter.inc.php';

// Achtung keine Webausgabe mehr noch dieser Zeile!!! Footer und HTML - Ende wird in loadFrameset.inc.php geladen!