<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */


// Art einlesen... Stammdaten / Buchungssatz
$navArray = array();
$hNav = new classes\system\SystemLayout();
if ($hNav->createNavMenueFullHandling($navArray)){

    $hNav->addDebugMessage($navArray);

    

}



?>
<i class="fa fa-list fa-lg"></i>&nbsp;&nbsp; Navigation
<br><br>


<table border="0" width="100%" class="standard">
    <tr>
        <td width="20px" align="center" class="bottomLineGreen submenueA" onmouseover="showInformation('Datei - Upload')" onmouseout="clearInformation()">
            <i class="fa fa-upload"></i>
        </td>
        <td colspan="3" class="bottomLineGreen submenueA" onmouseover="showInformation('Datei - Upload')" onmouseout="clearInformation()">
            Datei - Upload
        </td>
    </tr>




    <tr>
        <td class="submenueB" onmouseover="showInformation('Datei - Upload','Stammdaten')" onmouseout="clearInformation()">
            &nbsp;
        </td>
        <td width="20px" align="center" class="bottomLineGreen submenueB" onmouseover="showInformation('Datei - Upload','Stammdaten')" onmouseout="clearInformation()">
            <i class="fa fa-user"></i>
        </td>
        <td colspan="2" class="bottomLineGreen submenueB" onmouseover="showInformation('Datei - Upload','Stammdaten')" onmouseout="clearInformation()">
            Stammdaten
        </td>
    </tr>
    <tr>
        <td colspan="2" class="submenueC" onmouseover="showInformation('Datei - Upload','Stammdaten','Centron')" onmouseout="clearInformation()">&nbsp;</td>
        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datei - Upload','Stammdaten','Centron')" onmouseout="clearInformation()">
            <i class="fa fa-angle-double-right fa-lg"></i>
        </td>
        <td class="submenueC subMenueLink" onClick="location.href='<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/fileUpload/baseData/Centron'" onmouseover="showInformation('Datei - Upload','Stammdaten','Centron')" onmouseout="clearInformation()">
            Centron
        </td>
    </tr>
    <tr>
        <td colspan="2" class="submenueC" onmouseover="showInformation('Datei - Upload','Stammdaten','Dirmari')" onmouseout="clearInformation()">&nbsp;</td>
        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datei - Upload','Stammdaten','Dirmari')" onmouseout="clearInformation()">
            <i class="fa fa-angle-double-right fa-lg"></i>
        </td>
        <td class="submenueC subMenueLink" onClick="location.href='http://www.heise.de'" onmouseover="showInformation('Datei - Upload','Stammdaten','Dirmari')" onmouseout="clearInformation()">
            Dimari
        </td>
    </tr>





    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>

    <tr>
        <td class="submenueB" onmouseover="showInformation('Datei - Upload','Buchungssatz')" onmouseout="clearInformation()">
            &nbsp;
        </td>
        <td width="20px" align="center" class="bottomLineGreen submenueB" onmouseover="showInformation('Datei - Upload','Buchungssatz')" onmouseout="clearInformation()">
            <i class="fa fa-university"></i>
        </td>
        <td colspan="2" class="bottomLineGreen submenueB" onmouseover="showInformation('Datei - Upload','Buchungssatz')" onmouseout="clearInformation()">
            Buchungssatz
        </td>
    </tr>
    <tr>
        <td colspan="2" class="submenueC" onmouseover="showInformation('Datei - Upload','Buchungssatz','Centron')" onmouseout="clearInformation()">&nbsp;</td>
        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datei - Upload','Buchungssatz','Centron')" onmouseout="clearInformation()">
            <i class="fa fa-angle-double-right fa-lg"></i>
        </td>
        <td class="submenueC subMenueLink" onClick="location.href='<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/fileUpload/bookingData/Centron'" onmouseover="showInformation('Datei - Upload','Buchungssatz','Centron')" onmouseout="clearInformation()">
            Centron
        </td>
    </tr>
    <tr>
        <td colspan="2" class="submenueC" onmouseover="showInformation('Datei - Upload','Buchungssatz','Dimari')" onmouseout="clearInformation()">&nbsp;</td>
        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datei - Upload','Buchungssatz','Dimari')" onmouseout="clearInformation()">
            <i class="fa fa-angle-double-right fa-lg"></i>
        </td>
        <td class="submenueC subMenueLink" onClick="location.href='http://www.heise.de'" onmouseover="showInformation('Datei - Upload','Buchungssatz','Dimari')" onmouseout="clearInformation()">
            Dimari
        </td>
    </tr>
