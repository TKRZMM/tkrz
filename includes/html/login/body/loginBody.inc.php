<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */
?>


<div class="center">
    <main role="main">
        <h2>Login Formular</h2>

        <form class="loginForm" action="" method="post">

            <input id="userName" name="userName" class="login" autofocus pattern=".{<?php print ($_SESSION['Cfg']['System']['RequirementSettings']['requireMinLenUserLogin']); ?>,<?php print ($_SESSION['Cfg']['System']['RequirementSettings']['requireMaxLenUserLogin']); ?>}" required title="Benötigt zwischen <?php print ($_SESSION['Cfg']['System']['RequirementSettings']['requireMinLenUserLogin']); ?> und <?php print ($_SESSION['Cfg']['System']['RequirementSettings']['requireMaxLenUserLogin']); ?> Zeichen!" maxlength="<?php print ($_SESSION['Cfg']['System']['RequirementSettings']['requireMaxLenUserLogin']); ?>">
            <label for="userName">Benutzername</label>

            <input id="userPassword"name="userPassword" class="login" type="password" pattern=".{<?php print ($_SESSION['Cfg']['System']['RequirementSettings']['requireMinLenUserPassword']); ?>,<?php print ($_SESSION['Cfg']['System']['RequirementSettings']['requireMaxLenUserPassword']); ?>}" required title="Benötigt zwischen <?php print ($_SESSION['Cfg']['System']['RequirementSettings']['requireMinLenUserPassword']); ?> und <?php print ($_SESSION['Cfg']['System']['RequirementSettings']['requireMaxLenUserPassword']); ?> Zeichen!" maxlength="<?php print ($_SESSION['Cfg']['System']['RequirementSettings']['requireMaxLenUserPassword']); ?>">
            <label for="userPassword">Passwort</label>


            <button type="submit" class="loginButton">Anmelden</button>

            <input type="hidden" name="callAction" value="callLogin">
        </form>

    </main>
</div>
