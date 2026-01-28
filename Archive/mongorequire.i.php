<?php
// This file should only be required by my projects
// It instantiates the MongoDB\Client. It makes $client and $collection globals.
// It also uses the setGetCounter() function to get the 'count' from the collection and creates a
// div and a javaScript to place it in the footer #address.
/* NOTE:
   I have my firewall set up to only allow bartonphillips.org and 157.245.129.4 (my DigitalOcean server) access the port 27017 (the MongoDB port).
   Look at the firewall via: sudo ufw status numbered.
   Also, my /etc/mongod.conf has bindIp set to 0.0.0.0 which allows anyone access. The firewall keeps others out!

   I now use the Atlas free server (mongodb+srv://...).
*/

if(!$S) {
  header("location: https://bartonlp.com/otherpages/NotAuthorized.php");
}

function setGetCounter($S) {
  global $client, $collection;
  
  //$client = new MongoDB\Client('mongodb://barton_admin:bartonl411@bartonphillips.org:27017');
  // This uses the Atlas free server.
  //$client = new MongoDB\Client('mongodb+srv://barton:11KYG7oKKP2USHFa@cluster0.hwvadag.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0');
  $client = new MongoDB\Client('mongodb+srv://barton:uS3vxPnScyvtxK4G@cluster0.hwvadag.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0');
  $collection = $client->barton->logagent;

  // ... (Connection and initialization code as before)

  $currentTime = new DateTime('now');
  
  $site = $S->siteName; 
  $ip = $S->ip; 
  $page = $S->self; 
  $agent = $S->agent;
  $firsttime = $currentTime->format(DateTime::ISO8601);
  $lasttime = $currentTime->format(DateTime::ISO8601);
  
  $bot = $S->isBot($agent);
  
  // Check if document exists first

  $existingDocument = $collection->findOne([
                                            'site' => $site,
                                            'ip' => $ip,
                                            'page' => $page,
                                            'agent' => $agent,
                                           ]);

  if(!$existingDocument) {
    // New document, insert with count 1
    $collection->insertOne([
                            'site' => $site,
                            'ip' => $ip,
                            'page' => $page,
                            'count' => 1,
                            'agent' => $agent,
                            'bot' => $bot,
                            'firsttime' => $firsttime,
                            'lasttime' => $lasttime,
                           ]);
    $cnt = 1;
  } else {
    // Update existing document

    $collection->updateOne([
                            '_id' => $existingDocument['_id'],
                           ],
                           [
                            '$set' => ['lasttime'=>$lasttime],
                            '$inc' => ['count' => 1],
                           ]
                          );
    $cnt = $existingDocument['count'];
    $cnt++;
  }
  return $cnt;
}

$cnt = setGetCounter($S); // Call the function and make the div.

$counterWidget = "<div id='hitCounter'><table id='hitCountertbl'><tr id='hitCountertr'><th id='hitCounterth'>$cnt</th></tr></table></div>";

// Make an inlineScript. NOTE this sets the variable so if it is used in the file that requires it,
// it must be .=

$S->b_inlineScript =<<<EOF
// Put counter into the footer
$("#address").after("$counterWidget"); // $counterWidget is defined in PHP above.
EOF;