</table>





<br><br>





<table border="0" width="100%" class="standard">
    <tr>
        <td width="20px" align="center" class="bottomLineGreen submenueA" onmouseover="showInformation('Datenbank - Import')" onmouseout="clearInformation()">
            <i class="fa fa-sign-in fa-lg"></i>
        </td>
        <td colspan="3" class="bottomLineGreen submenueA" onmouseover="showInformation('Datenbank - Import')" onmouseout="clearInformation()">
            Datenbank - Import
        </td>
    </tr>

    <tr>
        <td class="submenueB" onmouseover="showInformation('Datenbank - Import','Stammdaten')" onmouseout="clearInformation()">
            &nbsp;
        </td>
        <td width="20px" align="center" class="bottomLineGreen submenueB" onmouseover="showInformation('Datenbank - Import','Stammdaten')" onmouseout="clearInformation()">
            <i class="fa fa-user"></i>
        </td>
        <td colspan="2" class="bottomLineGreen submenueB" onmouseover="showInformation('Datenbank - Import','Stammdaten')" onmouseout="clearInformation()">
            Stammdaten
        </td>
    </tr>
    <tr>
        <td colspan="2" class="submenueC" onmouseover="showInformation('Datenbank - Import','Stammdaten','Centron')" onmouseout="clearInformation()">&nbsp;</td>
        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datenbank - Import','Stammdaten','Centron')" onmouseout="clearInformation()">
            <i class="fa fa-angle-double-right fa-lg"></i>
        </td>
        <td class="submenueC subMenueLink" onClick="location.href='http://www.heise.de'" onmouseover="showInformation('Datenbank - Import','Stammdaten','Centron')" onmouseout="clearInformation()">
            Centron
        </td>
    </tr>
    <tr>
        <td colspan="2" class="submenueC" onmouseover="showInformation('Datenbank - Import','Stammdaten','Dimari')" onmouseout="clearInformation()">&nbsp;</td>
        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datenbank - Import','Stammdaten','Dimari')" onmouseout="clearInformation()">
            <i class="fa fa-angle-double-right fa-lg"></i>
        </td>
        <td class="submenueC subMenueLink" onClick="location.href='http://www.heise.de'" onmouseover="showInformation('Datenbank - Import','Stammdaten','Dimari')" onmouseout="clearInformation()">
            Dimari
        </td>
    </tr>

    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>

    <tr>
        <td class="submenueB" onmouseover="showInformation('Datenbank - Import','Buchungssatz')" onmouseout="clearInformation()">
            &nbsp;
        </td>
        <td width="20px" align="center" class="bottomLineGreen submenueB" onmouseover="showInformation('Datenbank - Import','Buchungssatz')" onmouseout="clearInformation()">
            <i class="fa fa-university"></i>
        </td>
        <td colspan="2" class="bottomLineGreen submenueB" onmouseover="showInformation('Datenbank - Import','Buchungssatz')" onmouseout="clearInformation()">
            Buchungssatz
        </td>
    </tr>
    <tr>
        <td colspan="2" class="submenueC" onmouseover="showInformation('Datenbank - Import','Buchungssatz','Centron')" onmouseout="clearInformation()">&nbsp;</td>
        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datenbank - Import','Buchungssatz','Centron')" onmouseout="clearInformation()">
            <i class="fa fa-angle-double-right fa-lg"></i>
        </td>
        <td class="submenueC subMenueLink" onClick="location.href='http://www.heise.de'" onmouseover="showInformation('Datenbank - Import','Buchungssatz','Centron')" onmouseout="clearInformation()">
            Centron
        </td>
    </tr>
    <tr>
        <td colspan="2" class="submenueC" onmouseover="showInformation('Datenbank - Import','Buchungssatz','Dimari')" onmouseout="clearInformation()">&nbsp;</td>
        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datenbank - Import','Buchungssatz','Dimari')" onmouseout="clearInformation()">
            <i class="fa fa-angle-double-right fa-lg"></i>
        </td>
        <td class="submenueC subMenueLink" onClick="location.href='http://www.heise.de'" onmouseover="showInformation('Datenbank - Import','Buchungssatz','Dimari')" onmouseout="clearInformation()">
            Dimari
        </td>
    </tr>
