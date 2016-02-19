<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 12:25
 */


// Fehlermeldungen die nicht über die Message - Klasse abgefangen werden können
/**
 * @param $getCaseNum
 * @param string $addArg
 */
function mySimpleout($getCaseNum, $addArg='')
{

    header('Content-Type: text/html; charset=Utf-8');
    print ("<pre>");

    switch ($getCaseNum) {
        case 1:
            $message = "FEHLER -KRITISCH FÜHRT ZU EXIT-<br>";
            $message .= "Versuch Klassen-Datei einzulesen fehlgeschlagen!<br>";
            $message .= "Fehlermeldung: <br>";
            $message .= "Datei für die angeforderte Klasse '".$addArg."' existiert nicht oder kann nicht gelesen werden!";

            break;


        default:
            $message = "FEHLER -KRITISCH FÜHRT ZU EXIT-<br>";
            $message .= "Versuch Klassen-Datei einzulesen fehlgeschlagen!<br>";
            $message .= "Fehlermeldung: <br>";
            $message .= "Unbekannter Fehler bei Klasse / Klassen-Datei: " . $addArg;
    }

    print($message);
    print ("</pre>");
    exit;

}   // END function mySimpleout(...)





// Liefert den Pfad zu einer Klasse via preg_match
function getClassDir ($searchForClass, $matchPath)
{
    $search = '/(.*)('.$searchForClass.')$/';
    $pattern = $matchPath;
    preg_match($search, $pattern, $matches);

    return $matches[1];
}   // END function getClassDir (...)





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

    // Wenn wir bis hier kommen, gibt es die Klasse / Klassen-Datei nicht!
    mySimpleout(1,$searchForClass);
    exit;
}   // END function findClassDir (...)







// PHP Klassen Auto-Loader (REQUIRE PHP Version >= 5.3.0)
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
$hCore = new CoreExtends();

