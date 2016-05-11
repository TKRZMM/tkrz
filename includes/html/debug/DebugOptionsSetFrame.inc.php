<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */

$outerClass = 'DebugOptionsOuter';
$innerClass = 'DebugOptions';

// NUR Entwickler hat zusätzliche Option
if ($_SESSION['Login']['userRoleID'] < 2){
	$outerClass = 'DebugOptionsOuter DebugOptionsOuterDevelop';
	$innerClass = 'DebugOptions DebugOptionsDevelop';
}



print ('<div id="containerDebugOptions" class="container '.$outerClass.'"><div class="container '.$innerClass.'">');
include 'DebugOptions.inc.php';


print ('<div id="containerDebugLinkList" class="container DebugLinkListOuter" style="display: none;" ><div class="container DebugLinkList">');
include 'DebugLinkList.inc.php';

print ('</div></div>');
print ('</div></div>');

