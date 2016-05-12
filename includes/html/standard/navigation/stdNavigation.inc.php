<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */



// Navigation einlesen
$boolGotNav = false;    // Flag zur Erkennung gültig geladener Navigations-Punkte

$navArray = array();    // InitialisierungNavigations - Array

// Erzeuge jetzt das Navigations-Objekt
$hNav = new classes\system\SystemLayout();
if ($hNav->createNavMenueFullHandling($navArray))
	$boolGotNav = true; // Setze Flag auf ... habe Daten


// $hNav->addDebugMessage($navArray);


/*
<div id="infoField" class="infoOnHoverMenue"></div>



*/
// PHP Fehler - Abfangen wenn Index nicht gesetzt ist
if (!isset($hCore->coreGlobal['GET']['callAction']))
	$hCore->coreGlobal['GET']['callAction'] = 'Home';
?>


<div id="DivNavigationBarClicked" class="DivNavigationBar">


	<?php
	$curCallAction = 'home';

	if ( ($hCore->coreGlobal['GET']['callAction'] == $curCallAction) || ( (!isset($hCore->coreGlobal['GET']['callAction'])) && (!isset($hCore->coreGlobal['POST']['callAction'])) ) )
		$curActive = ' dropdown-active';
	else
		$curActive = '';
	?>
	<div class="dropdown">
		<button class="dropbtn <?php print ($curActive); ?>" onclick="self.location.href='<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/home'"><i class="fa fa-home fa-lg"></i>&nbsp;&nbsp;Home</button>
	</div>




	<?php
	// Setze Werte für den aktuellen Nav-Block
	$curHeadline = 'Datei Upload';
	$curCallAction = 'fileUpload';

	if ($hCore->coreGlobal['GET']['callAction'] == $curCallAction)
		$curActive = ' dropdown-active';
	else
		$curActive = '';
	?>
	<div class="dropdown">
		<button class="dropbtn <?php print ($curActive); ?>"><i class="fa fa-upload"></i>&nbsp;&nbsp;<?php print ($curHeadline);?></button>
		<div class="dropdown-content">
			<?php
			if ($boolGotNav) {

				$boolIsFirstRow = true;	// Bool für Headline -Ddurchlauf (Abstand - top der ersten Headline ist geringer)

				// Haupt - Array - Durchlauf
				foreach($navArray['NavMenue'] as $sourceTypeID => $systemTypeArray) {


					// Durchlauf Headline (Stammdaten / Buchungssatz)
					foreach($systemTypeArray as $sourceTypeName => $sourceSystemArray) {

						$curIconTag = $navArray['IconSourceType'][$sourceTypeID];

						// Bool Headline - Durchlauf (Abstand - top der ersten Headline ist geringer)
						if ($boolIsFirstRow)
							$curClass = 'dopddown-headline-first';
						else
							$curClass = 'dopddown-headline';

						$boolIsFirstRow = false;

						print ('<div class="' . $curClass . '">' . $curIconTag . '&nbsp;&nbsp;&nbsp;' . $sourceTypeName .'</div>');

						// Durchlauf Systeme
						foreach($sourceSystemArray as $sourceSystemID => $sourceSystemName) {

							$curDir = $navArray['NameSourceTypeFileUploadDir'][$sourceTypeID];

							print ('<a href="' . $_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort'] . '/' . $curCallAction . '/' . $curDir . '/' . $sourceSystemName . '">' . $sourceSystemName . '</a>');

						}	// END // Durchlauf Systeme

					}	// END // Durchlauf Headline (Stammdaten / Buchungssatz)

				}	// END // Haupt - Array - Durchlauf

			}
			?>
		</div>
	</div>




	<?php
	$curHeadline = 'Datenbank Import';
	$curCallAction = 'dbImport';

	if ($hCore->coreGlobal['GET']['callAction'] == $curCallAction)
		$curActive = ' dropdown-active';
	else
		$curActive = '';
	?>
	<div class="dropdown">
		<button class="dropbtn <?php print ($curActive); ?>"><i class="fa fa-upload"></i>&nbsp;&nbsp;<?php print ($curHeadline);?></button>
		<div class="dropdown-content">
			<?php
			if ($boolGotNav) {

				$boolIsFirstRow = true;	// Bool für Headline -Ddurchlauf (Abstand - top der ersten Headline ist geringer)

				// Haupt - Array - Durchlauf
				foreach($navArray['NavMenue'] as $sourceTypeID => $systemTypeArray) {


					// Durchlauf Headline (Stammdaten / Buchungssatz)
					foreach($systemTypeArray as $sourceTypeName => $sourceSystemArray) {

						$curIconTag = $navArray['IconSourceType'][$sourceTypeID];

						// Bool Headline - Durchlauf (Abstand - top der ersten Headline ist geringer)
						if ($boolIsFirstRow)
							$curClass = 'dopddown-headline-first';
						else
							$curClass = 'dopddown-headline';

						$boolIsFirstRow = false;

						print ('<div class="' . $curClass . '">' . $curIconTag . '&nbsp;&nbsp;&nbsp;' . $sourceTypeName .'</div>');

						// Durchlauf Systeme
						foreach($sourceSystemArray as $sourceSystemID => $sourceSystemName) {

							$curDir = $navArray['NameSourceTypeFileUploadDir'][$sourceTypeID];

							print ('<a href="' . $_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort'] . '/' . $curCallAction . '/' . $curDir . '/' . $sourceSystemName . '">' . $sourceSystemName . '</a>');

						}	// END // Durchlauf Systeme

					}	// END // Durchlauf Headline (Stammdaten / Buchungssatz)

				}	// END // Haupt - Array - Durchlauf

			}
			?>
		</div>
	</div>




	<?php
	$curHeadline = 'Datenbank Export';
	$curCallAction = 'dbExport';

	if ($hCore->coreGlobal['GET']['callAction'] == $curCallAction)
		$curActive = ' dropdown-active';
	else
		$curActive = '';
	?>
	<div class="dropdown">
		<button class="dropbtn <?php print ($curActive); ?>"><i class="fa fa-upload"></i>&nbsp;&nbsp;<?php print ($curHeadline);?></button>
		<div class="dropdown-content">
			<?php
			if ($boolGotNav) {

				$boolIsFirstRow = true;	// Bool für Headline -Ddurchlauf (Abstand - top der ersten Headline ist geringer)

				// Haupt - Array - Durchlauf
				foreach($navArray['NavMenue'] as $sourceTypeID => $systemTypeArray) {


					// Durchlauf Headline (Stammdaten / Buchungssatz)
					foreach($systemTypeArray as $sourceTypeName => $sourceSystemArray) {

						$curIconTag = $navArray['IconSourceType'][$sourceTypeID];

						// Bool Headline - Durchlauf (Abstand - top der ersten Headline ist geringer)
						if ($boolIsFirstRow)
							$curClass = 'dopddown-headline-first';
						else
							$curClass = 'dopddown-headline';

						$boolIsFirstRow = false;

						print ('<div class="' . $curClass . '">' . $curIconTag . '&nbsp;&nbsp;&nbsp;' . $sourceTypeName .'</div>');

						// Durchlauf Systeme
						foreach($sourceSystemArray as $sourceSystemID => $sourceSystemName) {

							$curDir = $navArray['NameSourceTypeFileUploadDir'][$sourceTypeID];

							print ('<a href="' . $_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort'] . '/' . $curCallAction . '/' . $curDir . '/' . $sourceSystemName . '">' . $sourceSystemName . '</a>');

						}	// END // Durchlauf Systeme

					}	// END // Durchlauf Headline (Stammdaten / Buchungssatz)

				}	// END // Haupt - Array - Durchlauf

			}
			?>
		</div>
	</div>




	<?php
	$curHeadline = 'SEPA Mandatsverwaltung';
	$curCallAction = 'adminMandat';

	if ($hCore->coreGlobal['GET']['callAction'] == $curCallAction)
		$curActive = ' dropdown-active';
	else
		$curActive = '';
	?>
	<div class="dropdown">
		<button class="dropbtn <?php print ($curActive); ?>"><i class="fa fa-university"></i>&nbsp;&nbsp;<?php print ($curHeadline);?></button>
		<div class="dropdown-content">
			<div class="dopddown-headline-first"><i class="fa fa-credit-card"></i>&nbsp;&nbsp;&nbsp; Centron</div>
			<?php print ('<a href="' . $_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort'] . '/' . $curCallAction . '/newMandat/Centron"><i class="fa fa-plus"></i>&nbsp;&nbsp;&nbsp; Neuer Eintrag</a>'); ?>
			<?php print ('<a href="' . $_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort'] . '/' . $curCallAction . '/searchEdit/Centron"><i class="fa fa-search"></i>&nbsp;&nbsp;&nbsp; Suchen / Bearbeiten</a>'); ?>
		</div>
	</div>

</div>

