<?php
// With a second call

$_site = require_once(getenv("SITELOADNAME"));

$S = new SiteClass($_site);

$h->banner = "<h1>Test</h1>";
$h->css = "<style>iframe { width: 800px; height: 600px; }</style>";

[$top, $footer] = $S->getPageTopBottom($h);

$host = "{imap.gmail.com:993/imap/ssl}";
$user = "bartonphillips@gmail.com";
$password = "709Blp8653#$";
$mbox = @imap_open($host, $user, $password);

echo $top;

// Delete Message

if($mid = $_GET['delete']) {
  imap_delete($mbox, "$mid");
}

// Second part: Show the Email Message

if($mid = $_GET['mid']) {
  $header = getmsg($mbox, $mid); // Uses globals for $charset, $htmlmsg, $plainmsg, $attachments[]  

  if($attachments) {
    //echo "Attachments<br>";

    $data = $type = $subtype = $filename = [];

    foreach($attachments as $k=>$v) {
      echo "subtype: $k<br>";
      foreach($v as $key=>$value) {
        if($key != 'data') {
          //echo "key: $key=$value<br>";
        }
        $$key[$k] = $value;
      }

      switch($k) {
        case "png":
          $img = imagecreatefromstring($data[$k]);
          imagepng($img, $filename);
          imagedestroy($img);
          $items .= "<br><img src='https://bartonphillips.org/EmailTest/{$filename[$k]}' alt='image {$filename[$k]}'><br>";
          break;
        case "html":
        case "pdf":
        case "rtf":
        case "zip":
          file_put_contents($filename[$k], $data[$k]);
          $items .= "<br>View $k: <a target='_blank' href='https://bartonphillips.org/EmailTest/{$filename[$k]}' alt='pdf {$filename[$k]}'>$filename[$k]</a><br>";
          break;
        case '':
          break;
        default:
          echo "Unknown subtype: $k<br>";
          break;
      }

      if($items) {
        chmod($filename[$k], 0665);
      }
    }
  }

  if($htmlmsg) {
    //echo "htmlmsg:<br>";
    $htmlmsg .= ($items ? $items : '');
    $html = base64_encode($htmlmsg);
    echo <<<EOF
<iframe src="data:text/html;base64,$html"></iframe><br>
EOF;
  } else {
    if($plainmsg) {
      //echo "plainmsg:<br>";
      foreach($filename as $v) {
        $plainmsg .= ($items ? "\nLINK to file at https://bartonphillips.org/EmailTest/$v\n" : '');
      }
      $plainmsg = escapeltgt($plainmsg);
      echo "<pre>$plainmsg</pre><br>";
    }
  }
  exit();
}

// Look for messages

$check = imap_mailboxmsginfo($mbox);
vardump("check", $check);
echo "<hr>";

$del = imap_search($mbox, "DELETED");

if($del === false) echo "del is false<br>";
else vardump("del", $del);

if($check->Nmsgs) {
  for($i=1; $i < $check->Nmsgs+1; ++$i) {
    $style = null;
    $msg = "Delete Message $i";
    if($del !== false && ($x = array_intersect([$i], $del)[0])) {
      $style = "style='background: pink'";
      $msg = "Message Deleted";
    }
    
    $attachements = [];
    $charset = $htmlmsg = $plainmsg = '';
    $items = null;

    $header = getmsg($mbox, $i); // Uses globals for $charset, $htmlmsg, $plainmsg, $attachments[]

    $fromaddress = quoted_printable_decode(escapeltgt($header->fromaddress));
    $subject = quoted_printable_decode(imap_utf8($header->subject ?? $header->Subject));
    
    echo <<<EOF
<style>
td:nth-of-type(2) { padding-left: 20px }
</style>
<div>
<table>
<tr $style><td>Message#: <a target="_blank" href="parse3.php?mid=$i">$i</a></td><td><a href="parse3.php?delete=$i">$msg</a></td></tr>
<tr><td>charset:</td><td>$charset</td></tr>
<tr><td>From:</td><td>$fromaddress</td></tr>
<tr><td>Subject:</td><td>$subject</td></tr>
</table>
<hr>
EOF;
  }
}

