<?php
// This is like index

$_site = require_once("/var/www/vendor/bartonlp/site-class/includes/siteload.php");

//$_site->noTrack = $_site->noGeo = true;

$S = new SiteClass($_site);

/* NOTE:
   I have my firewall set up to only allow bartonphillips.org and 157.245.129.4 (my DigitalOcean server) access the port 27017 (the MongoDB port).
   Look at the firewall via: sudo ufw status numbered.
   Also, my /etc/mongod.conf has bindIp set to 0.0.0.0 which allows anyone access. The firewall keeps others out!
*/
$cnt = require("mongotracker.i.php");

$reflector = new ReflectionExtension('mongodb');
$version = $reflector->getVersion();

echo "MongoDB Driver Version: " . $version . "<br>";

echo "Count for this site: $cnt<br>";

$cnt = 0;

foreach($collection->find() as $cur) {
  foreach($cur as $k=>$doc) {
    if($k == 'bot') {
      foreach($doc as $kk=>$dd) {
        //vardump($k, $kk, $d);
        echo "&nbsp;&nbsp;&nbsp;&nbsp;$k: $kk=$dd<br>";
      }
      echo "<br>";
    } else {
      echo "$k=$doc<br>";
    }
  }
  echo "<br>";
  $cnt++;
}

echo "Number of items: $cnt<br><br>";

$pipeline = [
             ['$match' => ['bot' => [ '$exists' => true ]]], // Check for documents with "bot" field
             ['$project' => ['_id' => 1, 'ip' => 1, 'count' => 1, 'firsttime' => 1, 'lasttime' => 1, 'isBot' =>'$bot.isBot', 'botCount' => '$bot.botCount']],
            ];

$db = $client->barton;

/*
$cursor = $db->command([
                        'aggregate' => 'tracker', // Replace with any collection name, it won't be used
                        'pipeline' => $pipeline,
                        'cursor' => [ // Allow batching for efficiency
                                     'batchSize' => 500,
                                    ]
                       ]);
*/
$cursor = $collection->aggregate([['$match' => ['bot' => ['$exists' => true]]], ['$project' => ['_id' => 1, 'ip' => 1, 'count' => 1, 'isBot' => '$bot.isBot', 'botCount' => '$bot.botCount']]]);

//vardump("cursor", $cursor);
echo "Collection: (found document with 'bot' field).<br><br>";

foreach ($cursor as $doc) {
  //vardump("doc", $doc);
  foreach($doc as $k=>$v) {
    echo "  $k: $v<br>";
  }
  echo "<br>";
}

echo "Done<br>";
