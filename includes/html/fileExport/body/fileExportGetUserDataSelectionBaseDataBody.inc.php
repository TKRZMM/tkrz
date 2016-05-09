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

		<table border="0" width="100%" class="standard formBackground">
			<tr>
				<td>

					<table width="100%" border="0" class="standard formBackgroundInnerCollapse">
						<tr>
							<td colspan="6"><h2>Information Datensätze</h2></td>
							<td>
								<button type="reset" class="sendButton">Reset</button>
								<button type="submit" class="sendButton">Senden</button>
							</td>

						</tr>
						<tr>
							<td class="bottomLineGreen textCenter"><h4>&sum; Datensätze</h4></td>
							<td class="bottomLineGreen textCenter"><h4>&sum; Mandatsreferenzen</h4></td>
							<td class="bottomLineGreen"><h4>Neuster Datensatz</h4></td>
							<td class="bottomLineGreen"><h4>Ältester Datensatz</h4></td>
							<td class="bottomLineGreen"><h4>Sammelkonto (Liste)</h4></td>
							<td class="bottomLineGreen"><h4>Zahlungsarten (Liste)</h4></td>
							<td class="bottomLineGreen"><h4>Upload Benutzer</h4></td>
						</tr>

						<tr>
							<td class="bottomLineGreen textCenter textTop"><?php print ($hCore->coreGlobal['informationArray']['numDatasets']); // Datensätze ?></td>
							<td class="bottomLineGreen textCenter textTop"><?php print ($hCore->coreGlobal['informationArray']['numMandate']); // Datensätze ?></td>
							<td class="bottomLineGreen textTop"><?php print ($hCore->coreGlobal['informationArray']['lastDataset']); // Neuster Datensatz ?></td>
							<td class="bottomLineGreen textTop"><?php print ($hCore->coreGlobal['informationArray']['oldestDataset']); // Ältester Datensatz ?></td>
							<td class="bottomLineGreen textTop"><?php foreach($hCore->coreGlobal['informationArray']['Sammelkonten'] as $Sammelkonto) {
									print ($Sammelkonto . "<br>"); // Sammelkonto (Liste)
								} ?></td>
							<td class="bottomLineGreen textTop"><?php foreach($hCore->coreGlobal['informationArray']['Zahlungsarten'] as $Zahlungsart) {
									print ($Zahlungsart . "<br>");	// Zahlungsarten (Liste)
								}  ?></td>
							<td class="bottomLineGreen textTop"><?php print ($hCore->coreGlobal['informationArray']['uploadUserName']); // Upload Benutzer?></td>
						</tr>

					</table>

				</td>
			</tr>
		</table>

		<input type="hidden" name="callAction" value="dbExport">
		<input type="hidden" name="dbExportConfirmed" value="true">
	</form>

</div>

