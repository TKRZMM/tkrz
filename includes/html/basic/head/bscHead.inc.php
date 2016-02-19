<?php
/**
 * Created by PhpStorm.
 * User: MMelching
 * Date: 19.02.2016
 * Time: 13:37
 */
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="<?php print($_SESSION['Cfg']['Default']['WebsiteSettings']['DefaultEncodeWebFormat']); ?>">
    <title><?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['DefaultWebsiteTitle']); ?></title>


    <link rel="stylesheet" type="text/css" href="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>includes/css/defaultCSS.css" />
    <link rel="stylesheet" type="text/css" href="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>includes/css/divTagsCSS.css" />


    <script type="text/javascript" src="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>includes/javascript/defaultJSP.js"></script>

</head>

<body>