</table>





<br><br>





<table border="0" width="100%" class="standard">
    <tr>
        <td width="20px" align="center" class="bottomLineGreen submenueA" onmouseover="showInformation('Datenbank - Export')" onmouseout="clearInformation()">
            <i class="fa fa-sign-out fa-lg"></i>
        </td>
        <td colspan="3" class="bottomLineGreen submenueA" onmouseover="showInformation('Datenbank - Export')" onmouseout="clearInformation()">
            Datenbank - Export
        </td>
    </tr>

    <tr>
        <td class="submenueB" onmouseover="showInformation('Datenbank - Export','Stammdaten')" onmouseout="clearInformation()">
            &nbsp;
        </td>
        <td width="20px" align="center" class="bottomLineGreen submenueB" onmouseover="showInformation('Datenbank - Export','Stammdaten')" onmouseout="clearInformation()">
            <i class="fa fa-user"></i>
        </td>
        <td colspan="2" class="bottomLineGreen submenueB" onmouseover="showInformation('Datenbank - Export','Stammdaten')" onmouseout="clearInformation()">
            Stammdaten
        </td>
    </tr>
    <tr>
        <td colspan="2" class="submenueC" onmouseover="showInformation('Datenbank - Export','Stammdaten','Centron')" onmouseout="clearInformation()">&nbsp;</td>
        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datenbank - Export','Stammdaten','Centron')" onmouseout="clearInformation()">
            <i class="fa fa-angle-double-right fa-lg"></i>
        </td>
        <td class="submenueC subMenueLink" onClick="location.href='http://www.heise.de'" onmouseover="showInformation('Datenbank - Export','Stammdaten','Centron')" onmouseout="clearInformation()">
            Centron
        </td>
    </tr>
    <tr>
        <td colspan="2" class="submenueC" onmouseover="showInformation('Datenbank - Export','Stammdaten','Dimari')" onmouseout="clearInformation()">&nbsp;</td>
        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datenbank - Export','Stammdaten','Dimari')" onmouseout="clearInformation()">
            <i class="fa fa-angle-double-right fa-lg"></i>
        </td>
        <td class="submenueC subMenueLink" onClick="location.href='http://www.heise.de'" onmouseover="showInformation('Datenbank - Export','Stammdaten','Dimari')" onmouseout="clearInformation()">
            Dimari
        </td>
    </tr>

    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>

    <tr>
        <td class="submenueB" onmouseover="showInformation('Datenbank - Export','Buchungssatz')" onmouseout="clearInformation()">
            &nbsp;
        </td>
        <td width="20px" align="center" class="bottomLineGreen submenueB" onmouseover="showInformation('Datenbank - Export','Buchungssatz')" onmouseout="clearInformation()">
            <i class="fa fa-university"></i>
        </td>
        <td colspan="2" class="bottomLineGreen submenueB" onmouseover="showInformation('Datenbank - Export','Buchungssatz')" onmouseout="clearInformation()">
            Buchungssatz
        </td>
    </tr>
    <tr>
        <td colspan="2" class="submenueC" onmouseover="showInformation('Datenbank - Export','Buchungssatz','Centron')" onmouseout="clearInformation()">&nbsp;</td>
        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datenbank - Export','Buchungssatz','Centron')" onmouseout="clearInformation()">
            <i class="fa fa-angle-double-right fa-lg"></i>
        </td>
        <td class="submenueC subMenueLink" onClick="location.href='http://www.heise.de'" onmouseover="showInformation('Datenbank - Export','Buchungssatz','Centron')" onmouseout="clearInformation()">
            Centron
        </td>
    </tr>
    <tr>
        <td colspan="2" class="submenueC" onmouseover="showInformation('Datenbank - Export','Buchungssatz','Dimari')" onmouseout="clearInformation()">&nbsp;</td>
        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datenbank - Export','Buchungssatz','Dimari')" onmouseout="clearInformation()">
            <i class="fa fa-angle-double-right fa-lg"></i>
        </td>
        <td class="submenueC subMenueLink" onClick="location.href='http://www.heise.de'" onmouseover="showInformation('Datenbank - Export','Buchungssatz','Dimari')" onmouseout="clearInformation()">
            Dimari
        </td>
    </tr>
</table>

