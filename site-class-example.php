<?php

$_site = require_once "/var/www/vendor/bartonlp/simple-site-class/includes/simple-siteload.php";

//$_site = require_once "/var/www/vendor/bartonlp/simple-site-class/includes/simple-siteload.php";
//vardump("site", $_site);
$S = new SimpleSiteClass($_site);

$S->banner = "<h1>Test of SiteClass</h1>";

[$top, $footer] = $S->getPageTopBottom();


echo <<<EOF
$top
<h1>
<p>Test of this</p>
<h1>
$footer
EOF;
