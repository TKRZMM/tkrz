<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */

$row = $hCore->coreGlobal['dbResult']->fetch_object();

// Nach Update ... bei Fehlerhafte eingabe ... Daten aus DB verwendenden?
$boolForceUseDBData = false;
if ( (isset($hCore->coreGlobal['tmp']['forceReadDB'])) && ($hCore->coreGlobal['tmp']['forceReadDB'] == 'yes') ){

	unset($hCore->coreGlobal['tmp']['forceReadDB']);	// Wieder freigeben

	$boolForceUseDBData = true;
}

// Vom Benutzer übergebene Daten als Value setzen
$checVars = array('customerID',
				  'mandatNumber',
				  'lsArt',
				  'lsType',
				  'lsStatus',
				  'dateOfExpire',
				  'createdOn',
				  'gotMandatOn',
				  'dateOfFirstUse',
				  'useUntil',
				  'recalledOn',
				  'IBAN',
				  'BIC',
				  'lastUpdate',
				  'activeStatus'
				  );

$gotVar = array();
foreach ($checVars as $varName){
	if ( (isset($hCore->coreGlobal['POST'][$varName])) && (!$boolForceUseDBData) )
		$gotVar[$varName] = $hCore->coreGlobal['POST'][$varName];
	else{

		// Bei customerID und mandatNumber stimmt das DB - Feld nicht überein... hier passe ich das manuell an
		if ($varName == 'customerID')
			$rowName = 'personenkonto';
		elseif ($varName == 'mandatNumber')
			$rowName = 'mandatsnummer';
		else
			$rowName = $varName;

		// Leere bzw. noch nicht gesetzte Datum-Felder auf "leer" belassen
		$rowValue = $row->$rowName;
		if ($rowValue == '0000-00-00')
			$rowValue = '';


		if ($varName == 'activeStatus'){
			$rowValue = 'Nein';
			if ($row->activeStatus == 'yes')
				$rowValue = 'Ja';
		}


		$gotVar[$varName] = $rowValue;
	}
}

?>
<br>

