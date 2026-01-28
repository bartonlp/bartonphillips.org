<?php
$_site = require_once getenv("SIMPLE_SITELOADNAME");
//$_site = require_once "/var/www/simple-site-class/includes/simple-autoload.php";

$S = new SimpleSiteClass($_site);

$what = require SITECLASS_DIR . "/whatisloaded.php";
vardump("what", $what);
