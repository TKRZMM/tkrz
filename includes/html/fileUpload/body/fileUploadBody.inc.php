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
				<td colspan="2"><h2>Datei Upload</h2></td>
			</tr>
			<tr>
				<td><input class="fileUpload" required type="file" name="file" size="40" maxlength="100000"></td>
				<td><button type="submit" class="sendButton">Senden</button></td>
			</tr>
		</table>

		<input type="hidden" name="callAction" value="fileUpload">
	</form>

</div>

