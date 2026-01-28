<?php
// BLP 2024-01-31 - We are using SimpleSiteClass so we must check for SimpleDatabase!

if(!class_exists("SimpleDatabase")) {
  header("location: https://bartonlp.com/otherpages/NotAuthorized.php");
}

return <<<EOF
<!-- "head" for https://bartonphillips.org -->
<head>
$h->title
$h->base
$h->viewport
$h->charset
$h->copyright
$h->author
$h->desc
$h->keywords
$h->meta
$h->canonical
$h->favicon
$h->defaultCss
$h->link
$jQuery
$trackerStr
$h->extra
$h->script
$h->inlineScript
$h->css
</head>
<!-- End "head" -->
EOF;
