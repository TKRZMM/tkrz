<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */
print ('<div id="containerBody" class="container BodyContent" style="bottom: 36px;">');

include $hCore->coreGlobal['Load']['IncludeBody'];

// Debug - (Simpleout) - Messages ausgeben
include 'includes/html/debug/DebugOutputMessages.inc.php';

print ('</div>');
