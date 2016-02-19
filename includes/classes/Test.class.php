<?php

/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 12:26
 */
class Test extends CoreExtends
{

    function helloWorld()
    {
        echo "Hello World<br>";
        echo "<pre>";
        print_r($this->coreGlobal);
        echo "</pre><br>";
    }
}