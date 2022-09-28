<?php
$page = file_get_contents("http://www.bartonlp.com/otherpages/sitemap.eval");
return eval("?>". $page);
