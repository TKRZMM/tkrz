/**
 * Created by MMelching on 19.02.2016.
 *
 * Siehe: http://aktuell.de.selfhtml.org/artikel/javascript/draganddrop/
 *
 */

//Das Objekt, das gerade bewegt wird.
var dragobjekt = null;

// Position, an der das Objekt angeklickt wurde.
var dragx = 0;
var dragy = 0;

// Mausposition
var posx = 0;
var posy = 0;



// Initialisierung der Überwachung der Events
function draginit()
{
    document.onmousemove = drag;
    document.onmouseup = dragstop;
}



//Wird aufgerufen, wenn ein Objekt bewegt werden soll.
function dragstart(elementID)
{
    var element;
    element = document.getElementById(elementID);
    dragobjekt = element;
    dragx = posx - dragobjekt.offsetLeft;
    dragy = posy - dragobjekt.offsetTop;
}



// ORIGINAL!!!
// Siehe: http://aktuell.de.selfhtml.org/artikel/javascript/draganddrop/
// Die Initial-Funktion wird dann mit "dragstart(this)" aufgerufen
// Wird aufgerufen, wenn ein Objekt bewegt werden soll.
/*
function dragstart(element)
{
    dragobjekt = element;
    dragx = posx - dragobjekt.offsetLeft;
    dragy = posy - dragobjekt.offsetTop;
}
*/



//Wird aufgerufen, wenn ein Objekt nicht mehr bewegt werden soll.
function dragstop()
{
    dragobjekt=null;
}



//Wird aufgerufen, wenn die Maus bewegt wird und bewegt bei Bedarf das Objekt.
function drag(ereignis)
{
    posx = document.all ? window.event.clientX : ereignis.pageX;
    posy = document.all ? window.event.clientY : ereignis.pageY;
    if(dragobjekt != null) {
        dragobjekt.style.left = (posx - dragx) + "px";
        dragobjekt.style.top = (posy - dragy) + "px";
    }
}


// Verschiebt das Element auf seine Start-Position
// TODO Funktioniert nicht... der reagiert nicht auf unterschiedliche Werte
function sendDebugHome(getElementID)
{
    var obj = document.getElementById(getElementID);
    obj.style.width = 100 + "px";

}