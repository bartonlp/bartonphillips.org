<?php
// Display logagent table for today

$_site = require_once(getenv("SIMPLE_SITELOADNAME")); $siteload = "vendor simepl-siteload.php";
// Comment out the Normal start and use one of the following for testing.
//$_site = require_once("/var/www/vendor/bartonlp/simple-site-class/includes/simple-autoload.php");
//$siteload = "vendor simple-autoload.php";

//$_site->dbinfo->engine = 'pgsql';
//$_site->dbinfo->engine = 'mysql';
//$_site->dbinfo->engine = 'sqlite';

if(is_null($_site)) {
  echo "\$_site is null<br>";
  error_log("bartonphillips.org/showlogagent.php, SimpleSiteClass with PDO: \$_site is NULL");
  exit();
}

SimpleErrorClass::setDevelopment(true);

$_site->noTrack = true;

// Using SimpleSiteClass which only updates the logagent table.
$S = new SimpleSiteClass($_site);

$S->title = "Display logagent";
$S->banner = "<h1>Display logagent table</h1>";
$phpVersion = PHP_VERSION;
$noTrack =  ($_site->noTrack === true) ? "true" : "false";
$siteclassVersion = "{$S->__toString()} {$S->getVersion()} engine={$S->dbinfo->engine} host={$S->dbinfo->host}<br>noTrack=$noTrack<br>";

$S->msg = "PhpVersion: $phpVersion<br>"; // This is the local phpVersion on the rpi.
$S->msg1 = $siteclassVersion;

$T = new SimpledbTables($S);

// Depending on the engine. Note if engine is invalid we get an error from SimpleSiteClass so we do
// not need a 'default' case.

switch($_site->dbinfo->engine) {
  case 'pgsql':
    $sql = "select * from logagent where lasttime>=now() - interval '10 minute' order by lasttime";
    break;
  case 'mysql':
    $sql = "select * from logagent where lasttime>=now() - interval 10 minute order by lasttime";
    break;
  case 'sqlite':
    $sql = "select * from logagent where lasttime >= datetime('now', '-10 minute') order by lasttime";
    break;
}

$tbl1 = $T->maketable($sql, ['attr'=>['id'=>'logagent', 'border'=>'1']])[0];

// This sql works with all three engines

$sql = "select * from logagent where lasttime>current_date order by lasttime";

$tbl2 = $T->maketable($sql, ['attr'=>['id'=>'logagent', 'border'=>'1']])[0];

[$top, $footer] = $S->getPageTopBottom();

echo <<<EOF
$top
<hr>
<p style="color: red">Using: $S->msg1</p>
<h2>For last 10 minutes</h2>
$tbl1
<h2>For last day</h2>
$tbl2
<hr>
$footer
EOF;

