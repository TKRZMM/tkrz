<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 12:07
 * //print ('<div id="containerFooter" class="container Footer">');
 */

// ReSizen erlauben NUR bei Entwickler
$reSize = '';
if ($_SESSION['Login']['userRoleID'] < 2)
	$reSize = 'ondblclick="reSize(\'Footer\');"';
?>


<div id="Footer" class="container Footer" <?php print ($reSize); ?> style="height: 15px;">
	<?php

	// Include Footer Text
	include 'stdFooter.inc.php';

	// Include Debug Ausgabe und Selektion (NUR für Entwickler)
	if ($_SESSION['Login']['userRoleID'] < 2)
		include 'includes/html/debug/DebugOutput.inc.php';

	?>
</div>
