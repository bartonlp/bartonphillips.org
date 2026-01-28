<?php

function checktext(string $txt):string {
  if(($r = preg_match("~<.*>~m", $txt)) === 0) {
    // BLP 2024-11-11 - replace apostrophies

    $x = str_replace("'", "&apos;", $txt);

    // BLP 2024-11-11 - remove \r before the end and \n after the end.

    $contents = preg_replace("~^(.*?)$~m", "$1<br>", $x);
  } elseif($r === 1) {
    $ar = explode("\n", $txt);
    foreach($ar as $a) {
      if(preg_match("~<.*>~", $a) === 0) {
        $contents .= "$a<br>";
      } elseif(preg_match("~<br>$~", $a) === 0) {
        $contents .= "$a<br>";
      } else {
        $contents .= $a;
      }
    }

    $contents = preg_replace('~"~m', "&quot;", $contents);
  }

  $y = file_put_contents("./data/lasttext.data", $txt); // Save original text
  if($y === false) {
    echo "Error writing to file<br>";
    $err = error_get_last();
    echo $err['message'];
    exit();
  }
  return $contents;
}

$str =<<< EOF
This is a "test" of this stuff
This is <b>more</b> test.
And this is another line
EOF;
$txt = checktext($str);
echo $txt;
