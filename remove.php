<?php
require(getenv("SIMPLE_SITELOADNAME"));

$json = stripcomments(file_get_contents("mysitemap.json"));
if(file_put_contents("data.json", $json) === false) {
  echo "Can't write";
  exit();
}
echo "Done";

