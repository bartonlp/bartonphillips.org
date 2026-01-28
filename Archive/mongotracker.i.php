<?php
// PASSWORDS         USERNAME
// 11KYG7oKKP2USHFa, barton
// zPcn5bue0hrFVGAe, bartonphillips
// These are the credentials for the Atlas free sandbox
//*******************************************************

// This file should only be required by my projects
// It instantiates the MongoDB\Client. It makes $client and $collection globals.
// It also uses the setGetCounter() function to get the 'count' from the collection and creates a
// div and a javaScript to place it in the footer #address.
/* NOTE:
   I have my firewall set up to only allow bartonphillips.org and 157.245.129.4 (my DigitalOcean server) access the port 27017 (the MongoDB port).
   Look at the firewall via: sudo ufw status numbered.
   Also, my /etc/mongod.conf has bindIp set to 0.0.0.0 which allows anyone access. The firewall keeps others out!
*/

if(!$S) {
  header("location: https://bartonlp.com/otherpages/NotAuthorized.php");
}

//$client = new MongoDB\Client('mongodb://barton_admin:bartonl411@bartonphillips.org:27017');
//$client = new
//MongoDB\Client('mongodb+srv://barton:11KYG7oKKP2USHFa@cluster0.hwvadag.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0');
$client = new MongoDB\Client('mongodb+srv://barton:uS3vxPnScyvtxK4G@cluster0.hwvadag.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0');
//$client = new MongoDB\Client('mongodb+srv://bartonphillips:zPcn5bue0hrFVGAe@cluster0.hwvadag.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0');
$collection = $client->barton->tracker;

$currentTime = new DateTime('now');

$site = $S->siteName; 
$ip = $S->ip; 
$page = $S->self; 
$agent = $S->agent;
$firsttime = $currentTime->format(DateTime::ISO8601);
$lasttime = $currentTime->format(DateTime::ISO8601);

$isBot = $S->isBot($agent);

// Check if document exists first

$existingDocument = $collection->findOne([
                                          'site' => $site,
                                          'ip' => $ip,
                                          'page' => $page,
                                          'agent' => $agent,
                                         ]
                                        );

if(!$existingDocument) {
  // New document, insert with count 1
  
  //echo "Init: firsttime=$firsttime, lasttime=$lasttime<br>";

  $doc = [
          'site' => $site,
          'ip' => $ip,
          'page' => $page,
          'agent' => $agent,
          'count' => 1,
          'firsttime' => $firsttime,
          'lasttime' => $lasttime,          
  ];

  $bot = [];

  if($isBot === true) {
    $doc['bot'] = ['isBot' => true, 'botCount'=>1];
  } 

  $lastInsert = $collection->insertOne($doc);
  
  $cnt = 1;
} else {
  if(isset($existingDocument['bot'])) {
    $set = ['$set' => ['lasttime'=>$lasttime], '$inc'=>['count'=>1,'bot.botCount'=>1]];
  } else {
    if($isBot === true) {
      $set = ['$set' => ['lasttime'=>$lasttime, 'bot'=>['isBot' => true, 'botCount'=>1]], '$inc'=>['count'=>1]];
    } else {
      $set = ['$set' => ['lasttime'=>$lasttime], '$inc'=>['count'=>1]];
    }
  }

  $result = $collection->updateOne(
                                   [
                                    '_id' => $existingDocument['_id'],
                                   ],
                                   $set,
                                  );
  
  if($result->isAcknowledged() && $result->getModifiedCount() === 1) {
    //echo "Update Okay<br>";
  } else {
    echo "Update Error<br>";
  }

  $cnt = $existingDocument['count'];
  $cnt++;
}
//$client->close();

return $cnt;
