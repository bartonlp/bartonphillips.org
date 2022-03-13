<?php

$percent = 0.2;
chdir("/home/barton/Downloads");  
foreach(glob("*.jpg") as $filename) {
  echo "$filename<br>";

  // Get new sizes
  list($width, $height) = getimagesize($filename);

  $newwidth = 600;
  $newheight = $height * 600 / $width;

  // Load
  $thumb = imagecreatetruecolor($newwidth, $newheight);

  $source = imagecreatefromjpeg($filename);
   
  // Resize
  imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

  // Output
  imagejpeg($thumb, "/var/www/html/temp/$filename");
  imagedestroy($thumb);
}
echo "DONE<br>";