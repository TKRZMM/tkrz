<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 12:42
 */

// Basic - Header laden ... ggf. später überschreibbar
include 'includes/html/basic/head/bscHead.inc.php';



// TODO Dynamisches Laden der Framesetz ermöglichen
// Lade: Frameset - Template
include 'includes/html/framesets/frsStandard.inc.php';




// Basic - Footer laden ... ggf. später überschreibbar
include 'includes/html/basic/footer/bscFooter.inc.php';

// Achtung keine Webausgabe mehr noch dieser Zeile!!! Footer und HTML - Ende wird in loadFrameset.inc.php geladen!