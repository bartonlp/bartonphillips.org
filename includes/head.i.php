<?php
// BLP 2022-01-28 -- add $h->base for <base ...>
// BLP 2022-01-24 -- mod to SiteClass, added $trackerStr logic to getPageHead();
// BLP 2021-03-26 -- Added logic to not do tracker stuff if nodb or noTrack set.
// NOTE not via $h.

return <<<EOF
<head>
  <title>{$h->title}</title>
{$h->base}
  <!-- METAs -->
  <meta name=viewport content="width=device-width, initial-scale=1">
  <meta charset='utf-8'>
  <meta name="copyright" content="{$this->copyright}">
  <meta name="Author" content="{$this->author}">
  <meta name="description" content="{$h->desc}">
  <meta name="keywords" content="{$h->keywords}">
  <!-- More meta data -->
{$h->meta}
  <!-- ICONS, RSS -->
  <link rel="shortcut icon" href="{$h->favicon}">
  <!-- default CSS -->
  <link rel="stylesheet" href="{$h->defaultCss}" title="default">
{$h->link}
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/jquery-migrate-3.3.2.min.js"></script>
  <script>jQuery.migrateMute = false; jQuery.migrateTrace = false;</script>
$trackerStr
{$h->extra}
{$h->script}
{$h->css}
</head>
EOF;
