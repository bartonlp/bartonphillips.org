<?php
// BLP 2024-04-11 - Reworked for RPI. The programs are all in the bartonphillips.com:8000 directory
// and not at  https://bartonlp.com/otherpages/webstats.php. The rest of 2022-05-01 is still valie.
//
// BLP 2022-05-01 - Major rework. This now is in https://bartonlp.com/otherpages/webstats.php. I no
// longer use symlinks and the cumbersom rerouting logic is gone. Now webstats.php is called with
// ?blp=8653&site={sitename}. The GET grabs the site and puts it into $site. The post is called via
// the <select> and grabs the site a location header call which in turn does a new GET.
// Once the site is setup by the GET we get $_site and set $_site->siteName to $site.
// This file still uses webstats.js and webstats-ajax.php.

// IMPORTANT: mysitemap.json sets 'noGeo' true so we do not load it in SiteClass::getPageHead()
// We use map.js instead of geo.js

//$DEBUG = true;

$_site = require_once getenv("SIMPLE_SITELOADNAME");
SimpleErrorClass::setDevelopment(true);
// Wrap this in a try to see if the constructor fails

$_site->noTrack = true; // BLP 2024-11-08 - Don't track. Note in SimpleSiteClass there is no GEO

try {
  $S = new SimpleSiteClass($_site);
} catch(Exception $e) {
  $errno = $e->getCode();
  $errmsg = $e->getMessage();
  $sql = dbMySqli::$lastQuery;
  error_log("webstat.php constructor FAILED: $xip, $xsite, site=$site, sql=$sql, ref=$xref, errno=$errno, errmsg=$errmsg, agent=$xagent");

  // We do not have $S so we can't add this to the badplayer table.

  $sql = substr($sql, 0, 254); // Truncate just in case.

  // We do not have a $S so use the database name here and the x* items.
  
  insertMysqli("insert into barton.badplayer (ip, site, page, botAs, count, type, errno, errmsg, agent, created, lasttime) ".
               "values('$xip', '$xsite', 'webstats', 'counted', 1, 'CONSTRUCTOR_ERROR', -200, 'sql=$sql', '$xagent', now(), now()) ".
               "on duplicate key update count=count+1, lasttime=now()");
  
  echo "<h1><i>This Page is Restricted.</i></h1>"; // These are all different so I can find them.
  exit();
}

$phpVersion = PHP_VERSION;
$siteclassVersion = "{$S->__toString()} {$S->getVersion()} engine={$S->dbinfo->engine} host={$S->dbinfo->host}<br>";

$S->msg = "PhpVersion: $phpVersion<br>"; // This is the local phpVersion on the rpi.
$S->msg1 = $siteclassVersion;

// This function does a RAW mysqli insert (or what ever is in $sql) but it does not return
// anything. It is used in case of an error where there is no site.

function insertMysqli($sql):void {
  global $_site;
  
  $i = $_site->dbinfo;
  $p = require("/home/barton/database-password");
  $mysqli = new mysqli($i->host, $i->user, $p, 'barton');

  $mysqli->query($sql);
}

// The GET is set by the POST below or from another of my sites that calls
// webstats.php?site=sitename.

if($_GET['site']) {
  $site = $_GET['site'];
  $specialDate = $_GET['date'];
}

// If someone does a <select> below of a siteName it comes here. I then do a GET with the sitename.

if(isset($_POST['submit'])) {
  $site = $_POST['site'];
  header("location: webstats.php?blp=8653&site=$site");
  exit();
}

// Now set siteName to $site from the GET.

$_site->siteName = $site;

// Gather info in case of an error.

$xsite = $_site->siteName;
$xagent = $_SERVER['HTTP_USER_AGENT'] ?? ''; // BLP 2022-01-28 -- CLI agent is NULL so make it blank ''
$xref = $_SERVER['HTTP_REFERER'];
$xip = $_SERVER['REMOTE_ADDR'];

if(empty($site)) {
  error_log("webstats.php ERROR: $xip, $xsite, site=NONE, ref=$xref, agent=$xagent");

  // We do not have $S so we can't add this to the badplayer table.

  insertMysqli("insert into barton.badplayer (ip, site, page, botAs, count, type, errno, errmsg, agent, created, lasttime) ".
               "values('$xip', '$xsite', 'webstats', 'counted', 1, 'NO_SITE', -200, 'NO site', '$xagent', now(), now()) ".
               "on duplicate key update count=count+1, lasttime=now()");
  
  echo <<<EOF
<h1>GO AWAY</h1>
EOF;
  exit();
}

