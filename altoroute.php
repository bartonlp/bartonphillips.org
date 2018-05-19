<?php
// BLP 2018-04-19 -- add $__info to set $_site up so we can add $_site->requestUri. 
// For this to run under 'apache' 
// we would need to edit our '.htaccess' file and add:
//   RewriteEngine on
//   RewriteCond %{REQUEST_FILENAME} !-f
//   RewriteCond %{REQUEST_FILENAME] !-d
//   RewriteRule . altoroute.php [L]
// Which means everything will go throush 'altorouts.php' that is not a file or directory.
// 'composer require altorouter/altorouter'
// https://github.com/dannyvankooten/AltoRouter
// 'composer require pug-php/pug:^3.0'
// https://github.com/pug-php/pug

// This file DOES NOT DO a require_onece(getenv("SITELOADNAME") or set $S.
// The $router->map() functions can do it to set up $_site.

require_once("/var/www/vendor/autoload.php");
ErrorClass::setDevelopment(true);
ErrorClass::setErrorType(E_ALL & ~(E_NOTICE | E_WARNING | E_STRICT));
$router = new AltoRouter();

// getSiteLoad()
// for pug files we get the requestUri name, instanciate the $S
// and set $info. Return $info and $S

function getSiteLoad($name) {
  $__info->requestUri = $name;
  $_site = require_once(getenv("SITELOADNAME"));
  $S = new $_site->className($_site);

  if(!$S->isBot) {
    if(preg_match("~^.*(?:(msie\s*\d*)|(trident\/*\s*\d*)).*$~i", $S->agent, $m)) {
      $which = $m[1] ? $m[1] : $m[2];
      echo <<<EOF
<!DOCTYPE html>
<html>
<head>
  <title>NO GOOD MSIE</title>
</head>
<body>
<div style="background-color: red; color: white; padding: 10px;">
Your browser's <b>User Agent String</b> says it is:<br>
$m[0]<br>
Sorry you are using Microsoft's Broken Internet Explorer ($which).</div>
<div>
<p>You should upgrade to Windows 10 and Edge if you must use MS-Windows.</p>
<p>Better yet get <a href="https://www.google.com/chrome/"><b>Google Chrome</b></a>
or <a href="https://www.mozilla.org/en-US/firefox/"><b>Mozilla Firefox</b>.</p></a>
These two browsers will work with almost all previous
versions of Windows and are very up to date.</p>
<b>Better yet remove MS-Windows from your
system and install Linux instead.
Sorry but I just can not continue to support ancient versions of browsers.</b></p>
</div>
</body>
</html>
EOF;
      exit();
    }
  }

  $info = [
           'copyright'=>$S->copyright,
           'author'=>$S->author,
           'desc'=>'',
           'LAST_ID'=>$S->LAST_ID,
          ];

  return [(object)$info, $S];
};

// Do Routing

$router->map('GET', '/', gethomepage);
$router->map('GET', '/index', gethomepage);
$router->map('GET', '/myhomepage', gethomepage);

function gethomepage() {
  list($info, $S) = getSiteLoad('/myhomepage');
  date_default_timezone_set("America/New_York");
  $info->date = date("l F j, Y H:i:s T");
  $lastmod = date("Y-m-d H:i T", filemtime('pug/index.pug'));
  $info->mainTitle = "h1 $S->mainTitle";
  
  if(!($hereId = $_COOKIE['SiteId'])) {
    $S->query("select count, date(created) from $S->masterdb.logagent ".
              "where ip='$S->ip' and agent='$S->agent' and site='$S->siteName'");

    list($hereCount, $created) = $S->fetchrow('num');

    if($hereCount > 1) {
      $info->hereMsg =<<<EOF
<div class="hereMsg">You have been to our site $hereCount since $created<br>
Why not <a target="_blank" href="register.php">register</a>
</div>
EOF;
    }
  } else {
    $sql = "select name from members where id=$hereId";
    if($n = $S->query($sql)) {
      list($memberName) = $S->fetchrow('num');
      $info->hereMsg =<<<EOF
<div class="hereMsg">Welcome $memberName</div>
EOF;
    } else {
      error_log("$S->siteName: members id ($hereId) not found at line ".__LINE__);
    }
  }

  if($S->isBot) {
    $info->locstr = '';
  } else {
    $ref = $_SERVER['HTTP_REFERER'];

    if($ref) {
      if(preg_match("~(.*?)\?~", $ref, $m)) $ref = $m[1];
      $ref =<<<EOF
<li>You came to this site from: <i class='green'>$ref</i></li>
EOF;
    }
  
    // Use ipinfo.io to get the country for the ip
    $cmd = "http://ipinfo.io/$S->ip";
    $ch = curl_init($cmd);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $loc = json_decode(curl_exec($ch));
  
    $info->locstr = <<<EOF
<ul class="user-info">
  $ref
  <li>User Agent String is:<br>
    <i class='green'>$S->agent</i></li>
  <li>IP Address: <i class='green'>$S->ip</i></li>
  <li>Hostname: <i class='green'>$loc->hostname</i></li>
  <li>Location: <i class='green'>$loc->city, $loc->region $loc->postal</i></li>
  <li>GPS Loc: <i class='green'>$loc->loc</i></li>
  <li>ISP: <i class='green'>$loc->org</i></li>
</ul>
EOF;
  }
  
  $pug = new Pug();
  $pug->displayFile('pug/index.pug', ['info'=>$info, 'lastmod'=>$lastmod]);
};

