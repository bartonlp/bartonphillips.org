<?php
/* NOTE:
   I have my firewall set up to only allow bartonphillips.org and 157.245.129.4 (my DigitalOcean server) access the port 27017 (the MongoDB port).
   Look at the firewall via: sudo ufw status numbered.
   Also, my /etc/mongod.conf has bindIp set to 0.0.0.0 which allows anyone access. The firewall keeps others out!
*/

require(getenv("SITELOADNAME"));
use MongoDB\Client;

// Requires the MongoDB PHP Driver
// https://www.mongodb.com/docs/drivers/php/

$client = new Client('mongodb://barton_admin:bartonl411@bartonphillips.org:27017/');
$collection = $client->selectCollection('barton', 'tracker');
$cursor = $collection->aggregate([['$match' => ['site' => 'BartonphillipsOrg']], ['$sort' => ['lsttime' => 1]]]);

foreach($cursor as $item) {
  foreach($item as $key=>$i) {
    echo "$key: $i<br>";
  }
}
