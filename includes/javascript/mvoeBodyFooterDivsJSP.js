/**
 * Created by MMelching on 19.02.2016.
 */

// Maximale Höhe des Footer - Div - Fenster
var maxHighFooter = '600';

// Minimale Höhe des Footer - Div - Fenster
var minHighFooter = '15';

// Set Variable
var setStepsFooter = 10;     // Schritte in px die das Div-Fenster größer/kleiner werden soll
var setTimeoutFooter = 5;    // Timeout - Wert für das Footer-Div-Fenster bei der Größenänderung





// Initial Funktion zum "Resizen" der Footer - Leiste
function reSize(getMyElement)
{
    // Aktulle Höhe des Elementes ermitteln
    var obj = document.getElementById(getMyElement);
    var tmpHigh = obj.style.height;
    var curHigh = parseInt(tmpHigh);

    var inRange = maxHighFooter - setStepsFooter;

    // Soll "hoch" oder "runter" gefahren werden?
    if ( (curHigh >= maxHighFooter) || (curHigh >= inRange) ) {
        doReSize(obj, curHigh, 'down');
    }
    else {
        doReSize(obj, curHigh, 'up');
    }

}





// Sub Funktion zum "Resizen" der Footer - Leiste
function doReSize (obj, curHigh, dir)
{
    var nextHigh;

    if (dir =='up'){
        nextHigh = curHigh + setStepsFooter;

        if (nextHigh <= maxHighFooter){

            MoveBoddy('up');
            setHigh(obj, nextHigh);

            setTimeout(function(){ doReSize(obj, nextHigh, dir); }, setTimeoutFooter);
        }
    }
    else {
        nextHigh = curHigh - setStepsFooter;

        if (nextHigh >= minHighFooter){
            MoveBoddy('down');
            setHigh(obj, nextHigh);

            setTimeout(function(){ doReSize(obj, nextHigh, dir); }, setTimeoutFooter);
        }
    }
}





// Sub Funktion setzt die Größe des übergebenen Objekt auf die übergeben Größe
function setHigh(obj, newHigh)
{
    obj.style.height = newHigh + "px";
}





// Sub Funktion setzt den Abstand zum Bottom
function setBottom(obj, newBottom)
{
    obj.style.bottom = newBottom + "px";
}





// Sub Funktion ändert den Abstand des Body - Div zum Fensterboden
function MoveBoddy(dir)
{
    var curElement = 'containerBody';

    var obj = document.getElementById(curElement);
    var tmpBottom = obj.style.bottom;
    var curBottom = parseInt(tmpBottom);

    var newButtom;
    if (dir == 'up'){
        newButtom = curBottom + setStepsFooter;
    }
    else {
        newButtom = curBottom - setStepsFooter;
    }
    setBottom(obj, newButtom);
}
