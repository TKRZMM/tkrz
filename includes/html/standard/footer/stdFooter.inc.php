<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:49
 */

$origYear = '2016';
$curYear = date('Y');

$outInfo = $origYear;
if ($curYear != $origYear)
	$outInfo = $origYear . ' - ' . $curYear;
?>
<div style="text-align: right; color: #6f6f6f">&copy; <?php print ($outInfo); ?> by TKRZ</div>





