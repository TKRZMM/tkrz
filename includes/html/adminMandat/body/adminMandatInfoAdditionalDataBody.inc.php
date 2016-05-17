<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */

// Vom Benutzer übergebene Daten als Value setzen
$searchVars = array('Gültig bis'         => '',
					'Angelegt am'        => '',
					'Mandat erhalten am' => '',
					'Erste Verwendung'   => '',
					'Verwendbar bis'     => '',
					'Wirderrufen am'     => '',
					'Letzte Bearbeitung' => ''
);
?>
<br>
<div class="BodyContentOuterDivSelectDataInfoAdditional" id="AdditionalInfo" style="display: none">

	<form method="post" action="" enctype="multipart/form-data">

		<table border="0" width="100%" class="standard formBackground">
			<tr>
				<td>

					<table width="100%" border="0" class="standard formBackgroundInnerCollapse">
						<tr>
							<td colspan="2"><h2>Datum Informationen</h2></td>
						</tr>
						<tr>
							<td width="60%" class="bottomLineGreen"><h4>Centron Kunden-Nr.:</h4></td>
							<td class="bottomLineGreen textRight"><h4 id="centronNumber"></h4></td>
						</tr>
						<tr>
							<td class="bottomLineGreen"><h4>Feld</h4></td>
							<td class="bottomLineGreen textRight"><h4>Angabe</h4></td>
						</tr>

						<?php

						$cnt = 0;
						foreach($searchVars as $description => $value) {

							$cnt++;
							?>
							<tr>
								<td class="bottomLineGreen paddingFour"><?php print ($description); ?></td>
								<td id="dateInfo_<?php print ($cnt); ?>"
									class="bottomLineGreen textRight paddingFour"><?php print ($hCore->formatDateForMySQLWithNoSlash($value)); ?></td>
							</tr>
							<?php

						}
						?>
						<tr>
							<td class="paddingFour">Format Datum</td>
							<td class="textRight paddingFour">JJJJ-MM-TT</td>
						</tr>

					</table>
				</td>
			</tr>
		</table>

	</form>

</div>

