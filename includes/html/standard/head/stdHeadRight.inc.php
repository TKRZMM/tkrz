<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 09:14
 */
?>
<table border=0 class="standard logedinUser" align="right" style="width:260px">
    <tr>
        <td align="right">

            <table border=0 class="textRight logedinUser">
                <tr>
                    <td class="bottomLine rPaddingSix">Benutzer:</td><td class="bottomLine"><?php print ($_SESSION['Login']['userName']); ?></td>
                </tr>

                <tr>
                    <td class="rPaddingSix">Status:</td><td><?php print ($_SESSION['Login']['userRoleName']); ?></td>
                </tr>

                <tr>
                    <td class="rPaddingSix">Login:</td><td><?php print ($_SESSION['Login']['dateCurLogin']); ?></td>
                </tr>

                <tr>
                    <td class="bottomLine rPaddingSix">Letzter Login:</td><td class="bottomLine"><?php print ($_SESSION['Login']['dateLastLogin']); ?></td>
                </tr>

                <?php

                print ('<tr><td colspan="2"><a href="'.$_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort'].'/callLogout"><i class="fa fa-power-off"></i>&nbsp;Logout&nbsp;</a></td></tr>');

                ?>
            </table>

        </td>
    </tr>
</table>
