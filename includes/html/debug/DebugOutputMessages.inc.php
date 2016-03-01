<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 01.03.2016
 * Time: 08:47
 */
// Gebe die in coreGlobal['messagesSimpleout'] gespeicherten Inhalte formatiert aus
if (isset($hCore->coreGlobal['messagesSimpleout'])){
    print ("<pre><hr>");

    foreach ($hCore->coreGlobal['messagesSimpleout'] as $index=>$value){

        print ("Message: " . $index . " => ");

        if (is_array($value)) {

            foreach ($value as $extraKey=>$curValue){
                print ($extraKey . " => ");
                print_r($curValue);
            }

        }
        else {
            print_r($value);
        }

        print ('<br>');

    }

    print ("</pre><br>");
}
