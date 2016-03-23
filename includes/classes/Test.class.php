<?php

/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 12:26
 */

namespace classes;

use classes\core\CoreExtends;

class test extends CoreExtends
{

    function helloWorld()
    {
        echo "Hello World<br>";
        echo "<pre>";
        print_r($this->coreGlobal);
        echo "</pre><br>";
    }


    function test()
    {

        $str1 = 'Hello Markus';
        $str = '' . $str1 . '';

        print ('' . $str . '');





    }
}

