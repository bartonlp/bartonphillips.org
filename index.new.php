<?php
// BLP 2024-07-01 - NOTE, the mysitemap.json should have:
// "trackerLocationJs": "https://bartonphillips.org/site-class/includes/tracker.js",
// "trackerLocation": "https://bartonphillips.org/site-class/includes/tracker.php" and
// "beaconLocation": "https://bartonphillips.org/site-class/includes/beacon.php"
// set so we do everthing on this (HP-envy) server!

// BLP 2024-04-14 - Switched back to SiteClass and removed MongoDb stuff.

// Normal start using SITELOADNAME

$_site = require_once "/var/www/vendor/bartonlp/site-class/includes/siteload.php";
$_site->doSiteClass = true;
//site->noTrack = false;
// Comment out the normal start and use one of the following for testing.
//$_site = require_once "/var/www/vendor/bartonlp/site-class/includes/autoload.php";
//$_site->doSiteClass = true;
//vardump("site", $_site);
if(is_null($_site)) {
  echo "\$_site is null<br>";
  error_log("bartonphillips.org/index.php, SiteClass with PDO: \$_site is NULL");
  exit();
}

ErrorClass::setDevelopment(true);

//$_site->dbinfo->engine = 'sqlite';
//$_site->noTrack = true;

// AJAX POST

if($_POST['path']) {
  function glob_recursive($pattern, $flags = 0) {
    $files = glob($pattern, $flags);
        
    foreach(glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT|GLOB_MARK) as $dir) {
      $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
    }
        
    return $files;
  }

  $path = $_POST['path'];

  if(!$path) {
    $x = glob_recursive("*.JPG"); // we are looking for JPG
    array_push($x, glob_recursive("*.jpg")); // and also jpg
    array_push($x, glob_recursive("*.PNG")); // and also PNG
    array_push($x, glob_recursive("*.png")); // and also png
    $x = array_merge(...$x); // flatten the array of arrays
  } else {
    if($_POST['recursive'] == 'yes') {
      $x = glob_recursive($path);
    } else {
      $x = glob($path);
    }
  }
  if($_POST['mode'] == 'rand') {
    shuffle($x);
  }

  if($_POST['size']) {
    $x = array_slice($x, 0, $_POST['size']); // get from zero to size only.
  }

  // Turn the array into a string of lines with a \n

  foreach($x as $v) {
    $banner_photos .= "$v\n";
  }

  $banner_photos = rtrim($banner_photos, "\n");

  // Send this back to the Ajax function

  echo $banner_photos;
  exit();
}

$S = new SiteClass($_site);

$phpVersion = explode('-', PHP_VERSION)[0];
$noTrack = ($_site->noTrack === true) ? "true" : "false";

ob_start(); // Start output buffering
require "/var/www/composer.lock";
$x= ob_get_clean();

if(($n = preg_match("~\"url\": \"https://github.com/bartonlp/site-class.git\",\n *\"reference\": \"(.*?)\"~", $x, $m)) === false) {
  exit("ERROR");
}
$reporef = substr($m[1], 0, 7);

$siteclassVersion = "{$S->__toString()}={$S->getVersion()}<br>engine={$S->dbinfo->engine}, noTrack=$noTrack<br>".
                    "siteload=" . SITELOAD_VERSION . ", reporef=$reporef<br>";

$S->msg = "PhpVersion: $phpVersion<br>"; // This is the local phpVersion on the this server.
$S->msg1 = $siteclassVersion;

$S->css = <<<EOF
header h1 { line-height: 1.5rem; } /* reduce line height */
span {
  font-size: 1.2rem;
}

/* The 'section' for the and the img. Make it max-height */
#show {
  /*border: 1px solid red;*/
  width: 30rem;
  margin: auto;
  text-align: center;
}
#show img {
  width: 20rem; /*100%;*/
  max-height: 20rem;
  object-fit: contain;
}
#show-message { text-align: center; }
@media (hover: none) and (pointer: coarse) {
  .desktop {
    display: none;
  }
}
@media (hover: hover) and (pointer: fine) {
  .phone {
    display: none;
  }
}

.AWAD_title
{font-size: 10pt; color: silver; font-weight: bold;}
.AWAD_wordlink
{text-decoration: none; color: green;}
.AWAD_wordlink:hover
{text-decoration: underline;}
.AWAD_byline
{font-style: italic;}  
EOF;  

$S->h_script =<<<EOF
<script type="module" src="/dist/bundle.js"></script>
<script type="module">
  import { dobanner } from '/dist/bundle.js';
  //dobanner("images/*.png", "Bonnie & Me", {recursive: 'no', size: '100', mode: ""});
  dobanner("images/*.webp", "Bonnie & Me", {recursive: 'no', size: '100', mode: ""});
</script>
EOF;

$S->banner = "<h1>Bartonphillips.org on HP-envy<br><span>Using {$S->__toString()} and PDO</span></h1>";
$S->desc = "{$S->__toString()} with dbPdo";

[$top, $bottom] = $S->getPageTopBottom();

// Get the engine and driver being used with the dbPdo class.

//$driver = $_site->dbinfo->engine;
//$host = $_site->dbinfo->host;
//$serverhost = $_SERVER['HTTP_HOST'];

echo <<<EOF
$top
<hr>
<section id="show"></section>
<section id="show-message">Please visit our main <a href="https://www.bartonphillips.com">Home Page</a>.<br>
<a target="_blank" href="showlogagent.php">Display the logagent table for today for {$S->__toString()}</a>
</section>
<hr>
$bottom
EOF;

