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
		<div class="buttonBoxOuter">
			<div class="buttonBox">
				<button type="submit" class="sendButton">Senden</button>
				<button type="reset" class="sendButton">Reset</button>
				<br>
			</div>
		</div>

		<table border="0" width="100%" class="standard formBackground">
			<tr>
				<td colspan="0"><h2>Datei Auswahl</h2></td>
			</tr>
			<tr>
				<td colspan="0"><input class="fileUpload" required type="file" name="file" size="40" maxlength="100000">
				</td>
			</tr>
		</table>

		<input type="hidden" name="callAction" value="fileUpload">
	</form>

</div>

