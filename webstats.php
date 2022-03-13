<?php
$page = file_get_contents("https://bartonphillips.net/webstats.eval");
return eval("?>". $page);
