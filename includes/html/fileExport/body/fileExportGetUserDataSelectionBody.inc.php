<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */

?>
<br>

<div style="position: absolute; left: 280px; right: 300px;">

	<form method="post" action="" enctype="multipart/form-data">

		<table border="0" width="100%" class="standard formBackground">
			<tr>
				<td>

					<table width="100%" border="0" class="standard formBackgroundInnerCollapse">
						<tr>
							<td colspan="6"><h2>Information Datensätze</h2></td>

						</tr>
						<tr>
							<td class="bottomLineGreen textCenter"><h4>&sum; Datensätze</h4></td>
							<td class="bottomLineGreen"><h4>Neuster Datensatz</h4></td>
							<td class="bottomLineGreen"><h4>Ältester Datensatz</h4></td>
							<td class="bottomLineGreen"><h4>Sammelkonto (Liste)</h4></td>
							<td class="bottomLineGreen"><h4>Zahlungsarten (Liste)</h4></td>
							<td class="bottomLineGreen"><h4>Upload Benutzer</h4></td>
						</tr>

						<?php
						/*
						while ($row = $hCore->coreGlobal['dbResult']->fetch_object()) {

							// Datum letzter Import
							$lastImport = '-';

							if ($row->lastImport > 0)
								$lastImport =  $row->lastImport;

							$importCounter = $row->importCounter;

							?>
							<tr>
								<td class="bottomLineGreen textCenter"><input type="radio" required name="selFileUploadID" value="<?php print ($row->fileUploadID); ?>"></td>
								<td class="bottomLineGreen"><?php print ($row->fileOriginName); // Dateiname ?></td>
								<td class="bottomLineGreen"><?php print ($row->uploadDateTime); // Upload Datum ?></td>
								<td class="bottomLineGreen"><?php print ($lastImport); // Letzter Import am ?></td>
								<td class="bottomLineGreen"><?php print ($importCounter); // Import Zähler ?></td>
								<td class="bottomLineGreen"><?php print ($hCore->formatSizeUnits($row->fileSize)); // Datei Größe ?></td>
								<td class="bottomLineGreen"><?php print ($row->userName); // Upload Benutzer?> (<?php print ($row->userRoleName); // Upload Benutzer Rollen-Name?>)</td>
								<td class="bottomLineGreen"><a href="<?php print ( $_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort'] . '/' . $row->downloadLink); // Datei Download ?>" target="_blank">Download</a></td>
							</tr>
							<?php
						}
*/
						//$hCore->free_result($hCore->coreGlobal['dbResult']);
						?>
						<tr>
							<td colspan="6">
								<button type="reset" class="sendButton">Reset</button> <button type="submit" class="sendButton">Senden</button>
							</td>
						</tr>


					</table>

				</td>
			</tr>
		</table>

		<input type="hidden" name="callAction" value="dbExport">
	</form>

</div>

