<?php
// The .htaccess file has: ReWriteRule ^robots.txt$ robots.php [L,NC]
// This file reads the rotbots.txt file and outputs it and then gets the user agent string and
// saves it in the bots table.
// NOTE: this file can only be run using mysqli or PDO with the mysql engine!
/*
CREATE TABLE `bots` (
  `ip` varchar(40) NOT NULL DEFAULT '',
  `agent` text NOT NULL,
  `count` int DEFAULT NULL,
  `robots` int DEFAULT '0',
  `site` varchar(255) DEFAULT NULL, // this is $who which can be multiple sites seperated by commas.
  `creation_time` datetime DEFAULT NULL,
  `lasttime` datetime DEFAULT NULL,
  PRIMARY KEY (`ip`,`agent`(254))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `bots2` (
  `ip` varchar(40) NOT NULL DEFAULT '',
  `agent` text NOT NULL,
  `page` text,
  `date` date NOT NULL,
  `site` varchar(50) NOT NULL DEFAULT '', 
  `which` int NOT NULL DEFAULT '0',
  `count` int DEFAULT NULL,
  `lasttime` datetime DEFAULT NULL,
PRIMARY KEY (`ip`,`agent`(254),`date`,`site`,`which`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
*/

define("ROBOT_VERSION", '1.0.0Hp-envy');

$_site = require_once(getenv("SIMPLE_SITELOADNAME"));
$_site->noTrack = true;
$_site->noGeo = true;
$S = new SimpleDatabase($_site);

$rob = BOTS_ROBOTS;

if(!file_exists($S->path . "/robots.txt")) {
  echo "<h1>404 - FILE NOT FOUND</h1>";
  exit();
}

$robots = file_get_contents("./robots.txt");
header("Content-Type: text/plain");
echo $robots . "\n# From robots.php\n";

if($S->isMe()) return;

$agent = $S->agent;
$ip = $S->ip;

try {
  // BLP 2021-12-26 -- robots is 1 if we do an insert or robots=robots|2 

  $S->sql("insert into $S->masterdb.bots (ip, agent, count, robots, site, creation_time, lasttime) ".
          "values('$ip', '$agent', 1, $rob, '$S->siteName', now(), now())");
} catch(Exception $e) {
  if($e->getCode() == 1062 || $e->getCode() == 23000) { // duplicate key
    $S->sql("select site from $S->masterdb.bots where ip='$ip' and agent='$agent'");

    $who = $S->fetchrow('num')[0];

    if(!$who) {
      $who = $S->siteName;
    }
    if(strpos($who, $S->siteName) === false) {
      $who .= ", $S->siteName";
    }

    $S->sql("update $S->masterdb.bots set robots=robots|$rob, count=count +1, site='$who', lasttime=now() ".
            "where ip='$ip'");
  } else {
    error_log("robots: ".print_r($e, true));
  }
}

// Insert or update logagent

$S->sql("insert into $S->masterdb.logagent (site, ip, agent, count, created, lasttime) values('$S->siteName', '$ip', '$agent', 1, now(), now()) ".
        "on duplicate key update count=count+1, lasttime=now()");
