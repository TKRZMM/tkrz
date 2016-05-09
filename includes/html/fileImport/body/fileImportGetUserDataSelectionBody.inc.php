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
							<td colspan="8"><h2>Datensatz Auswahl</h2></td>
							<td><button type="reset" class="sendButton">Reset</button> <button type="submit" class="sendButton">Senden</button></td>

						</tr>
						<tr>
							<td class="bottomLineGreen textCenter"><h4>Auswahl</h4></td>
							<td class="bottomLineGreen textCenter"><h4>Aktuell in DB</h4></td>
							<td class="bottomLineGreen"><h4>Dateiname</h4></td>
							<td class="bottomLineGreen"><h4>Upload Datum</h4></td>
							<td class="bottomLineGreen"><h4>Letztes Import Datum</h4></td>
							<td class="bottomLineGreen"><h4>Import Zähler</h4></td>
							<td class="bottomLineGreen"><h4>Datei Größe</h4></td>
							<td class="bottomLineGreen"><h4>Upload Benutzer</h4></td>
							<td class="bottomLineGreen"><h4>Datei Download</h4></td>
						</tr>

						<?php
						while ($row = $hCore->coreGlobal['dbResult']->fetch_object()) {

							// Datum letzter Import
							$lastImport = '-';

							if ($row->lastImport > 0)
								$lastImport =  $row->lastImport;

							$importCounter = $row->importCounter;

							$curInDB = '';
							if ($row->enumIsCurrentInDB == 'yes')
								$curInDB = '<b>&#10003;</b>';

							?>
							<tr class="hoverTr" onclick="selUserSelectionByID('<?php print ($row->fileUploadID); ?>');">
								<td class="bottomLineGreen textCenter paddingFour"><input type="radio" required name="selFileUploadID" value="<?php print ($row->fileUploadID); ?>" id="selFileUploadID_<?php print ($row->fileUploadID); ?>"></td>
								<td class="bottomLineGreen textCenter paddingFour"><?php print ($curInDB); // Aktuell in DB ?></td>
								<td class="bottomLineGreen paddingFour"><?php print ($row->fileOriginName); // Dateiname ?></td>
								<td class="bottomLineGreen paddingFour"><?php print ($row->uploadDateTime); // Upload Datum ?></td>
								<td class="bottomLineGreen paddingFour"><?php print ($lastImport); // Letzter Import am ?></td>
								<td class="bottomLineGreen paddingFour"><?php print ($importCounter); // Import Zähler ?></td>
								<td class="bottomLineGreen paddingFour"><?php print ($hCore->formatSizeUnits($row->fileSize)); // Datei Größe ?></td>
								<td class="bottomLineGreen paddingFour"><?php print ($row->userName); // Upload Benutzer?> (<?php print ($row->userRoleName); // Upload Benutzer Rollen-Name?>)</td>
								<td class="bottomLineGreen paddingFour"><a href="<?php print ( $_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort'] . '/' . $row->downloadLink); // Datei Download ?>" target="_blank">Download</a></td>
							</tr>
							<?php
						}

						//$hCore->free_result($hCore->coreGlobal['dbResult']);
						?>

					</table>

				</td>
			</tr>
		</table>

		<input type="hidden" name="callAction" value="dbImport">
	</form>

</div>

