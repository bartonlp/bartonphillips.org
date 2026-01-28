<?php
// BLP 2023-10-18 - this does the javascript setup for webstats.php, findip.php and anything else
// that needs these values.

// This should not be called from a browser.
// $S must already by instantiated

if(!class_exists("SimpleDatabase")) header("location: https://bartonlp.com/otherpages/NotAuthorized.php");

// ********************************************
// BLP 2023-10-17 - START Set up the JavaScript

$myIp = implode(",", $S->myIp);
$homeIp = gethostbyname("bartonphillips.org");

$myIp1 = '"'.$myIp.'"'; // BLP 2023-10-17 - make $myIp and $homeIp have quotes around them for javascript.
$homeIp1 = '"'.$homeIp.'"';

$mask = TRACKER_BOT | TRACKER_NORMAL | TRACKER_NOSCRIPT | TRACKER_CSS | TRACKER_ME | TRACKER_GOTO | TRACKER_GOAWAY;

$robots = BOTS_ROBOTS;
$sitemap = BOTS_SITEMAP;
$siteclass = BOTS_SITECLASS;
$zero = BOTS_CRON_ZERO;

$start = TRACKER_START;
$load = TRACKER_LOAD;
$normal = TRACKER_NORMAL;
$noscript = TRACKER_NOSCRIPT;
$bvisibilitychange = BEACON_VISIBILITYCHANGE;
$bpagehide = BEACON_PAGEHIDE;
$bunload = BEACON_UNLOAD;
$bbeforeunload = BEACON_BEFOREUNLOAD;
$timer = TRACKER_TIMER;
$bot = TRACKER_BOT;
$css = TRACKER_CSS;
$me = TRACKER_ME;
$goto = TRACKER_GOTO; // Proxy
$goaway = TRACKER_GOAWAY; // unusal tracker.
$checktracker = CHECKTRACKER; // BLP 2023-10-20 - Added by checktracker2.php

// BLP 2023-10-17 - inlineScript to set up the javascript constants

$S->h_inlineScript = <<<EOF
const myIp = $myIp1;
const homeIp = $homeIp1;
const mask = $mask;
const robots = {"$robots": "Robots", "$siteclass": "BOT", "$sitemap": "Sitemap", "$zero": "Zero"};
const tracker = {
  "$start": "Start", "$load": "Load", "$normal": "Normal", "$noscript": "NoScript",
  "$bvisibilitychange": "B-VisChange", "$bpagehide": "B-PageHide", "$bunload": "B-Unload", "$bbeforeunload": "B-BeforeUnload",
  "$timer": "Timer", "$bot": "BOT", "$css": "Csstest", "$me": "isMe", "$goto": "Proxy", "$goaway": "GoAway", "$checktracker": "ADDED"
};
EOF;

// BLP 2023-10-17 - END Set up the JavaScript
// ******************************************

