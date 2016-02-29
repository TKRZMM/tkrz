/**
 * Created by MMelching on 19.02.2016.
 */


var debugSelections = ["debugMessages",
                        "debugCoreGlobal",
                        "debugSESSION",
                        "debugGETPOST"];

var debugSelectionsTabs = ["set_debugMessages",
                            "set_coreGlobal",
                            "set_SESSION",
                            "set_GETPOST"];


// Div - Tag ein/ausblenden
function showOnOff(getElement)
{
    var obj = document.getElementById(getElement);

    if (obj.style.display == 'block')
        obj.style.display = 'none';
    else
        obj.style.display = 'block';
}





// Div - Tags der Debug Selektion ein/ausblenden
function showOnOffDebugSelections(getElement)
{
    var index;
    var obj;
    var tmp;
    var nextTemp;
    var selectedTab;

    for	(index = 0; index < debugSelections.length; index++) {
        tmp = debugSelections[index];
        nextTemp = debugSelectionsTabs[index];

        obj = document.getElementById(tmp);
        selectedTab = document.getElementById(nextTemp);

        if (tmp == getElement) {
            obj.style.display = 'block';
            selectedTab.style.backgroundColor = 'white';
        }
        else {
            obj.style.display = 'none';
            selectedTab.style.backgroundColor = 'rgb(170, 228, 109)';
        }
    }
}



function OLD_hallo()
{
    var obj = document.getElementById('debugMessages');
    var selectedTab = document.getElementById('set_debugMessages');

    obj.style.display = 'block';
    selectedTab.style.backgroundColor = 'white';
}
