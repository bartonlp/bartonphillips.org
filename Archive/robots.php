<?php
// This does not use SimpleSiteClass!
// See the .htaccess file, the RewriteRules for robots.txt and Sitemap.xml are commented out!
$page = file_get_contents("https://bartonlp.com/otherpages/robots.eval");
return eval("?>". $page);
