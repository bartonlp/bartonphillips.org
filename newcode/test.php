<?php
$_site = require_once "/var/www/vendor/bartonlp/site-class/includes/siteload.php";
ErrorClass::setDevelopment(true);
//vardump("site", $_site);

$S = new SiteClass($_site);
//vardump("S", $S);

$S->b_inlineScript =<<<EOF
  console.log("This is start");
  document.querySelector(".here").addEventListener("click", e => {
    console.log("This is here");
    consl getit = e.target.value();
    getit = getit.toUpperCase();
    e.target.value(getit);
  });
EOF;

$S->css =<<<EOF
.here { color: red; }
EOF;

[$top, $bottom] = $S->getPageTopBottom();

echo <<<EOF
$top
<button class="here">hello</button>
$bottom
EOF;
