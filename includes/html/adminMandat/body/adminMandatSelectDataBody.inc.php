<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */


?>

	<br>

	<div class="BodyContentOuterDiv">

		<form method="post" action="" enctype="multipart/form-data">
			<div  class="buttonBoxOuter">
				<div class="buttonBox">
					<button type="submit" class="sendButton">Auswählen</button>
					<button type="reset" class="sendButton">Reset</button>
				</div>
			</div>

			<table border="0" width="100%" class="standard formBackground">
				<tr>
					<td>

						<table width="100%" border="0" class="standard formBackgroundInnerCollapse">
							<tr>
								<td colspan="10"><h2>Datensatz Auswahl</h2></td>
							</tr>
							<tr>
								<td class="bottomLineGreen textCenter"><h4>Nr.</h4></td>
								<td class="bottomLineGreen textCenter"><h4>Auswahl</h4></td>
								<td class="bottomLineGreen textCenter"><h4>Centron Kunden-Nr.</h4></td>
								<td class="bottomLineGreen textCenter"><h4>SEPA Mandats-Nr.</h4></td>
								<td class="bottomLineGreen textCenter"><h4>Verwendung bei Export</h4></td>
								<td class="bottomLineGreen"><h4>Lastschriftart</h4></td>
								<td class="bottomLineGreen"><h4>Typ</h4></td>
								<td class="bottomLineGreen"><h4>Status</h4></td>
								<td class="bottomLineGreen"><h4>IBAN</h4></td>
								<td class="bottomLineGreen"><h4>BIC</h4></td>
							</tr>

							<?php
							$cnt = 0;
							while ($row = $hCore->coreGlobal['dbResult']->fetch_object()) {

								$cnt++;

								$useOnExport = 'Nein';
								$cssClass = 'hoverTrInactive';
								if ($row->activeStatus == 'yes'){
									$useOnExport = 'Ja';
									$cssClass = 'hoverTr';
								}

								?>
								<tr class="<?php print ($cssClass); ?>"
									onclick="selUserSelectionByID('centron_mand_refID_','<?php print ($row->centron_mand_refID); ?>');"

									onmouseover="showInnerHtml(
										'<?php print ($row->personenkonto); ?>',
										'<?php ($row->dateOfExpire == '0000-00-00') ? print ('') : print ($row->dateOfExpire); ?>',
										'<?php ($row->createdOn == '0000-00-00') ? print ('') : print ($row->createdOn); ?>',
										'<?php ($row->gotMandatOn == '0000-00-00') ? print ('') : print ($row->gotMandatOn); ?>',
										'<?php ($row->dateOfFirstUse == '0000-00-00') ? print ('') : print ($row->dateOfFirstUse); ?>',
										'<?php ($row->useUntil == '0000-00-00') ? print ('') : print ($row->useUntil); ?>',
										'<?php ($row->recalledOn == '0000-00-00') ? print ('') : print ($row->recalledOn); ?>',
										'<?php ($row->lastUpdate == '0000-00-00') ? print ('') : print ($row->lastUpdate); ?>'
										);">
									<td class="bottomLineGreen textCenter paddingFour"><?php print ($cnt); ?></td>
									<td class="bottomLineGreen textCenter paddingFour"><input type="radio" required
																							  name="centron_mand_refID"
																							  value="<?php print ($row->centron_mand_refID); ?>"
																							  id="centron_mand_refID_<?php print ($row->centron_mand_refID); ?>">
									</td>
									<td class="bottomLineGreen textCenter paddingFour"><?php print ($row->personenkonto); ?></td>
									<td class="bottomLineGreen textCenter paddingFour"><?php print ($row->mandatsnummer); ?></td>
									<td class="bottomLineGreen textCenter paddingFour"><?php print ($useOnExport); ?></td>
									<td class="bottomLineGreen paddingFour"><?php print ($row->lsArt); ?></td>
									<td class="bottomLineGreen paddingFour"><?php print ($row->lsType); ?></td>
									<td class="bottomLineGreen paddingFour"><?php print ($row->lsStatus); ?></td>
									<td class="bottomLineGreen paddingFour"><?php print ($row->IBAN); ?></td>
									<td class="bottomLineGreen paddingFour"><?php print ($row->BIC); ?></td>
								</tr>
								<?php
							}

							$hCore->free_result($hCore->coreGlobal['dbResult']);
							?>

						</table>

					</td>
				</tr>
			</table>

			<input type="hidden" name="callAction" value="adminMandat">
		</form>

	</div>

<?php
// Angegebene Such-Daten - Information ausgeben
include 'adminMandatInfoSearchDataBody.inc.php';

// Zusätzliche Informationen - einbinden
include 'adminMandatInfoAdditionalDataBody.inc.php';
?>