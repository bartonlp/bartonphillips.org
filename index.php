<?php
// HP-envy
// NOTE, the mysitemap.json should have:
// "trackerLocationJs": "https://bartonphillips.org/site-class/includes/tracker.js",
// "trackerLocation": "https://bartonphillips.org/site-class/includes/tracker.php" and
// "beaconLocation": "https://bartonphillips.org/site-class/includes/beacon.php"
// set so we do everthing on this (HP-envy) server!

// Normal start using SIMPLE_SITELOADNAME

$_site = require_once getenv("SIMPLE_SITELOADNAME");

if(is_null($_site)) {
  echo "\$_site is null<br>";
  error_log("bartonphillips.org/index.php, SiteClass with PDO: \$_site is NULL");
  exit();
}

SimpleErrorClass::setDevelopment(true);

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
    $banner_photos .= "https://bartonphillips.org/$v\n";
  }

  $banner_photos = rtrim($banner_photos, "\n");

  // Send this back to the Ajax function

  echo $banner_photos;
  exit();
}

// BLP 2024-11-15 -  SimpleSiteClass instantiation must be after the if($_POST['data'] or we will
// get two counts in logagent.

$S = new SimpleSiteClass($_site);

$phpVersion = explode('-', PHP_VERSION)[0];
$noTrack = ($_site->noTrack === true) ? "true" : "false";

ob_start(); // Start output buffering
require "/var/www/composer.lock";
$x= ob_get_clean();

if(($n = preg_match("~\"url\": \"https://github.com/bartonlp/simple-site-class.git\",\n *\"reference\": \"(.*?)\"~",
                    $x, $m)) === false) {
  exit("ERROR");
}
$reporef = substr($m[1], 0, 7);

$siteclassVersion = "{$S->__toString()}={$S->getVersion()}<br>engine={$S->dbinfo->engine}, noTrack=$noTrack<br>".
                    "siteload=" . SITELOAD_VERSION . ", reporef=$reporef<br>";

$S->msg = "PhpVersion: $phpVersion<br>"; // This is the local phpVersion on the this server.
$S->msg1 = $siteclassVersion;

$S->css = <<<EOF
header h1 { line-height: 30px; } /* reduce line hight */
.item { text-align: center; }
/* This is like <hr> */
.item::after {
  content: '';
  width: 100%;
  height: 1px;
  margin: 10px 0 10px;
  display: block;
  background-color: black;
}
#show {
  width: 300px;
  margin: auto;
}
#show img {
  width: 300px;
}
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

$S->b_inlineScript = <<<EOF
// inline

let bannerImages = new Array, binx = 0;

function dobanner(path, name, obj) {
  // obj has three members: size, recursive, mode.

  console.log(path, name, obj);
  
  let recursive = obj.recursive;
  let size = obj.size;
  let mode = obj.mode;
  //console.log("obj: " +obj+", recursive: "+recursive);

  $.ajax({
    url: './index.php',
    type: 'post',
    data: {path: path, recursive: recursive, size: size, mode: mode},
    success: function(data) {
      bannerImages = data.split("\\n");
      $("#show").html("<h3 class='center'>" + name + "</h3><img>");
      bannershow(obj.mode); // pass mode to bannershow()
    },
    error: function(err) {
      console.log("Error: ", err);
    }
  });
}

// Called from above. It displayes the image in "#show" and then sets a
// timer and does it again and again.

function bannershow() {
  if(binx > (bannerImages.length - 1)) {
    binx = 0;
  }
    
  var image = new Image;
  image.src = bannerImages[binx++];
  $(image).on("load", function() {
    $("#show img").attr('src', image.src);
    setTimeout(function() { bannershow(); }, 5000);
  });

  $(image).on("error", function(err) {
    console.log(err);
    setTimeout(function() { bannershow(); }, 5000);
  });
}

// This start the slideshow.

dobanner("Photos/*.png", "Bonnie & Me",  {recursive: 'no', size: '100', mode: "rand"});
EOF;

$S->banner = "<h1>Bartonphillips.org on HP-envy</h1><p>Using {$S->__toString()} and PDO</p>";
$S->desc = "{$S->__toString()} with dbPdo";

[$top, $footer] = $S->getPageTopBottom();

// Get the engine and driver being used with the dbPdo class.

//$driver = $_site->dbinfo->engine;
//$host = $_site->dbinfo->host;
//$serverhost = $_SERVER['HTTP_HOST'];

echo <<<EOF
$top
<hr>
<p>Please visit our main <a href="https://www.bartonphillips.com">Home Page</a>.</p>
<a target="_blank" href="showlogagent.php">Display the logagent table for today for {$S->__toString()}</a><br>
<div id="show"></div>
<hr>
$footer
EOF;

