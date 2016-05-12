<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */

// Vom Benutzer übergebene Daten als Value setzen
$searchVars = array('customerID'     => 'Centron Kunden-Nr.',
					'mandatNumber'   => 'SEPA Mandats-Nr.',
					'lsArt'          => 'Lastschriftart',
					'lsType'         => 'Typ',
					'lsStatus'       => 'Status',
					'dateOfExpire'   => 'Gültig bis',
					'createdOn'      => 'Angelegt am',
					'gotMandatOn'    => 'Mandat erhalten',
					'dateOfFirstUse' => 'Erste Verwendung',
					'recalledOn'     => 'Widerrufen am',
					'IBAN'           => 'IBAN',
					'BIC'            => 'BIC'
);
?>
<br>
<div class="BodyContentOuterDivSelectDataInfo">

	<form method="post" action="" enctype="multipart/form-data">

		<table border="0" width="100%" class="standard formBackground">
			<tr>
				<td>

					<table width="100%" border="0" class="standard formBackgroundInnerCollapse">
						<tr>
							<td colspan="2"><h2>Such - Angaben</h2></td>
						</tr>
						<tr>
							<td class="bottomLineGreen"><h4>Feld</h4></td>
							<td class="bottomLineGreen textRight"><h4>Angabe</h4></td>
						</tr>

						<?php


						foreach($searchVars as $varName => $description) {

							if (isset($hCore->coreGlobal['POST'][$varName]))
								$gotVar[$varName] = $hCore->coreGlobal['POST'][$varName];
							else
								$gotVar[$varName] = '';

							?>
							<tr>
								<td class="bottomLineGreen paddingFour"><?php print ($description); ?></td>
								<td class="bottomLineGreen textRight paddingFour"><?php print ($hCore->formatDateForMySQLWithNoSlash($gotVar[$varName])); ?></td>
							</tr>
							<?php

						}

						$likeSearch = 'Nein';
						if ((isset($hCore->coreGlobal['POST']['likeSearchEnable'])) && ($hCore->coreGlobal['POST']['likeSearchEnable'] == 'on'))
							$likeSearch = 'Ja';

						print ('
							<tr>
								<td><h4>Teilsuche erlaubt</h4></td4>
								<td class="textRight"><h4>' . $likeSearch . '</h4></td>
							</tr>
							');
						?>

					</table>
				</td>
			</tr>
		</table>

	</form>

</div>

