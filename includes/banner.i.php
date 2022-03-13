<?php
// BLP 2021-06-06 -- see bartonphillips.com/includes/banner.i.php for information on trackerImg*
// BLP 2021-03-26 -- add nodb logic

return <<<EOF
<header>
  <a href="http://www.bartonphillips.com">
$image1
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
EOF;
