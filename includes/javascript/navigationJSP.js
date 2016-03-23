/**
 * Created by MMelching on 19.02.2016.
 */

function showInformation(mainMenu,subMenu,selection)
{
    var newContent;


    // mainMenu unterscheiden
    if (mainMenu == 'Datei - Upload'){
        newContent = '<i class="fa fa-home fa-lg"></i>&nbsp;&nbsp;HOME&nbsp;&nbsp;<i class="fa fa-angle-double-right fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-upload"></i>&nbsp;&nbsp;';
    }
    else if (mainMenu == 'Datenbank - Import'){
        newContent = '<i class="fa fa-home fa-lg"></i>&nbsp;&nbsp;HOME&nbsp;&nbsp;<i class="fa fa-angle-double-right fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-sign-in fa-lg"></i>&nbsp;&nbsp;';
    }
    else if (mainMenu == 'Datenbank - Export'){
        newContent = '<i class="fa fa-home fa-lg"></i>&nbsp;&nbsp;HOME&nbsp;&nbsp;<i class="fa fa-angle-double-right fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-sign-out fa-lg"></i>&nbsp;&nbsp;';
    }
    if (mainMenu)
        newContent += mainMenu + '&nbsp;&nbsp;&nbsp;&nbsp;';



    // subMenu unterscheiden
    if (subMenu == 'Stammdaten') {
        newContent += '<i class="fa fa-angle-double-right fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-user"></i> &nbsp;';
    }
    else if (subMenu == 'Buchungssatz') {
        newContent += '<i class="fa fa-angle-double-right fa-lg"></i>&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-university"></i>&nbsp;&nbsp;';
    }
    if (subMenu)
        newContent += subMenu + '&nbsp;&nbsp;&nbsp;&nbsp;';



    // selection angegeben?
    if (selection){
        newContent += '<i class="fa fa-angle-double-right fa-lg"></i>&nbsp;&nbsp;&nbsp;';
        newContent += selection + '&nbsp;&nbsp;&nbsp;&nbsp;';
    }


    // infoField
    document.getElementById('infoField').innerHTML = newContent;

}


function clearInformation()
{
    document.getElementById('infoField').innerHTML = '';
}


