<?php
$_site = require_once(getenv("SIMPLE_SITELOADNAME"));
//$_site = require_once("/var/www/bartonphillips.org/simple-site-class/includes/autoload.php");

$tbl = (require(SITECLASS_DIR . "/whatisloaded.php"))[0];

$S = new SimpleSiteClass($_site);
$S->title = "Get Versions";
$S->banner = "<h1>Get Versions</h1>";
$S->css = "td { padding: 0 10px; }";

$class = $S->__toString();

[$top, $footer] = $S->getPageTopBottom();

echo <<<EOF
$top
<hr>
$class
$tbl
<hr>
$footer
EOF;
