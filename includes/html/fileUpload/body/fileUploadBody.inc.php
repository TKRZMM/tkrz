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
				<td colspan="2"><h2>Datei Auswahl</h2></td>
				<td><button type="reset" class="sendButton">Reset</button> <button type="submit" class="sendButton">Senden</button></td>
			</tr>
			<tr>
				<td colspan="2"><input class="fileUpload" required type="file" name="file" size="40" maxlength="100000"></td>
			</tr>
		</table>

		<input type="hidden" name="callAction" value="fileUpload">
	</form>

</div>

