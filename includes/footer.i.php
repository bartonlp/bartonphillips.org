<?php
// Footer file
// BLP 2021-12-18 -- add geo.js after </footer>
// BLP 2018-02-24 -- added 'script' just before </body>

return <<<EOF
<footer>
<h2><a target="_blank" href='aboutwebsite.php'>About This Site</a></h2>
<div id="address">
<address>
  Copyright &copy; $this->copyright<br>
$b->address<br>
<a href='mailto:$this->EMAILADDRESS'>$this->EMAILADDRESS</a>
</address>
</div>
{$b->msg}
{$b->msg1}
<br>
$counterWigget
Last Modified: $lastmod
{$b->msg2}
</footer>
<script src="https://bartonphillips.net/js/geo.js"></script>
{$b->script}
</body>
</html>
EOF;
