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
				<button type="submit" class="sendButton">Senden</button>
				<button type="reset" class="sendButton">Reset</button><br>
			</div>
		</div>

		<table border="0" width="100%" class="standard formBackground">
			<tr>
				<td>

					<table width="100%" border="0" class="standard formBackgroundInnerCollapse">
						<tr>
							<td colspan="7"><h2>Information Datensätze</h2></td>
						</tr>
						<tr>
							<td class="bottomLineGreen textCenter"><h4>&sum; Datensätze</h4></td>
							<td class="bottomLineGreen"><h4>Import Datum</h4></td>
							<td class="bottomLineGreen"><h4>Rechnungs - Datum (Liste)</h4></td>
							<td class="bottomLineGreen"><h4>Erlöskonten (Liste)</h4></td>
							<td class="bottomLineGreen"><h4>Kostenstelle (Liste)</h4></td>
							<td class="bottomLineGreen"><h4>Erhobene MwSt (Liste)</h4></td>
							<td class="bottomLineGreen"><h4>Upload Benutzer</h4></td>
						</tr>

						<tr>
							<td class="bottomLineGreen textCenter textTop"><?php print ($hCore->coreGlobal['informationArray']['numDatasets']); // Datensätze ?></td>
							<td class="bottomLineGreen textTop"><?php foreach($hCore->coreGlobal['informationArray']['ImportDatumArray'] as $importDate) {
									print ($importDate . "<br>"); // Import Datum
								} ?></td>
							<td class="bottomLineGreen textTop"><?php foreach($hCore->coreGlobal['informationArray']['RDatumArray'] as $rDatum) {
									print ($rDatum . "<br>"); // Rechnungs - Datum (Liste)
								} ?></td>
							<td class="bottomLineGreen textTop"><?php foreach($hCore->coreGlobal['informationArray']['Erloeskonten'] as $Erloeskonto) {
									print ($Erloeskonto . "<br>"); // Erlöskonten (Liste)
								} ?></td>
							<td class="bottomLineGreen textTop"><?php foreach($hCore->coreGlobal['informationArray']['Kostenstellen'] as $Kostenstelle) {
									print ($Kostenstelle . "<br>"); // Kostenstelle (Liste)
								} ?></td>
							<td class="bottomLineGreen textTop"><?php foreach($hCore->coreGlobal['informationArray']['MwSts'] as $MwSt) {
									print ($MwSt . " %<br>");	// Erhobene MwSt (Liste)
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

