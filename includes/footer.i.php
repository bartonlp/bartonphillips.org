<?php
   // Footer file
$statcounter = <<<EOF
<!-- Start of StatCounter Code for Default Guide -->
<script type="text/javascript">
var sc_project=10131375; 
var sc_invisible=1; 
var sc_security="5d14a98f"; 
var scJsHost = (("https:" == document.location.protocol) ?
"https://secure." : "http://www.");
document.write("<sc"+"ript type='text/javascript' src='" +
scJsHost+
"statcounter.com/counter/counter.js'></"+"script>");
</script>
<noscript><div class="statcounter"><a title="free hit
counters" href="http://statcounter.com/free-hit-counter/"
target="_blank"><img class="statcounter"
src="http://c.statcounter.com/10131375/0/5d14a98f/1/"
alt="free hit counters"></a></div></noscript>
<!-- End of StatCounter Code for Default Guide -->
EOF;

if(isset($arg['statcounter'])) {
  if(is_string($arg['statcounter'])) {
    $statcounter = $arg['statcounter'];
  } elseif($arg['statcounter'] === false) {
    $statcounter = '';
  }
}
$statcounter = '';

$lastmod = date("M j, Y H:i", getlastmod());

return <<<EOF
<footer>
<h2><a target="_blank" href='aboutwebsite.php'>About This Site</a></h2>
<div id="address">
<address>
  Copyright &copy; $this->copyright<br>
$this->address<br>
<a href='mailto:bartonphillips@gmail.com'>bartonphillips@gmail.com</a>
</address>
</div>
{$arg['msg']}
{$arg['msg1']}
<br>
$counterWigget
Last Modified: $lastmod
{$arg['msg2']}
</footer>
$statcounter
</body>
{$arg['script']}
</html>
EOF;
