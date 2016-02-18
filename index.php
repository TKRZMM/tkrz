<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 11:35
 */
session_start();

require_once 'includes/system/systemClassAutoLoad.inc.php';

echo "<hr>";
echo "<pre>";
print_r($_SESSION);
echo "</pre><br>";
