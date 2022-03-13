<?php
$_site = require_once(getenv("SITELOADNAME"));
$S = new SiteClass($_site);

$h->script =<<<EOF
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-LRD1H2KKZ0"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-LRD1H2KKZ0');
</script>
EOF;

$h->banner = "<h1>Google Analytics Test</h1>";

[$top, $footer] = $S->getPageTopBottom($h);

echo <<<EOF
$top
$footer
EOF;