$router->map('GET', '/aboutwebsite', function() {
  $__info->requestUri = '/aboutwebsite';
  require(__DIR__ . '/aboutwebsite.php');
});

$router->map('GET', '/rotary/list', function() {
  list($info, $S) = getSiteLoad('/rotary/list');
  $info->desc = "Rotary List";
  $S->query("select concat(fname, ' ', lname) from granbyrotary.rotarymembers ".
            "where status='active' order by lname");
  
  $list = [];
  while(list($name) = $S->fetchrow('num')) {
    $list[] = "$name";
  }
  date_default_timezone_set('America/New_York');
  $lastmod = date("Y-m-d H:i T", filemtime('pug/rotary-info.pug'));
  $pug = new Pug();
  $pug->displayFile('pug/rotary-list.pug', ['list'=>$list, 'lastmod'=>$lastmod, 'info'=>$info]);
});

$router->map('GET', '/rotary/[a:fname]%20[a:lname]', function($fname, $lname) {
  list($info, $S) = getSiteLoad("/rotary/$fname $lname");
  $info->desc = "Rotary Info for $fname $lname";
  $S->query("select * from granbyrotary.rotarymembers ".
            "where fname='$fname' and lname='$lname'");
  
  $row = $S->fetchrow('assoc');
  date_default_timezone_set('America/New_York');
  $lastmod = date("Y-m-d H:i T", filemtime('pug/rotary-info.pug'));
  $pug = new Pug();
  $pug->displayFile('pug/rotary-info.pug', ['lastmod'=>$lastmod, 'row'=>$row, 'info'=>$info]);
});

// map users details page. We could have used a closeure but for example purpuses I have a real
// function here and for webstats

$router->map('GET', '/user/[i:id]', 'getUser');

$router->map('GET', '/webstats', 'getWebstats');

// Do the match

$match = $router->match();

// call closure or display 404 status

if($match && is_callable($match['target'])) {
  // This calls the closer with the params.
	call_user_func_array($match['target'], $match['params']); 
} else {
  // no route was matched
  $path = $_SERVER['REQUEST_URI'];
	header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
  echo <<<EOF
<!DOCTYPE html>
<html>
<head>
<title>404 Not Found</title>
</head>
<body>
<h1>404 Not Found</h1>
<p>Sorry this path ($path) was not found on the server.</p>
</body>
</html>
EOF;
}

function getUser($id) {
  // test.php does NOT do require siteload.php!
  
  list($info, $S) = getSiteLoad("/user/$id");
  $info->desc = "User $id";
  require(__DIR__ . "/../bartonphillips.com/examples/test.php");
};

function getWebstats() {
  list($info, $S) = getSiteLoad('/webstats');
  $info->desc = "Web Stats for BartonphillipsOrg";

  $S->query("select * from barton.tracker ".
            "where site='BartonphillipsOrg' and lasttime > current_date() order by lasttime desc");

  $list = [];
  
  while($row = $S->fetchrow('assoc')) {
    $list[] = $row;
  }

  date_default_timezone_set('America/New_York');
  $lastmod = date("Y-m-d H:i T", filemtime('pug/rotary-info.pug'));
  $pug = new Pug();
  $pug->displayFile('pug/webstats.pug', ['list'=>$list, 'lastmod'=>$lastmod, 'info'=>$info]);
};
