<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 25.02.2016
 * Time: 12:51
 */
?>
<br>

<div id="debugContentSelections" class="debugContentSelections">
    <table border=0 width="100%" class="standard debugContentSelection">
        <tr>
            <td class="bottomLineGreen" width="200">
                <div id="set_debugMessages" class="debugSelectionTab" onclick="showOnOffDebugSelections('debugMessages')">$hCore->debugMessages</div>
            </td>
            <td class="bottomLineGreen" width="200">
                <div id="set_coreGlobal" class="debugSelectionTab" onclick="showOnOffDebugSelections('debugCoreGlobal')">$hCore->coreGlobal</div>
            </td>
            <td class="bottomLineGreen" width="200">
                <div id="set_SESSION" class="debugSelectionTab" onclick="showOnOffDebugSelections('debugSESSION')">$_SESSION</div>
            </td>
            <td class="bottomLineGreen" width="200">
                <div id="set_GETPOST" class="debugSelectionTab" onclick="showOnOffDebugSelections('debugGETPOST')">$_GET | $_POST</div>
            </td>
            <td class="bottomLineGreen" width="100%"><div id="set_Off" class="debugSelectionTab lastDebugSelection" onclick="showOnOffDebugSelections('debugOff')">X</div></td>
        </tr>
    </table>
</div>





<div id="debugMessages" class="divHiddenOnLoad">
    <table class="standard debugInformation borderGreen">
        <tr>
            <td>
                <?php
                print ('<pre>');
                print ('<span style="text-decoration: underline;">$hCore->coreGlobal[\'debugMessages\']</span><br><br>');
                print_r($hCore->coreGlobal['debugMessages']);
                print ("</pre>");
                ?>
            </td>
        </tr>
    </table>
</div>



<div id="debugCoreGlobal" class="divHiddenOnLoad">
    <table border='1' class="standard debugInformation borderGreen">
        <tr>
            <td>
                <?php
                print ('<pre>');
                print ('<span style="text-decoration: underline;">$hCore->coreGlobal</span><br><br>');
                print_r($hCore->coreGlobal);
                print ("</pre>");
                ?>
            </td>
        </tr>
    </table>
    <br>
</div>



<div id="debugSESSION" class="divHiddenOnLoad">
    <table border='1' class="standard debugInformation borderGreen">
        <tr>
            <td>
                <?php
                print ('<pre>');
                print ('<span style="text-decoration: underline;">$_SESSION</span><br><br>');
                print_r($_SESSION);
                print ("</pre>");
                ?>
            </td>
        </tr>
    </table>
    <br>
</div>



<div id="debugGETPOST" class="divHiddenOnLoad">
    <table border='1' class="standard debugInformation borderGreen">
        <tr>
            <td>
                <?php
                print ('<pre>');
                print ('<span style="text-decoration: underline;">$_GET</span><br><br>');
                print_r($_GET);
                print ("</pre><br><br>");

                print ('<pre>');
                print ('<span style="text-decoration: underline;">$_POST</span><br><br>');
                print_r($_POST);
                print ("</pre><br>");
                ?>
            </td>
        </tr>
    </table>
    <br>
</div>



<?php
if (isset($hCore->debugMessages)) {
    // print ('<script>reSize(\'Footer\');</script>');
    print ('<script>showOnOffDebugSelections(\'debugMessages\');</script>');
}
elseif (isset($hCore->coreGlobal)) {
    print ('<script>showOnOffDebugSelections(\'debugCoreGlobal\');</script>');
}
