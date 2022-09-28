<?php
$page = file_get_contents("http://www.bartonlp.com/otherpages/robots.eval");
return eval("?>". $page);
