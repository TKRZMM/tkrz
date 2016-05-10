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
							<td colspan="4"><h2>Datensatz Eingabe</h2></td>
							<td>
								<button type="reset" class="sendButton">Reset</button>
								<button type="submit" class="sendButton">Senden</button>
							</td>
						</tr>
						<tr>
							<td>&#10038; Centron Kunden-Nr.:<br><input type="text" name="customerID" class="stdinput" value=""  pattern=".{1,20}" required title="Benötigt zwischen 1 und 20 Zeichen!" maxlength="20"></td>
							<td>&#10038; Mandats-Nr.:<br><input type="text" name="mandatNumber" class="stdinput" value=""  pattern=".{1,20}" required title="Benötigt zwischen 1 und 20 Zeichen!" maxlength="20"></td>
						</tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr>
							<td>Lastschriftart<br>
								<select name="lsArt" size="1" class="selectInput">
									<option value="Basis" selected>Basis</option>
									<option value="B2B">B2B</option>
									<option value="unbekannt">Unbekannt</option>
								</select>
							</td>


							<td>Typ<br>
								<select name="lsType" size="1" class="selectInput">
									<option value="Widerkehrend" selected>Widerkehrend</option>
									<option value="Einmalig">Einmalig</option>
									<option value="Unbekannt">Unbekannt</option>
								</select>
							</td>

							<td>Status<br>
								<select name="lsStatus" size="1" class="selectInput">
									<option value="Aktiv" selected>Aktiv</option>
									<option value="Inaktiv">Inaktiv</option>
									<option value="Unbekannt">Unbekannt</option>
								</select>
							</td>
						</tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr>
							<td>Gültig bis<br><input type="text" readonly name="dateOfExpire" class="tcal calender" value=""></td>
							<td>Angelegt am<br><input type="text" readonly name="createdOn" class="tcal calender" value=""></td>
							<td>Mandat erhalten am<br><input type="text" readonly name="GotMandatOn" class="tcal calender" value=""></td>
							<td>Erste Verwendung am<br><input type="text" readonly name="dateOfFirstUse" class="tcal calender" value=""></td>
							<td>Widerrufen am<br><input type="text" readonly name="recalledOn" class="tcal calender" value=""></td>
						</tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr>
							<td>IBAN<br><input type="text" name="IBAN" class="stdinput" value=""></td>
							<td>BIC<br><input type="text" name="BIC" class="stdinput" value=""></td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr><td colspan="5">&nbsp;</td></tr>
						<tr>
							<td colspan="5" class="textRight">&#10038; Eingabe - Pflicht</td>
						</tr>

					</table>

				</td>
			</tr>
		</table>

		<input type="hidden" name="callAction" value="adminMandat">
	</form>

</div>

