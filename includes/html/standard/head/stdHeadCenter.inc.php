<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 09:14
 */
if (isset($hCore->coreMessages['headline'])) {

	foreach($hCore->coreMessages['headline'] as $index => $headline) {

		$indexInfo = $index + 1;


		// Typ ... error, info, ok usw abhandeln
		$curClass = 'bgMessageStandard';
		$curTypeIndx = strtolower($hCore->coreMessages['type'][$index]);

		if ($curTypeIndx == 'fehler')
			$curClass = 'bgMessageError';

		elseif ($curTypeIndx == 'erfolg')
			$curClass = 'bgMessageDone';

		elseif ($curTypeIndx == 'warnung')
			$curClass = 'bgMessageWarning';


		?>

		<div style="padding-top: 5px; padding-left: 8%">

			<?php
			if ($index > 0)
				print ('<br>');
			?>

			<table border="0" width="90%" class="standard infoMessage">
				<tr>
					<td>
						<table border="0" width="100%">
							<tr>
								<td width="20px" class="bgMessageStandard textCenter borderGreenShadow">
									<i class="fa fa-bullhorn"></i>
								</td>

								<td width="50px" class="textRight">
									Nr. <i class="fa fa-arrow-right"></i>&nbsp;
								</td>

								<td width="100px" class="bgMessageStandard textCenter borderGreenShadow">
									# <?php print ($indexInfo); ?>
								</td>


								<td width="90px" class="textRight">
									Typ <i class="fa fa-arrow-right"></i>&nbsp;
								</td>

								<td width="100px" class="<?php print ($curClass); ?> textCenter borderGreenShadow">
									<?php print ($hCore->coreMessages['type'][$index]); ?>
								</td>


								<td width="90px" class="textRight">
									Kategorie <i class="fa fa-arrow-right"></i>&nbsp;
								</td>

								<td width="140px" class="bgMessageStandard textCenter borderGreenShadow">
									<?php print ($hCore->coreMessages['code'][$index]); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td>
						<table style="padding-left: 50px" border="0" width="100%">
							<tr>
								<td width="90px" class="textRight">
									&nbsp;
								</td>

								<td class="bgMessageStandard borderGreenShadow" align="left">
									<?php print ($hCore->coreMessages['headline'][$index]); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>


				<tr>
					<td>
						<table style="padding-left: 150px" border="0" width="100%">
							<tr>
								<td width="90px" class="textRight textTop">
									&nbsp;
								</td>

								<td class="bgMessageStandard borderGreenShadow" align="left">
									<?php print ($hCore->coreMessages['message'][$index]); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<?php
				if ($hCore->coreMessages['explain'][$index]) {
					?>

					<tr>
						<td>
							<table style="padding-left: 150px" border="0" width="100%">
								<tr>
									<td width="90px" class="textRight textTop">
										Anmerkung &nbsp;
									</td>

									<td class="bgMessageStandard borderGreenShadow" align="left">
										<?php print ($hCore->coreMessages['explain'][$index]); ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<?php
				}
				?>
			</table>

		</div>

		<?php
	}

	print ('<br>');

}
?>
