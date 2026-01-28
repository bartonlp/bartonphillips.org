<?php
// banner for https://bartonphillips.org
// BLP 2024-01-31 - We are using SimpleSiteClass so we must check for SimpleDatabase!

if(!class_exists("SimpleDatabase")) {
  header("location: https://bartonlp.com/otherpages/NotAuthorized.php");
}

return <<<EOF
<!-- "footer" for https://bartonphillips.org -->
<footer>
{$b->aboutwebsite}
{$f->aboutwebsite}
<div id="address">
<address>
{$b->copyright}
{$f->copyright}
{$b->address}
{$f->address}
{$b->emailAddress}
{$f->emailAddress}
</address>
</div>
{$b->msg}
{$f->msg}
{$b->msg1}
{$f->msg1}
{$lastmod}
{$b->msg2}
{$f->msg2}
</footer>
{$geo}
{$b->script}
{$b->inlineScript}
</body>
</html>
<!-- End "footer" -->
EOF;
