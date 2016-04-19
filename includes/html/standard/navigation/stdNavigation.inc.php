<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 11:59
 */


// Navigation einlesen
$boolGotNav = false;    // Flag zur Erkennung gültig geladener Navigations-Punkte

$navArray = array();    // InitialisierungNavigations - Array

// Erzeuge jetzt das Navigations-Objekt
$hNav = new classes\system\SystemLayout();
if ($hNav->createNavMenueFullHandling($navArray))
    $boolGotNav = true; // Setze Flag auf ... habe Daten


// $hNav->addDebugMessage($navArray);



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


    <?php
    if ($boolGotNav){

        foreach($navArray['NavMenue'] as $sourceTypeID => $systemTypeArray) {

            //echo "SourceTypeID : $sourceTypeID<br>";



            foreach($systemTypeArray as $sourceTypeName => $sourceSystemArray) {

                $curIconTag = $navArray['IconSourceType'][$sourceTypeID];
                ?>

                <tr>
                    <td class="submenueB" onmouseover="showInformation('Datei - Upload','<?php print ($sourceTypeName); ?>')" onmouseout="clearInformation()">
                        &nbsp;
                    </td>
                    <td width="20px" align="center" class="bottomLineGreen submenueB" onmouseover="showInformation('Datei - Upload','<?php print ($sourceTypeName); ?>')" onmouseout="clearInformation()">
                        <?php print ($curIconTag); ?>
                    </td>
                    <td colspan="2" class="bottomLineGreen submenueB" onmouseover="showInformation('Datei - Upload','<?php print ($sourceTypeName); ?>')" onmouseout="clearInformation()">
                        <?php print ($sourceTypeName); ?>
                    </td>
                </tr>


                <?php

                foreach($sourceSystemArray as $sourceSystemID => $sourceSystemName) {

                    $curDir = $navArray['NameSourceTypeFileUploadDir'][$sourceTypeID];
                    ?>
                    <tr>
                        <td colspan="2" class="submenueC" onmouseover="showInformation('Datei - Upload','<?php print ($sourceTypeName); ?>','<?php print ($sourceSystemName); ?>')" onmouseout="clearInformation()">&nbsp;</td>
                        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datei - Upload','<?php print ($sourceTypeName); ?>','<?php print ($sourceSystemName); ?>')" onmouseout="clearInformation()">
                            <i class="fa fa-angle-double-right fa-lg"></i>
                        </td>
                        <td class="submenueC subMenueLink" onClick="location.href='<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/fileUpload/<?php print ($curDir); ?>/<?php print ($sourceSystemName); ?>'" onmouseover="showInformation('Datei - Upload','<?php print ($sourceTypeName); ?>','<?php print ($sourceSystemName); ?>')" onmouseout="clearInformation()">
                            <?php print ($sourceSystemName); ?>
                        </td>
                    </tr>
                    <?php

                }



            }


            ?>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <?php

        }


    }   // END if ($boolGotNav){
    ?>

</table>





<br>





<table border="0" width="100%" class="standard">
    <tr>
        <td width="20px" align="center" class="bottomLineGreen submenueA" onmouseover="showInformation('Datenbank - Import')" onmouseout="clearInformation()">
            <i class="fa fa-sign-in fa-lg"></i>
        </td>
        <td colspan="3" class="bottomLineGreen submenueA" onmouseover="showInformation('Datenbank - Import')" onmouseout="clearInformation()">
            Datenbank - Import
        </td>
    </tr>


    <?php
    if ($boolGotNav){

        foreach($navArray['NavMenue'] as $sourceTypeID => $systemTypeArray) {

            //echo "SourceTypeID : $sourceTypeID<br>";



            foreach($systemTypeArray as $sourceTypeName => $sourceSystemArray) {

                $curIconTag = $navArray['IconSourceType'][$sourceTypeID];
                ?>

                <tr>
                    <td class="submenueB" onmouseover="showInformation('Datenbank - Import','<?php print ($sourceTypeName); ?>')" onmouseout="clearInformation()">
                        &nbsp;
                    </td>
                    <td width="20px" align="center" class="bottomLineGreen submenueB" onmouseover="showInformation('Datenbank - Import','<?php print ($sourceTypeName); ?>')" onmouseout="clearInformation()">
                        <?php print ($curIconTag); ?>
                    </td>
                    <td colspan="2" class="bottomLineGreen submenueB" onmouseover="showInformation('Datenbank - Import','<?php print ($sourceTypeName); ?>')" onmouseout="clearInformation()">
                        <?php print ($sourceTypeName); ?>
                    </td>
                </tr>


                <?php

                foreach($sourceSystemArray as $sourceSystemID => $sourceSystemName) {

                    $curDir = $navArray['NameSourceTypeFileUploadDir'][$sourceTypeID];
                    ?>
                    <tr>
                        <td colspan="2" class="submenueC" onmouseover="showInformation('Datenbank - Import','<?php print ($sourceTypeName); ?>','<?php print ($sourceSystemName); ?>')" onmouseout="clearInformation()">&nbsp;</td>
                        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datenbank - Import','<?php print ($sourceTypeName); ?>','<?php print ($sourceSystemName); ?>')" onmouseout="clearInformation()">
                            <i class="fa fa-angle-double-right fa-lg"></i>
                        </td>
                        <td class="submenueC subMenueLink" onClick="location.href='<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/dbImport/<?php print ($curDir); ?>/<?php print ($sourceSystemName); ?>'" onmouseover="showInformation('Datenbank - Import','<?php print ($sourceTypeName); ?>','<?php print ($sourceSystemName); ?>')" onmouseout="clearInformation()">
                            <?php print ($sourceSystemName); ?>
                        </td>
                    </tr>
                    <?php

                }



            }


            ?>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <?php

        }


    }   // END if ($boolGotNav){
    ?>

