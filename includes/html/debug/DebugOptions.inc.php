<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */
?>

<?php
// NUR Entwickler ... Footer - Leiste "hoch-runter" fahren - Button einbelenden
if ($_SESSION['Login']['userRoleID'] < 2)
	print ('<div class="DebugButtons DebugUpDown" onclick="reSize(\'Footer\');"><i class="fa fa-arrows-v fa-lg"></i></div>');
?>

<div class="DebugButtons DebugLinks" onclick="showOnOff('containerDebugLinkList')"><i class="fa fa-bars fa-lg"></i></div>

<?php
// <div class="DebugButtons DebugSettings"><i class="fa fa-cogs fa-lg"></i></div>
// <div class="DebugButtons DebugMove" onmousedown="dragstart('containerDebugOptions')" ondblclick="sendDebugHome('containerDebugOptions')"><i class="fa fa-arrows fa-lg"></i></div>
?>

