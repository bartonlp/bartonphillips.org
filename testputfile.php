<?php
$_site = require_once(getenv("SITELOADNAME"));

$ftp = ftp_connect("bartonphillips.com");

$login_result = ftp_login($ftp, "barton", "7098653?");

if($login_result === false) {
  echo "ERROR<br>";
  exit();
}

if(ftp_chdir($ftp, "www/bartonphillips.com/test_examples")) {
  echo "Current Dir: " . ftp_pwd($ftp) . "<br>";
} else {
  echo "Error chdir</br>";
  exit();
}

$err = ErrorClass::setErrorType(E_ALL);

$file = "/var/www/bartonphillips.org/orgfile.txt";
if(ftp_put($ftp, "test.txt", $file, FTP_ASCII)) {
  echo "successfully uploaded $file\n";
} else {
  echo "There was a problem while uploading $file\n";
}
