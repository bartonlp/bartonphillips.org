<?php
$_site = require_once(getenv("SITELOADNAME"));

if (!function_exists('glob_recursive')) {
  function glob_recursive($pattern, $flags = 0) {
    $files = glob($pattern, $flags);
        
    foreach(glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT|GLOB_MARK) as $dir) {
      $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
    }
        
    return $files;
  }
}


// AJAX GET

if($_GET['path']) {
  $path = $_GET['path'];

  if(!$path) {
    $x = glob_recursive("*.JPG"); // we are looking for JPG
    array_push($x, glob_recursive("*.jpg")); // and also jpg
  } else {
    if($_GET['recursive'] == 'yes') {
      $x = glob_recursive($path);
    } else {
      $x = glob($path);
    }
  }
  if($_GET['mode'] == 'rand') {
    shuffle($x);
  }

  if($_GET['size']) {
    $x = array_slice($x, 0, $_GET['size']); // get from zero to size only.
  }

  // Turn the array into a string of lines with a \n

  foreach($x as $v) {
    $banner_photos .= "http://bartonphillips.org/$v\n";
  }

  $banner_photos = rtrim($banner_photos, "\n");

  // Send this back to the Ajax function

  echo $banner_photos;
  exit();
}

$S = new SiteClass($_site);

$h->css = <<<EOF
<style>
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
</style>
EOF;  

$b->msg = "<br><a href='webstats.php'>Webstats</a><br>";
$b->script = <<<EOF
  <script src='yimage.js'></script>
  <script>dobanner("Photos/*.png", {recursive: 'no', size: '100', mode: "rand"});</script>
EOF;

list($top, $footer) = $S->getPageTopBottom($h, $b);

echo <<<EOF
$top
<hr>
<p>Please visit our main <a href="https://www.bartonphillips.com">Home Page</a>.</p>
<p>How to get 'https' to work. Check out the <a href="README.md">README.md</a> or the text howto <a href="HTTPS-howto.txt">HTTPS-howto</a></p>
<div id="show"></div>
<hr>
$footer
EOF;

