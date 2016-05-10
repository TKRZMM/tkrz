<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */

?>
<div class="DivPageCallIndformationContent">

	<?php
	$preLink = '<a href="' . $_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort'] . '/home">';


	$callActionArray = array('home'        => array('label' => 'Home',
													'icon'  => '<i class="fa fa-home fa-lg"></i>'),
							 'fileUpload'  => array('label' => 'Datei - Upload',
													'icon'  => '<i class="fa fa-upload"></i>'),
							 'dbImport'    => array('label' => 'Datenbank - Import',
													'icon'  => '<i class="fa fa-sign-in fa-lg"></i>'),
							 'dbExport'    => array('label' => 'Datenbank - Export',
													'icon'  => ' <i class="fa fa-sign-out fa-lg"></i>'),
							 'adminMandat' => array('label' => 'Mandatsverwaltung',
													'icon'  => ' <i class="fa fa-university fa-lg"></i>')
	);


	$subActionArray = array('baseData'    => array('label' => 'Stammdaten',
												   'icon'  => '<i class="fa fa-user"></i>'),
							'bookingData' => array('label' => 'Buchungssatz',
												   'icon'  => '<i class="fa fa-university"></i>'),
							'searchEdit' => array('label' => 'Suchen / Bearbeiten',
												   'icon'  => '<i class="fa fa-search"></i>'),
							'newMandat' => array('label' => 'Neuer Eintrag',
												  'icon'  => '<i class="fa fa-plus"></i>')
	);


	// Home ausgeben
	if ((strlen($myCallAction = $hCore->getActionAsString('callAction')) < 1) || ($hCore->getActionAsString('callAction') == 'home')) {
		print ($callActionArray['home']['icon']);
		print ('&nbsp;&nbsp;');
		print ($callActionArray['home']['label']);
	}


	// subAction ausgeben? ... z.B. baseData ... bookingData
	if (strlen($mySubAction = $hCore->getActionAsString('subAction')) > 0) {

		if (isset($subActionArray[$mySubAction]['icon'])){
			print ($subActionArray[$mySubAction]['icon']);
		}
		print ('&nbsp;&nbsp;');
		if (isset($subActionArray[$mySubAction]['label'])) {
			print ($subActionArray[$mySubAction]['label']);
		}
	}


	// valueActon ausgeben? ... z.B. Centron
	if (strlen($myValueAction = $hCore->getActionAsString('valueAction')) > 0) {

		print ('&nbsp;&nbsp;<i class="fa fa-angle-double-right fa-lg"></i>&nbsp;&nbsp;');
		print ($myValueAction);

	}
	?>

</div>

