<?php
// BLP 2022-07-31 - Test new changes to Database.

$_site = require_once(getenv("SITELOADNAME"));
require_once(SITECLASS_DIR . "/defines.php");
//$_site->isMeFalse = true; // force isMe() true

[$major, $minor, $release] = explode(".", phpversion());
error_log("testDatabase.php: php version: " . phpversion());

echo "PHP Version: major=$major, minor=$minor, release=$release<br>";

$S = new Database($_site);
$T = new dbTables($S);
$tbl = $T->maketable("select id, site from $S->masterdb.tracker limit 1",["attr"=>["id"=>"test", "border"=>"1"]])[0];
echo $tbl;

echo "Database<br>";

error_log("testDatabase.php: " . get_class($S->db) . " version: " . $S->db->getVersion());
echo get_class($S->db)." Class " . $S->db->getVersion() . "<br>";

vardump("myIp", $S->myIp);

error_log("testDatabase.php: " . get_class($S) . " version: " . $S->getVersion());
echo get_class($S)." Class ".$S->getVersion() . "<br>";

echo "isMe=".($S->isMe() ? "true" : "false")."<br>";
echo $S->getIp()."<br>";
echo "\$S->isBot(): ".($S->isBot("testitbotasathing") ? "true" : "false")."<br>";
echo "isBot: ".($S->isBot ? "true" : "false")."<br>";
echo $S->__toString(). "<br>";
echo get_class($S)."<br>";
$ips = implode(",", preg_replace("~(\S+)~", "'$1'", $S->myIp));
echo "ips: $ips<br>";
echo "isMyIp('123.12.12.1'): ".($S->isMyIp('123.12.12.1') ? "true" : "false")."<br>";
echo "isMyIp('74.192.14.176'): ".($S->isMyIp('74.192.14.176') ? "true" : "false")."<br>";
error_log("testDatabase.php: ". get_class($S->errorClass) . " version: " . $S->errorClass->getVersion());
echo get_class($S->errorClass)." Class ". $S->errorClass->getVersion()."<br>";

$S = new SiteClass($_site);
$T = new dbTables($S);

echo "<br>SiteClass<br>";

error_log("testDatabase.php: " . get_class($T) . " version: " . $T->getVersion());
echo get_class($T)." Class ". $T->getVersion()."<br>";

vardump("myIp", $S->myIp);
error_log("testDatabase.php: " . get_class($S->db) . " version: " . $S->getVersion());
echo get_class($S)." Class ".$S->getVersion() . "<br>";

echo "isMe=".($S->isMe() ? "true" : "false")."<br>";
echo $S->getIp()."<br>";
echo "\$S->isBot(): ".($S->isBot("testitbotasathing") ? "true" : "false")."<br>";
echo "isBot: ".($S->isBot ? "true" : "false")."<br>";
echo $S->__toString(). "<br>";
echo get_class($S)."<br>";

$err = new ErrorClass;
echo get_class($err)." Class " . ErrorClass::getVersion()."<br>";
error_log("testDatabase.php: " . get_class(dbAbstract) . " version: " . $S->getAbstractVersion());
echo get_class($S) ." dbAbstract Version: " . $S->getAbstractVersion()."<br>";

echo "DB Name: " .$S->getDbName()."<br>";

error_log("testDatabase.php: helper version: " . getVersion());
echo "helper version: ". getVersion()."<br>";

try {
  throw new SqlException("this is an error");
} catch(Exception $e) {
  echo "ERROR: ";
  echo $e->getCode() . "<br>";
  echo $e->getMessage() ."<br>";
  echo get_class($e)." Class ".$e->getVersion()."<br>";
}
try {
  $S->query("select * from b");
} catch(Exception $e) {
  echo "Error: ".$e->getCode().", ".$e->getMessage()."<br>";
}
try {
  $S->query("select * from members limit 2");
  echo "DATA<br>";
  while($row = $S->fetchrow('num')) {
    //echo "row type: ".gettype($row)."<br>";
    $rows = implode('<br>', $row);
    echo "$rows<br>";
  }
  echo "END DATA<br>";
} catch(Exception $e) {
  echo "Error: ".$e->getCode(). ", ".$e->getMessage()."<br>";
}

require(SITECLASS_DIR . "/defines.php");

error_log("testDatabase.php: tracker version: " . DEFINES_VERSION);
error_log("testDatabase.php: siteload version: " . SITELOAD_VERSION);
error_log("testDatabase.php: sql excepton version: " .SQLEXCEPTION_CLASS_VERSION);


