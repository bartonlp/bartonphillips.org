<?php
$_site = require_once(getenv("SITELOADNAME"));
ErrorClass::setDevelopment(true);
$S = new $_site->className($_site);

[$top, $footer] = $S->getPageTopBottom();

echo $top;

$finger = require("https://bartonphillips.net/test.php");
if($finger == 1) {
  vardump("test", $fingers);
} else {
  vardump("finger", $finger);
}

