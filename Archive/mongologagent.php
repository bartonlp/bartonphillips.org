<?php
// Display the MongoDB logagent collection
// Uses SimpleSiteClass and mongorequire.i.php which loads the $client and $collection

$_site = require_once(getenv("SITELOADNAME")); $siteload = "vendor siteload.php";

SimpleErrorClass::setDevelopment(true);

$S = new SimpleSiteClass($_site);

$S->title = "Show MongoDB logagent";
$S->banner = "<h1>$S->title</h1>";

$siteInfo = "{$S->__toString()}, Version: {$S->getVersion()}, Host: {$S->dbinfo->host}, Engine: {$S->dbinfo->engine}";

// Get the mongo logic.
/* NOTE:
   I have my firewall set up to only allow bartonphillips.org and 157.245.129.4 (my DigitalOcean server) access the port 27017 (the MongoDB port).
   Look at the firewall via: sudo ufw status numbered.
   Also, my /etc/mongod.conf has bindIp set to 0.0.0.0 which allows anyone access. The firewall keeps others out!
*/

require("mongorequire.i.php");

$targetDate = date("Y-m-d");

// Get the MongoDB collectin and find all lasttime gt current_date();

$cursor = $collection->find(['lasttime'=>['$gt'=>$targetDate]]);

foreach($cursor as $doc) {
  extract((array)$doc);
  
  $tbl .= sprintf("<tr><td>%s</td><td><a target='_blank' href='https://bartonphillips.com/findip.php?where=%s&by=%s'>%s</a></td><td>%s</td>".
                  "<td>%s</td><td>%s</td>".
                  "<td>%s</td><td>%s</td></tr>",
                  $site, urlencode("where ip='$ip'"), urlencode("order by lasttime"), $ip, $page, $agent, $count, (($bot === false) ? 'false' : 'true'), $lasttime);
}

$tbl =<<<EOF
<table border='1'>
<thead>
<tr><th>Site</th><th>IP</th><th>Page</th><th>Agent</th><th>Count</th><th>Bot</th><th>Last Time</th></tr>
</thead>
<tbody>$tbl</tbody>
</table>
EOF;

[$top, $footer] = $S->getPageTopBottom();

echo <<<EOF
$top
<hr>
<p>$siteInfo</p>
$tbl
<hr>
$footer
EOF;
