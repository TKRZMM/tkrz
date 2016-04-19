<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */
?>
<div id="infoField" class="infoOnHoverMenue"></div>


<div id="infoFieldClicked" class="infoOnHoverMenueClicked">
	<?php
	$preLink = '<a href="' . $_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort'] . '/home">';


	$callActionArray = array('home'       => array('label' => 'HOME</a>',
												   'icon'  => $preLink . '<i class="fa fa-home fa-lg"></i>'),
							 'fileUpload' => array('label' => 'Datei - Upload',
												   'icon'  => '<i class="fa fa-upload"></i>'),
							 'dbImport'   => array('label' => 'Datenbank - Import',
												   'icon'  => '<i class="fa fa-sign-in fa-lg"></i>'),
							 'dbExport'   => array('label' => 'Datenbank - Export',
												   'icon'  => ' <i class="fa fa-sign-out fa-lg"></i>')
	);


	$subActionArray = array('baseData'    => array('label' => 'Stammdaten',
												   'icon'  => '<i class="fa fa-user"></i>'),
							'bookingData' => array('label' => 'Buchungssatz',
												   'icon'  => '<i class="fa fa-university"></i>')
	);


	// callAction ausgeben?
	if ((strlen($myCallAction = $hCore->getActionAsString('callAction')) > 0) && ($hCore->getActionAsString('callAction') != 'home')) {

		// Home zusätzlich ausgeben
		print ($callActionArray['home']['icon']);
		print ('&nbsp;&nbsp;&nbsp;');
		print ($callActionArray['home']['label']);
		print ('&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right fa-lg"></i>&nbsp;&nbsp;&nbsp;');

		// callAction ausgeben!
		print ($callActionArray[$myCallAction]['icon']);
		print ('&nbsp;&nbsp;&nbsp;');
		print ($callActionArray[$myCallAction]['label']);
	}



	// subAction ausgeben? ... z.B. baseData ... bookingData
	if (strlen($mySubAction = $hCore->getActionAsString('subAction')) > 0) {

		print ('&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right fa-lg"></i>&nbsp;&nbsp;&nbsp;');
		print ($subActionArray[$mySubAction]['icon']);
		print ('&nbsp;&nbsp;&nbsp;');
		print ($subActionArray[$mySubAction]['label']);

	}



	// valueActon ausgeben? ... z.B. Centron
	if (strlen($myValueAction = $hCore->getActionAsString('valueAction')) > 0) {

		print ('&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-double-right fa-lg"></i>&nbsp;&nbsp;&nbsp;');
		print ($myValueAction);

	}

	?>
</div>

