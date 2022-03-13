<?php
$_site = require_once(getenv("SITELOADNAME"));
$S = new SiteClass($_site);
$h->banner = "<h1>Test2 Banner</h1>";
[$top, $footer] = $S->getPageTopBottom($h);

echo <<<EOF
$top
<h2>Test two stuff</h2>
$footer
EOF;