// FUNCTIONS

// getmsg() this is from the PHP comments for imap_fetchstructure by "david at hundsness dot com"
// It does seem to work well. I added $header as a global.

function getmsg($mbox, $mid) {
  // input $mbox = IMAP stream, $mid = message id
  // output all the following:

  global $charset, $htmlmsg, $plainmsg, $attachments;
  
  $charset = $htmlmsg = $plainmsg = '';
  $attachments = [];

  // HEADER

  $header = imap_headerinfo($mbox, $mid);

  // BODY

  $s = imap_fetchstructure($mbox, $mid);

  if(!$s->parts) {  // simple
    getpart($mbox, $mid, $s, 0);  // pass 0 as part-number
  } else {  // multipart: cycle through each part
    foreach($s->parts as $partno0=>$p) {
      getpart($mbox, $mid, $p, $partno0+1); // numbers start at 1 not zero
    }
  }
  
  return $header;
}

// Also from "david at hundsness dot com"
// The following globals are used:
// $charset, $htmlmsg, $plainmsg, $attachments[]
// $partno = '1', '2', '2.1', '2.1.3', etc for multipart, 0 if simple
  
function getpart($mbox, $mid, $p, $partno) {
  global $charset, $htmlmsg, $plainmsg, $attachments;

  // DECODE DATA
  $subtype = strtolower($p->subtype);
  
  $data = ($partno) ? imap_fetchbody($mbox, $mid, $partno) :  // multipart
          imap_body($mbox, $mid);  // simple

  // Any part may be encoded, even plain text messages, so check everything.
  
  if($p->encoding == ENCQUOTEDPRINTABLE) { // this should be 4
    $data = quoted_printable_decode($data);
  } elseif($p->encoding == ENCBASE64) { // this should be 3
    $data = base64_decode($data);
  }

  // PARAMETERS
  // get all parameters, like charset, filenames of attachments, etc.

  $params = [];

  if($p->parameters) {
    foreach ($p->parameters as $x) {
      $params[strtolower($x->attribute)] = $x->value;
    }
    if($p->dparameters) {
      foreach($p->dparameters as $x) {
        $params[strtolower($x->attribute)] = $x->value;
      }
    }
  }

  // ATTACHMENT
  // Any part with a filename is an attachment,
  // so an attached text file (type 0) is not mistaken as the message.

  if($params['filename'] || $params['name']) {
    // filename may be given as 'Filename' or 'Name' or both
    $filename = ($params['filename']) ? $params['filename'] : $params['name'];
    // filename may be encoded, so see imap_mime_header_decode()
    $attachments[$subtype]['filename'] = $filename;
    $attachments[$subtype]['data'] = $data;  // this is a problem if two files have same name
    $attachments[$subtype]['type'] = $p->type;
    $attachments[$subtype]['subtype'] = $subtype;
    $attachments[$subtype]['part'] = $partno;
    $data = null; // If we have a file name we will put a link on the page so don't add this data to plain or html
  }
    
  // TEXT

  if($p->type == TYPETEXT && $data) { // type should be 0
    // Messages may be split in different parts because of inline attachments,
    // so append parts together with blank row.

    if($subtype == 'plain') {
      $plainmsg .= trim($data);
    } else { // else this should be 'html'
      $htmlmsg .= $data;
    }
    $charset = $params['charset'];  // assume all parts are same charset
  } elseif($p->type == TYPEMESSAGE && $data) { // type should be 2
    // EMBEDDED MESSAGE
    // Many bounce notifications embed the original message as type 2,
    // but AOL uses type 1 (multipart), which is not handled here.
    // There are no PHP functions to parse embedded messages,
    // so this just appends the raw source to the main message.
    
    $plainmsg .= $data;
  }

  // SUBPART RECURSION

  if($p->parts) {
    foreach($p->parts as $partno0=>$p2) {
      getpart($mbox, $mid, $p2, ($partno . '.' . ($partno0+1)));  // 1.2, 1.2.1, etc.
    }
  }
}

// how to get get umlauts
// $text = trim(utf8_encode( quoted_printable_decode(
//               imap_fetchbody( $this->inbox, $emailNo, $section))));
  