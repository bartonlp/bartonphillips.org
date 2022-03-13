<?php
// BLP 2021-10-03 -- Get information from ifconfig.co

$_site = require_once(getenv("SITELOADNAME"));
ErrorClass::setNoEmailErrs(true);
//vardump("site", $_site);
$S = new $_site->className($_site);

use PHPHtmlParser\Dom;

$h->banner = "<h1>Bartonphillips.org on HP envy</h1>";

list($top, $footer) = $S->getPageTopBottom($h);

//$site = file_get_contents("https://ifconfig.co");

$dom = new Dom;

//$x = $dom->loadStr($site);
$x = $dom->loadFromUrl("https://ifconfig.co");

$tbl = $x->find(".info-table");

// BLP 2021-10-03 -- or we can do it via curl and get a json record
$ch = curl_init("ifconfig.io/all.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl = curl_exec($ch);

echo <<<EOF
$top
$tbl
<h2>Via Curl as a json set</h2>
<p>
$curl
</p>
$footer
EOF;
