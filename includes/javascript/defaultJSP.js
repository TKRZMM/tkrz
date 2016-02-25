/**
 * Created by MMelching on 19.02.2016.
 */

// Div - Tag ein/ausblenden
function showOnOff(getElement)
{
    var obj = document.getElementById(getElement);

    if (obj.style.display == 'block'){
        obj.style.display = 'none';
    }
    else {
        obj.style.display = 'block';
    }
}