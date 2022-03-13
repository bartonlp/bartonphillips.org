<?php
// Resize image
// GET: 'image' is the name of the image.
// 'width' the new width
// 'height' the new height
// 'percent' the percent of change
// if width and height not present use percent.

$errorhdr = <<<EOF
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta name="robots" content="noindex">
</head>
EOF;
  
// File and new size
$filename = $_GET['image'];

if(empty($filename)) {
  echo <<<EOF
$errorhdr
<body>
<p>Did you forget something? An image?</p>
</body>
</html>
EOF;
  exit();
}

$imgwidth = $_GET['width'];
$imgheight = $_GET['height'];
$imgpercent = $_GET['percent'];

if(!$imgwidth && !$imgheight && !$imgpercent) {
  echo <<<EOF
$errorhdr
<body>
<p>Did you forget something? An a width or height or percent?</p>
</body>
</html>
EOF;
  exit();
}

$newwidth = $imgwidth;
$newheight = $imgheight;

list($width, $height) = getimagesize($filename);

if(!empty($imgpercent)) {
  $imgpercent /= 100;
  // Get new sizes
  $newwidth = $width * $imgpercent;
  $newheight = $height * $imgpercent;
} else {
  if(!empty($imgwidth) && empty($imgheight)) {
    $newwidth = $imgwidth;
    $newheight = $height * $imgwidth/$width;
  } elseif(!empty($imgheight) && empty($imgwidth)) {
    $newheight = $imgheight;
    $newwidth = $width * $imgheight/$height;
  }
}
// Load
$thumb = imagecreatetruecolor($newwidth, $newheight);
$ext = pathinfo($filename)['extension'];
switch($ext) {
  case 'png':
    $source = imagecreatefrompng($filename);
    $mime = 'image/png';
    $func = 'png';
    break;
  case 'jpg':
    $source = imagecreatefromjpeg($filename);
    $mime = 'image/jpg';
    $func = 'jpeg';
    break;
  case 'gif':
    $source = imagecreatefromgif($filename);
    $mime = 'image/gif';
    $func = 'gif';
    break;
  default:
    throw(new Exception("Not an image file"));
}

// Resize
imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

header("Content-type: $mime");

// Output
$func = "image$func";
$func($thumb);
