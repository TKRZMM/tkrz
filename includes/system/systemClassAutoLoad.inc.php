<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 12:25
 */

// Liefert den Pfad zu einer Klasse via preg_match
function getClassDir ($searchForClass, $matchPath)
{
    $search = '/(.*)('.$searchForClass.')$/';
    $pattern = $matchPath;
    preg_match($search, $pattern, $matches);

    return $matches[1];
}





// INITIAL Liefert den Pfad zu einer Klasse
function findClassDir ($searchForClass)
{
    $path[] = 'includes/classes/*';

    while(count($path) != 0)
    {
        $shiftedPath = array_shift($path);

        foreach(glob($shiftedPath) as $item)
        {
            if (is_dir($item))
                $path[] = $item . '/*';

            elseif (is_file($item))
            {
                // Datei entspricht der gesuchten Klasse?
                if (basename($item) == $searchForClass)
                {
                    return getClassDir($searchForClass, $item);
                }
            }
        }
    }
}







// PHP Klassen Auto-Loader (REQUIRE PHP 5.3.0)
spl_autoload_register(

    function ($class)
    {
        // Pfad zur gewünschten Klasse finden
        $classIncludePath = findClassDir($class . '.class.php');

        // Gewünschte Klasse laden
        include $classIncludePath . $class . '.class.php';
    }

);





// Initialisiere Base->Core - Klassen - Objekt
$hCoreA = new CoreExtends();
$hCoreA->coreGlobal['Vorname'] = 'Markus';


$hCoreB = new CoreExtends();

$hSub = new Test();

var_dump($hCoreA); echo "<br>";
var_dump($hCoreB); echo "<br>";
var_dump($hSub); echo "<br>";
echo "<hr>";

echo "<pre>";
print_r($hCoreA->coreGlobal);
echo "</pre><br>";

echo "<pre>";
print_r($hCoreB->coreGlobal);
echo "</pre><br>";

echo "<pre>";
print_r($hSub->coreGlobal);
echo "</pre><br>";




