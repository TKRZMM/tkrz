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
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" >
    <link rel="icon" type="image/ico" href="favicon.ico">


    <link rel="stylesheet" type="text/css" href="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/includes/css/defaultCSS.css" />
    <link rel="stylesheet" type="text/css" href="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/includes/css/formCSS.css" />
    <link rel="stylesheet" href="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/includes/css/font-awesome-4.5.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/includes/css/divTagsCSS.css" />
    <link rel="stylesheet" type="text/css" href="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/includes/css/navigationCSS.css" />
    <link rel="stylesheet" type="text/css" href="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/includes/css/tcal.css" />


    <script type="text/javascript" src="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/includes/javascript/defaultJSP.js"></script>
    <script type="text/javascript" src="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/includes/javascript/mvoeBodyFooterDivsJSP.js"></script>
    <script type="text/javascript" src="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/includes/javascript/navigationJSP.js"></script>
    <script type="text/javascript" src="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/includes/javascript/debugOptionsJSP.js"></script>
    <script type="text/javascript" src="<?php print ($_SESSION['Cfg']['Default']['WebsiteSettings']['InternHomeShort']); ?>/includes/javascript/tcal.js"></script>

</head>

<body onload="draginit()">