<div class="BodyContentOuterDiv">

	<form method="post" action="" enctype="multipart/form-data">
		<div  class="buttonBoxOuter">
			<div class="buttonBox">
				<button type="submit" class="sendButton">Bearbeiten</button>
				<button type="submit" name="delMandat" value="yes" class="sendButtonDelete" onclick="return confirm('Soll das Mandat wirklich gelöscht werden?'); ">Löschen</button>
				<button type="reset" class="sendButton">Reset</button>
			</div>
		</div>

		<table border="0" width="100%" class="standard formBackground">
			<tr>
				<td>

					<table width="100%" border="0" class="standard formBackgroundInnerCollapse">
						<tr>
							<td colspan="5"><h2>Datensatz Bearbeiten</h2></td>
						</tr>
						<tr>
							<td>&#10038; Centron Kunden-Nr.:<br><input type="text" name="customerID" class="stdinput" value="<?php print ($gotVar['customerID']); ?>"  pattern=".{1,20}" required title="Benötigt zwischen 1 und 20 Zeichen!" maxlength="20" autocomplete="off" autofocus></td>
							<td>&#10038; SEPA Mandats-Nr.:<br><input type="text" name="mandatNumber" class="stdinput" value="<?php print ($gotVar['mandatNumber']); ?>"  pattern=".{1,20}" required title="Benötigt zwischen 1 und 20 Zeichen!" maxlength="20" autocomplete="off"></td>
						</tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr>
							<td>Lastschriftart<br>
								<select name="lsArt" size="1" class="selectInput">
									<option value="Basis" <?php if ( ($gotVar['lsArt'] == 'Basis') || ($gotVar['lsArt'] == '') ){ print ('selected'); } ?>>Basis</option>
									<option value="B2B" <?php if ($gotVar['lsArt'] == 'B2B'){ print ('selected'); } ?>>B2B</option>
									<option value="Unbekannt" <?php if ($gotVar['lsArt'] == 'Unbekannt'){ print ('selected'); } ?>>Unbekannt</option>
								</select>
							</td>


							<td>Typ<br>
								<select name="lsType" size="1" class="selectInput">
									<option value="Widerkehrend" <?php if ( ($gotVar['lsType'] == 'Widerkehrend') || ($gotVar['lsType'] == '') ){ print ('selected'); } ?>>Widerkehrend</option>
									<option value="Einmalig" <?php if ($gotVar['lsType'] == 'Einmalig'){ print ('selected'); } ?>>Einmalig</option>
									<option value="Unbekannt" <?php if ($gotVar['lsType'] == 'Unbekannt'){ print ('selected'); } ?>>Unbekannt</option>
								</select>
							</td>

							<td>Status<br>
								<select name="lsStatus" size="1" class="selectInput">
									<option value="Aktiv" <?php if ( ($gotVar['lsStatus'] == 'Aktiv') || ($gotVar['lsStatus'] == '') ){ print ('selected'); } ?>>Aktiv</option>
									<option value="Inaktiv" <?php if ($gotVar['lsStatus'] == 'Inaktiv'){ print ('selected'); } ?>>Inaktiv</option>
									<option value="Widerrufen" <?php if ($gotVar['lsStatus'] == 'Widerrufen'){ print ('selected'); } ?>>Widerrufen</option>
									<option value="Unbekannt" <?php if ($gotVar['lsStatus'] == 'Unbekannt'){ print ('selected'); } ?>>Unbekannt</option>
								</select>
							</td>
						</tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr>
							<td>Gültig bis<br><input type="text" id="dateOfExpire" name="dateOfExpire" class="tcal calender" value="<?php print ($gotVar['dateOfExpire']); ?>">&nbsp;<i onclick="delDateFieldByID('dateOfExpire');" class="delDateField">&nbsp;&#10008;</i></td>
							<td>Angelegt am<br><input type="text" readonly id="createdOn" name="createdOn" class="tcal calender" value="<?php print ($gotVar['createdOn']); ?>">&nbsp;<i onclick="delDateFieldByID('createdOn');" class="delDateField">&nbsp;&#10008;</i></td>
							<td>Mandat erhalten am<br><input type="text" readonly id="gotMandatOn" name="gotMandatOn" class="tcal calender" value="<?php print ($gotVar['gotMandatOn']); ?>">&nbsp;<i onclick="delDateFieldByID('gotMandatOn');" class="delDateField">&nbsp;&#10008;</i></td>
						</tr>
						<tr><td colspan="4">&nbsp;</td></tr>
						<tr><td colspan="4">&nbsp;</td></tr>
						<tr>
							<td>Erste Verwendung am<br><input type="text" readonly id="dateOfFirstUse" name="dateOfFirstUse" class="tcal calender" value="<?php print ($gotVar['dateOfFirstUse']); ?>">&nbsp;<i onclick="delDateFieldByID('dateOfFirstUse');" class="delDateField">&nbsp;&#10008;</i></td>
							<td>Verwendbar bis<br><input type="text" readonly id="useUntil" name="useUntil" class="tcal calender" value="<?php print ($gotVar['useUntil']); ?>">&nbsp;<i onclick="delDateFieldByID('useUntil');" class="delDateField">&nbsp;&#10008;</i></td>
							<td>Widerrufen am<br><input type="text" readonly id="recalledOn" name="recalledOn" class="tcal calender" value="<?php print ($gotVar['recalledOn']); ?>">&nbsp;<i onclick="delDateFieldByID('recalledOn');" class="delDateField">&nbsp;&#10008;</i></td>
						</tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr>
							<td>IBAN<br><input type="text" name="IBAN" class="stdinput" value="<?php print ($gotVar['IBAN']); ?>" autocomplete="off"></td>
							<td>BIC<br><input type="text" name="BIC" class="stdinput" value="<?php print ($gotVar['BIC']); ?>" autocomplete="off"></td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr>
							<td colspan="5" class="textRight">&#10038; Eingabe - Pflicht</td>
						</tr>
						<tr>
							<td colspan="5" class="textRight">Verwendung bei Export: <?php print ($gotVar['activeStatus']); ?></td>
						</tr>
						<tr>
							<td colspan="5" class="textRight">Letzte Bearbeitung: <?php print ($gotVar['lastUpdate']); ?></td>
						</tr>

					</table>

				</td>
			</tr>
		</table>

		<input type="hidden" name="callAction" value="adminMandat">
		<input type="hidden" name="callUpdate" value="yes">
		<input type="hidden" name="centron_mand_refID" value="<?php print ($hCore->coreGlobal['POST']['centron_mand_refID']); ?>">
	</form>

</div>

