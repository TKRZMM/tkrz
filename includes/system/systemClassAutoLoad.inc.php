<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 18.02.2016
 * Time: 12:25
 *
 * @param $getContent
 *
 * @return mixed
 */



// Fehlermeldungen die nicht über die Message - Klasse abgefangen werden können
/**
 * @param $getCaseNum
 * @param string $addArg
 */
function mySimpleout($getCaseNum, $addArg = '')
{

	header('Content-Type: text/html; charset=Utf-8');
	print ("<pre>");

	$message = "FEHLER -KRITISCH FÜHRT ZU EXIT-<br>Versuch Klassen-Datei einzulesen fehlgeschlagen!<br>Fehlermeldung: <br>";

	switch ($getCaseNum) {
		case 1:
			$message .= "Datei für die angeforderte Klasse '" . $addArg . "' existiert nicht oder kann nicht gelesen werden!";

			break;


		default:
			$message .= "Unbekannter Fehler bei Klasse / Klassen-Datei: " . $addArg;
	}

	print($message);
	print ("</pre>");
	exit;

}   // END function mySimpleout(...)








// Ersetze Slashe vor-zurück
function revertSlashes($getContent)
{

	// Erstetze / durch \
	$returnValue = str_replace("\\", '/', $getContent);

	return $returnValue;

}   // END function revertSlashes($getContent)








// Prüft ob "classes" mit im Pfad ist und gibt true oder false zurück
function checkForClassesDir($class)
{
	if (!preg_match('/classes\/(.+)/',$class, $mathes))
		return false;

	return true;

}	// END function checkForClassesDir(...)















// PHP Klassen Auto-Loader (REQUIRE PHP Version >= 5.3.0)
spl_autoload_register(

	function ($class) {

		$class = revertSlashes($class);

		$class .= '.class.php';

		if (!checkForClassesDir($class))
			$fullPath = 'includes/classes/' . $class;
		else
			$fullPath = 'includes/' . $class;


		if (file_exists($fullPath)){
			include $fullPath;
		}
		else {
			// Wenn wir bis hier kommen, gibt es die Klasse / Klassen-Datei nicht!
			mySimpleout(1, $fullPath);
			exit;
		}

	}

);



// Initialisiere Base->Core - Klassen - Objekt
//$hCore = new CoreExtends();
$hCore = new classes\core\CoreExtends();

