<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 13:31
 */

// TODO Dynamisches Laden der Header-Datei
// Lade: Header - Datei !Kann durch die Action - Steuerung überschrieben werden
include 'includes/html/standard/head/stdHeadSetFrame.inc.php';



// Dynamisches Laden der Debug-Optionen
// Lade: DebugOptionen - Datei ! ... Wenn userRoleID kleiner 6 hat der User Zugriff auf die Leiste
if ($_SESSION['Login']['userRoleID'] < 6)
	include 'includes/html/debug/DebugOptionsSetFrame.inc.php';



// TODO ... Prüfen ob Frameset-Datei existiert
// Lade: Body - Datei! Kann durch die Action - Steuerung überschrieben werden
if (isset($hCore->coreGlobal['Load']['FramesetBody']))
	include 'includes/html/' . $hCore->coreGlobal['Load']['FramesetBody'];    // Aufruf durch Action - Steuerung
else
	include 'includes/html/standard/body/stdBodySetFrame.inc.php';            // Default



// TODO Dynamisches Laden der Information-Datei
// Lade: Information - Datei !Kann durch die Action - Steuerung überschrieben werden
include 'includes/html/standard/information/stdInformationSetFrame.inc.php';



// TODO Dynamisches Laden der Navigations-Datei
// Lade: Navigation - Datei !Kann durch die Action - Steuerung überschrieben werden
include 'includes/html/standard/navigation/stdNavigationSetFrame.inc.php';



// TODO Dynamisches Laden der Footer-Datei
// Lade: Footer - Datei !Kann durch die Action - Steuerung überschrieben werden
include 'includes/html/standard/footer/stdFooterSetFrame.inc.php';
