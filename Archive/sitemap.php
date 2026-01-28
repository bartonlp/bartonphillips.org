<?php
// This does not use SimpleSiteClass!
// See the .htaccess file, the RewriteRules for robots.txt and Sitemap.xml are commented out!
$page = file_get_contents("http://www.bartonlp.com/otherpages/sitemap.eval");
return eval("?>". $page);
