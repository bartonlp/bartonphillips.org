<?php
// BLP 2023-02-25 - use new approach
// All sites that have an "About Website" link should have a symlink
// to /var/www/bartonlp.com/otherpages/aboutwebsite.php
  
$_site = require_once(getenv("SIMPLE_SITELOADNAME"));
$S = new SimpleSiteClass($_site);

// check for subdomain. This doesn't need to be rigorous as we will Never have a multiple
// subdomain like en.test.domain.com. At most we might have www. or mpc.

$site = $_GET['site'];
$webdomain = $_GET['domain'];

if(empty($site) || empty($webdomain)) {
  $S->sql("insert into $S->masterdb.badplayer (ip, site, botAs, type, count, errno, errmsg, agent, created, lasttime) ".
            "values('$S->ip', '$S->siteName', 'counted', 'ABOUTWEBSITE', 1, '-202', 'No site or domain provided', '$S->agent', now(), now()) ".
            "on duplicate key update count=count+1, lasttime=now()");

  $tmp = "$site, $webdomain";
  error_log("aboutwebsite.php NO_SITE_OR_WEBDOMAIN: ip=$S->ip, \$S->sitename='$S->siteName', site/webdomain=$tmp, agent=$S->agent");

  echo <<<EOF
<!DOCTYPE html>
<head>
<title>Go Away</title>
<meta name='robots' content='noindex'>
</head>
<body>
<h1>NOT AUTHORIZED</h1>
</body>
</html>
EOF;
  exit();
}

$prefix = $_SERVER['HTTPS'] == "on" ? 'https://' : 'http://';

$webdomain = $prefix . $webdomain;

$S->title = "About This Website and Server";
$S->banner = "<h2 class='center'>About This Website and Server<br>On $site</h2>";
$phpVersion = PHP_VERSION;
$siteclassVersion = "{$S->__toString()} {$S->getVersion()} engine={$S->dbinfo->engine} host={$S->dbinfo->host}<br>";

$S->msg = "PhpVersion: $phpVersion<br>"; // This is the local phpVersion on the rpi.
$S->msg1 = $siteclassVersion;

$S->css = <<<EOF
img { border: 0; }
/* About this website (aboutwebsite.php)  */
#aboutWebSite {
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 2em;
        display: block;
        width: 100%;
        text-align: center;
}
#runWith {
        background-color: white;
        border: groove blue 10px;
        margin: 2em;
}
img[alt="jQuery logo"] {
        background-color: black;
        width: 215px;
}
img[alt="100% Microsoft Free"] {
        width: 100px;
}
img[alt="Powered By ...?"] {
        width: 90px;
        height: 53px;
}
img[alt="DigitalOcean"] {
        width: 200px;
        height: 60px;
        vertical-align: middle;
}
img[alt="Apache"] {
        width: 400px;
        height: 148px;
}
img[alt="PHP Powered"], img[alt="Powered by MySql"] {
        width: 150px;
        heitht: 50px;
}
img[alt="Best viewed with Mozilla or any other browser"] {
        width: 321px;
}
@media (max-width: 800px) {
        #runWith {
          width: 94%;
          margin: 0px;
        }
}
EOF;

[$top, $footer] = $S->getPageTopBottom();

echo <<<EOF
$top
<div id="aboutWebSite">
<div id="runWith">
  <p>This site's designer is Barton L. Phillips<br/>
     at <a href="https://www.bartonphillips.com">www.bartonphillips.com</a><br>
     Copyright &copy; $S->copyright<br>
     Your IP Address: $S->ip
  </p>
  
	<p>This site is hosted at
    <a href="https://www.digitalocean.com">
		  <img src="https://bartonphillips.net/images/aboutsite/digitalocean.jpg"
		    alt="DigitalOcean">
		</a>
  </p>
  <p>This site is run with Linux, Apache, MySql, PHP and jQurey<br>
    <img src="https://bartonphillips.net/images/aboutsite/linux-powered.gif"
      alt="Linux Powered">
  </p>
	<p>
    <a href="https://www.apache.org/">
    <img src="https://bartonphillips.net/images/aboutsite/apache_logo.gif"
      alt="Apache">
    </a>
  </p>
	<p>
    <a href="https://www.mysql.com">
      <img src="https://bartonphillips.net/images/aboutsite/powered_by_mysql.gif"
        alt="Powered by MySql">
    </a>
  </p>
	<p>
    <a href="https://www.php.net">
      <img src="https://bartonphillips.net/images/aboutsite/php-small-white.png"
        alt="PHP Powered">
    </a>
  </p>
  <p>
    <a href="https://jquery.com/">
      <img src="https://bartonphillips.net/images/aboutsite/logo_jquery_215x53.gif"
        alt="jQuery logo">
    </a>
  </p>
	<p>
    <img src="https://bartonphillips.net/images/aboutsite/msfree.png"
      alt="100% Microsoft Free">
  </p>
	<p>
    <a href="https://toolbar.netcraft.com/site_report?url=$webdomain#history_table">
	    <img src="https://bartonphillips.net/images/aboutsite/powered.gif"
        alt="Powered By ...?">
    </a>
	</p>
</div>
<p><a href="./webstats.php?blp=8653&site=$site">Web Statistics for $site</a></p>
</div>
$footer
EOF;
