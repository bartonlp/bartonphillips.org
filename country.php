<?php
// This uses BigDataCloud. I got the file countries-codes.json from them.
// https://www.bigdatacloud.com/account. The account name and password are in Dashlane.
// Go to the account and click on 'Credentials' for the API key.
// My account ID is: a0fc067d-697f-4099-860e-5ef96c95e857

$_site = require_once getenv("SIMPLE_SITELOADNAME");
$S = new SimpleSiteClass($_site);
$key = require '/home/barton/PASSWORDSandKeys/BigDataCloudAPI-key';

/*
if(($json = file_get_contents("https://api.ip2location.io/?key=68C94E7B6A47592F69EF30A5647CA167&ip=45.134.142.212")) === false) exit("ip2location failed");
$info = json_decode($json, true);
vardump("info", $info);
*/

/*
if(($json = file_get_contents("https://api-bdc.net/data/client-ip")) === false) exit("get client-ip failed");
$info = json_decode($json, true);
vardump("info", $info);

if(($json = file_get_contents("https://api-bdc.net/data/network-by-cidr?cidr=107.189.8.16&depthLimit=2&bogonsOnly=false&localityLanguage=en&key=$key")) === false) exit("get cidr failed");
$info = json_decode($json, true);
vardump("info", $info);

if(($json = file_get_contents("https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=35.0978048&longitude=-77.070336&localityLanguage=en")) === false) exit("get cidr failed");
$info = json_decode($json, true);
vardump("info", $info);

if(($json = file_get_contents("https://api-bdc.net/data/email-verify?emailAddress=Mail@bartonphillips.com&key=$key")) === false) exit("email verfiy failed");
$info = json_decode($json, true);
vardump("info", $info);

if(($json = file_get_contents("https://api.ip2whois.com/v2?key=68C94E7B6A47592F69EF30A5647CA167&domain=bartonlp.org")) === false) exit("whois failed");
$info = json_decode($json, true);
vardump("info", $info);

if(($json = file_get_contents("https://api.ip2whois.com/v2?key=68C94E7B6A47592F69EF30A5647CA167&domain=bartonlp.com")) === false) exit("whois failed");
$info = json_decode($json, true);
vardump("info", $info);
*/
/*
if(($json = file_get_contents("/home/barton/Downloads/countries-codes.json")) === false) exit("get failed");

$info = json_decode($json, true);
foreach($info as $v) {
  $ar[$v['iso2_code']] = $v['label_en'];
}

ksort($ar);
foreach($ar as $k=>$v) {
  echo "$k, $v<br>";
}
*/

/*
if(($json = file_get_contents("https://api.bigdatacloud.net/data/tor-exit-nodes-list?batchSize=1000&offset=900&localityLanguage=en&key=$key")) === false) exit("tor failed");
$info = json_decode($json, true);
vardump("info", $info);
*/
$ip = '109.70.100.67';
echo "ip=$ip<br>";
if(($json = file_get_contents("https://api-bdc.net/data/hazard-report?ip=$ip&key=&key=$key")) === false) exit("tor failed");
$info = json_decode($json, true);
vardump("info", $info);
