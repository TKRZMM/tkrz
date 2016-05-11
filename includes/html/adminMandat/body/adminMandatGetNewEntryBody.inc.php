<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */

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
				  'recalledOn',
				  'IBAN',
				  'BIC'
				  );

$gotVar = array();
foreach ($checVars as $varName){
	if (isset($hCore->coreGlobal['POST'][$varName]))
		$gotVar[$varName] = $hCore->coreGlobal['POST'][$varName];
	else
		$gotVar[$varName] = '';
}

?>
<br>

<div class="BodyContentOuterDiv">

	<form method="post" action="" enctype="multipart/form-data">

		<table border="0" width="100%" class="standard formBackground">
			<tr>
				<td>

					<table width="100%" border="0" class="standard formBackgroundInnerCollapse">
						<tr>
							<td colspan="4"><h2>Datensatz Eingabe</h2></td>
							<td>
								<button type="reset" class="sendButton">Reset</button>
								<button type="submit" class="sendButton">Senden</button>
							</td>
						</tr>
						<tr>
							<td>&#10038; Centron Kunden-Nr.:<br><input type="text" name="customerID" class="stdinput" value="<?php print ($gotVar['customerID']); ?>"  pattern=".{1,20}" required title="Benötigt zwischen 1 und 20 Zeichen!" maxlength="20" autocomplete="off"></td>
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
							<td>Gültig bis<br><input type="text" readonly name="dateOfExpire" class="tcal calender" value="<?php print ($gotVar['dateOfExpire']); ?>"></td>
							<td>Angelegt am<br><input type="text" readonly name="createdOn" class="tcal calender" value="<?php print ($gotVar['createdOn']); ?>"></td>
							<td>Mandat erhalten am<br><input type="text" readonly name="gotMandatOn" class="tcal calender" value="<?php print ($gotVar['gotMandatOn']); ?>"></td>
							<td>Erste Verwendung am<br><input type="text" readonly name="dateOfFirstUse" class="tcal calender" value="<?php print ($gotVar['dateOfFirstUse']); ?>"></td>
							<td>Widerrufen am<br><input type="text" readonly name="recalledOn" class="tcal calender" value="<?php print ($gotVar['recalledOn']); ?>"></td>
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

					</table>

				</td>
			</tr>
		</table>

		<input type="hidden" name="callAction" value="adminMandat">
	</form>

</div>