</table>





<br>





<table border="0" width="100%" class="standard">
    <tr>
        <td width="20px" align="center" class="bottomLineGreen submenueA" onmouseover="showInformation('Datenbank - Export')" onmouseout="clearInformation()">
            <i class="fa fa-sign-out fa-lg"></i>
        </td>
        <td colspan="3" class="bottomLineGreen submenueA" onmouseover="showInformation('Datenbank - Export')" onmouseout="clearInformation()">
            Datenbank - Export
        </td>
    </tr>


    <?php
    if ($boolGotNav){

        foreach($navArray['NavMenue'] as $sourceTypeID => $systemTypeArray) {

            //echo "SourceTypeID : $sourceTypeID<br>";



            foreach($systemTypeArray as $sourceTypeName => $sourceSystemArray) {

                $curIconTag = $navArray['IconSourceType'][$sourceTypeID];
                ?>

                <tr>
                    <td class="submenueB" onmouseover="showInformation('Datenbank - Export','<?php print ($sourceTypeName); ?>')" onmouseout="clearInformation()">
                        &nbsp;
                    </td>
                    <td width="20px" align="center" class="bottomLineGreen submenueB" onmouseover="showInformation('Datenbank - Export','<?php print ($sourceTypeName); ?>')" onmouseout="clearInformation()">
                        <?php print ($curIconTag); ?>
                    </td>
                    <td colspan="2" class="bottomLineGreen submenueB" onmouseover="showInformation('Datenbank - Export','<?php print ($sourceTypeName); ?>')" onmouseout="clearInformation()">
                        <?php print ($sourceTypeName); ?>
                    </td>
                </tr>


                <?php

                foreach($sourceSystemArray as $sourceSystemID => $sourceSystemName) {

                    $curDir = $navArray['NameSourceTypeFileUploadDir'][$sourceTypeID];
                    ?>
                    <tr>
                        <td colspan="2" class="submenueC" onmouseover="showInformation('Datenbank - Export','<?php print ($sourceTypeName); ?>','<?php print ($sourceSystemName); ?>')" onmouseout="clearInformation()">&nbsp;</td>
                        <td width="20px" align="center" class="submenueC" onmouseover="showInformation('Datenbank - Export','<?php print ($sourceTypeName); ?>','<?php print ($sourceSystemName); ?>')" onmouseout="clearInformation()">
                            <i class="fa fa-angle-double-right fa-lg"></i>
                        </td>
                        <td class="submenueC subMenueLink" onClick="location.href='<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/dbExport/<?php print ($curDir); ?>/<?php print ($sourceSystemName); ?>'" onmouseover="showInformation('Datenbank - Export','<?php print ($sourceTypeName); ?>','<?php print ($sourceSystemName); ?>')" onmouseout="clearInformation()">
                            <?php print ($sourceSystemName); ?>
                        </td>
                    </tr>
                    <?php

                }



            }


            ?>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <?php

        }


    }   // END if ($boolGotNav){
    ?>

</table>



