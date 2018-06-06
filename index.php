<?php
// Main page for bartonphillips.org
$_site = require_once(getenv("SITELOADNAME"));
$S = new $_site->className($_site);

// if this is a bot don't bother with getting a location.

if($S->isBot) {
  $locstr = '';
} else {
  $ref = $_SERVER['HTTP_REFERER'];

  if($ref) {
    if(preg_match("~(.*?)\?~", $ref, $m)) $ref = $m[1];
    $ref =<<<EOF
<li>You came to this site from: <i class='green'>$ref</i></li>
EOF;
  }
  
  // Use ipinfo.io to get the country for the ip
  $cmd = "http://ipinfo.io/$S->ip";
  $ch = curl_init($cmd);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $loc = json_decode(curl_exec($ch));
  
  $locstr = <<<EOF
<ul class="user-info">
  $ref
  <li>User Agent String is:<br>
    <i class='green'>$S->agent</i></li>
  <li>IP Address: <i class='green'>$S->ip</i></li>
  <li>Hostname: <i class='green'>$loc->hostname</i></li>
  <li>Location: <i class='green'>$loc->city, $loc->region $loc->postal</i></li>
  <li>GPS Loc: <i class='green'>$loc->loc</i></li>
  <li>ISP: <i class='green'>$loc->org</i></li>
</ul>
EOF;
} // End of if(isBot..

// css/blp.css is included in head.i.php

$h->css = <<<EOF
  <!-- Local CSS -->
  <style>
body {
  background-color: hsla(115, 55%, 55%, 0.35);
}
.locstr {
  margin: 1rem 0;
}
.user-info {
  line-height: 1rem;
  margin: 0px;
}
.hereMsg {
  font-size: 1.2rem;
  font-weight: bold;
  padding-top: 1rem;
}
h1 {
  text-align: center;
}
/* Colors */
.green {
  color: green;
}
.red {
  color: red;
}
#show {
  display: block;
  width: 32rem;
  height: 23rem;

  margin: 0 auto .5rem;
  overflow: hidden;
}
#show img {
  width: 100%;
}
/* Sections */
#browser-info { /* section */
  border-top: 1px solid gray;
}
#blog { /* section */
  width: 40%;
  text-align: center;
  background-color: #FCF6CF;
  padding: 20px;
  margin: auto;
  border: 1px solid #696969;
}
  </style>
EOF;

$h->script .= <<<EOF
  <!-- local script -->
  <script src="https://bartonphillips.net/js/phpdate.js"></script>
  <script src="https://bartonphillips.net/js/yimage.js"></script>
  <script>
// This does the banner logic and places the images in "#show" below.
// The path to images on rpi is the first agrument. The second arg tells dobanner to not use
// recursion. There can be a third arg which is 'seq' to tell the function to use sequential
// numbers rather than a random number for the index into the glob array.

dobanner("Pictures/Germany2010/*.JPG", 'no');

jQuery(document).ready(function($) {
  // Date Today
  setInterval(function() {
    var d = date("l F j, Y");
    var t = date("H:i:s T"); // from phpdate.js
    $("#datetoday").html("<span class='green'>"+d+"</span><br>Your Time is: <span class='green'>"+t+"</span>");
  }, 1000);
});
  </script>  
EOF;

$h->link =<<<EOF
  <link rel="canonical" href="https://www.bartonphillips.com">
EOF;

$h->title = $S->siteName;

$h->banner = <<<EOF
<h1>$S->mainTitle</h1>
EOF;

list($top, $footer) = $S->getPageTopBottom($h);

// Do we have a cookie? If not offer to register

if(!($hereId = $_COOKIE['SiteId'])) {
  $S->query("select count, date(created) from $S->masterdb.logagent ".
            "where ip='$S->ip' and agent='$S->agent' and site='$S->siteName'");

  list($hereCount, $created) = $S->fetchrow('num');
  if($hereCount > 1) {
    $hereMsg =<<<EOF
<div class="hereMsg">You have been to our site $hereCount since $created<br>
Why not <a href="register.php">register</a>
</div>
EOF;
  }
} else {
  $sql = "select name from members where id=$hereId";
  if($n = $S->query($sql)) {
    list($memberName) = $S->fetchrow('num');
    $hereMsg =<<<EOF
<div class="hereMsg">Welcome $memberName</div>
EOF;
  } else {
    error_log("$S->siteName: members id ($hereId) not found at line ".__LINE__);
  }
}

date_default_timezone_set("America/New_York");

$date = date("l F j, Y H:i:s T");

// ***************
// Render the page
// ***************

echo <<<EOF
$top
$fromAltoRouter
<!-- #show is where the dobanner() images are shown. -->
<section id='show'></section>

<section id='browser-info'>
$hereMsg
<div class="locstr">
   Our domain is <i>bartonphillips.org</i><br/>
   $locstr
Start: <span class='green'>$date in New Bern, NC</span><br>
Today is: <span id="datetoday">$date</span></div>
<p>Try our <a href='https://www.bartonphillips.com'>Home Page (www.bartonphillips.com)</a></p>
</section>
<section id="blog">
<a target="_blank" href="proxy.php?https://bartonplp.blogspot.com">My BLOG with tips and tricks</a>.
</section>
$footer
EOF;
