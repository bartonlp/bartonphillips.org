<?php
// This program is run from crontab via all-cron.sh in www/bartonlp/scripts.
// BLP 2021-03-24 -- removed links to yahoo pure stuff. Added webstats.css which has the pure
// stuff we need. Removed extranious divs also.
// NOTE: this file is not usually called directly by anything other than a cron. All of the
// info in webstats.php comes from https:bartonphillips.net/analysis/ where we have the
// $site-analysis.i.txt files that this program creates.
// BLP 2017-11-01 -- all-cron.sh runs update-analysis.sh
// BLP 2016-09-03 -- change ftp password to '7098653?' note without single quotes

$page = file_get_contents("https://bartonlp.com/otherpages/analysis.eval");
return eval("?>". $page);
exit();
