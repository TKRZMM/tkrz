/**
 * Created by MMelching on 19.02.2016.
 */


var debugSelections = ["messagesDebug",
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



// Div - Tag einblenden
function showOn(getElement)
{
	var obj = document.getElementById(getElement);

	obj.style.display = 'block';
}



// Div - Tag ausblenden
function showOff(getElement)
{
	var obj = document.getElementById(getElement);

	obj.style.display = 'none';
}



// InnerHtml Inhalt ändern
function showInnerHtml(get0, get1, get2, get3, get4, get5, get6, get7)
{
	showOn('AdditionalInfo');
	document.getElementById('centronNumber').innerHTML = get0;
	document.getElementById('dateInfo_1').innerHTML = get1;
	document.getElementById('dateInfo_2').innerHTML = get2;
	document.getElementById('dateInfo_3').innerHTML = get3;
	document.getElementById('dateInfo_4').innerHTML = get4;
	document.getElementById('dateInfo_5').innerHTML = get5;
	document.getElementById('dateInfo_6').innerHTML = get6;
	document.getElementById('dateInfo_7').innerHTML = get7;
}



// Div - Tags der Deb-Selektion ein/ausblenden
function showOnOffDebugSelections(getElement)
{
	var index;
	var obj;
	var tmp;
	var nextTemp;
	var selectedTab;


	for(index = 0; index < debugSelections.length; index++) {
		tmp = debugSelections[index];
		nextTemp = debugSelectionsTabs[index];

		obj = document.getElementById(tmp);
		selectedTab = document.getElementById(nextTemp);

		//alert("getElement: " + getElement + " -> tmp: " + tmp + " -> index: " + index + " -> obj: " + obj);

		if (obj) {
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
}


function selUserSelectionByID(getHtmlID, getID)
{
	var element = getHtmlID + getID;
	document.getElementById(element).checked = true;
}


// Datum - Eingabe leeren
function delDateFieldByID(getID)
{
	document.getElementById(getID).value = "";
}