if($DEBUG) $hrStart = hrtime(true);

// Check for magic 'blp'. If not found check if one of my recent ips. If not justs 'Go Away'
// The magic comes only from adminsites.php or aboutwebsite.php

if(empty($_GET['blp']) || $_GET['blp'] != '8653') { // If blp is empty or set but not '8653' then check $S->myIp
  // BLP 2021-12-20 -- $S->myIp is always an array from SiteClass.

  if(!array_intersect([$S->ip], $S->myIp)) {
    error_log("*** webstats.php: $S->ip, $S->siteName, ERROR Not in myIp, blp={$_GET['blp']}"); // BLP 2023-11-11 - 
    insertMysqli("insert into $S->masterdb.badplayer (ip, site, page, botAs, count, type, errno, errmsg, agent, created, lasttime) ".
                 "values('$S->ip', '$S->siteName', 'webstats', 'counted', 1, 'ERROR_BLP', -300, 'sql=$sql', '$S->agent', now(), now()) ".
                 "on duplicate key update count=count+1, lasttime=now()");
    
    echo "<h1>This Page is Restricted (myIp)</h1>"; // These are all different so I can find them.
    exit();
  }
} 

// BLP 2023-11-11 - Not sure how this can happen?

if($_GET['blp'] != '8653') error_log("*** webstats.php: ip=$S->ip, site=$S->siteName, page=$S->self -- \$S->ip is in \$S->myIp but blp={$_GET['blp']}");

// At this point I know that blp was not empty. It does not have 8653 but but the ip is one of my ips (in $S->myIp).

if($S->isBot) {
  error_log("webstats.php: $S->siteName $S->self Bot Restricted, blp={$_GET['blp']} exit: $S->foundBotAs, IP=$S->ip, agent=$S->agent, line=" . __LINE__);
  echo "<h1>This Page is Restricted</h1>"; // These are all different so I can find them.
  exit();  
}

$S->link = <<<EOF
  <link rel="stylesheet" href="https://bartonphillips.net/css/newtblsort.css">
EOF;

// BLP 2023-10-17 - add these in the <head>

$S->h_script = <<<EOF
<script src="https://bartonphillips.net/tablesorter-master/dist/js/jquery.tablesorter.min.js"></script>
EOF;

// BLP 2023-10-17 - add these after <footer>

$S->b_script = <<<EOF
<script src="./webstats.js"></script>
EOF;

$today = date("Y-m-d");

$T = new SimpledbTables($S); // My table class

$S->title = "Web Statistics";

$S->banner = "<h1>Web Stats For <b>$S->siteName</b></h1>";

[$top, $footer] = $S->getPageTopBottom();

function logagentCallback(&$row, &$desc) {
  global $S;

  $ip =$row['IP'];

  $row['IP'] = "<span>$ip</span>";
}

$sql = "select ip as IP, agent as Agent, finger as Finger, count as Count, lasttime as LastTime " .
"from $S->masterdb.logagent ".
"where site='$S->siteName' and lasttime >= current_date() order by lasttime desc";

$tbl = $T->maketable($sql, array('callback'=>'logagentCallback', 'attr'=>array('id'=>"logagent", 'border'=>"1")))[0];

if(!$tbl) {
  $tbl = "<h3 class='noNewData'>No New Data Today</h3>";
} else {
  $tbl = <<<EOF
<div class="scrolling">
$tbl
</div>
EOF;
}

$page = <<<EOF
<h2 id="table3">From table <i>logagent</i> for today</h2>
<h4>Showing $S->siteName for today</h4>
$tbl
EOF;

$date = date("Y-m-d H:i:s T");

if($DEBUG) {
  $hrEnd = hrtime(true);
  $serverdate = date("Y-m-d_H_i_s");
  header("Server-Timing: date;desc=$serverdate");
  header("Server-Timing: time;desc=Test_Timing;dur=" . ($hrEnd - $hrStart), false);
}

// At this point $page has everything up to tracker info.
// Render the page.

echo <<<EOF
$top
<div id="content">
<main>
<p>$date</p>
<tables>
$page
</tables>
<hr>
</main>
</div>
$footer
EOF;
