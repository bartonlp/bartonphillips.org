<?php
// banner for https://bartonphillips.org
// BLP 2024-01-31 - We are using SimpleSiteClass so we must check for SimpleDatabase!

if(!class_exists("SimpleDatabase")) {
  header("location: https://bartonlp.com/otherpages/NotAuthorized.php");
}

return <<<EOF
<!-- "banner" for https://bartonphillips.org -->
<header>
  <a href="$h->logoAnchor">
    $image1</a>
  $image2
$mainTitle
<noscript>
<p style='color: red; background-color: #FFE4E1; padding: 10px'>
$image3
Your browser either does not support <b>JavaScripts</b> or you have JavaScripts disabled, in either case your browsing
experience will be significantly impaired. If your browser supports JavaScripts but you have it disabled consider enabaling
JavaScripts conditionally if your browser supports that. Sorry for the inconvienence.</p>
</noscript>
</header>
<!-- End "banner" -->
EOF;
