/*
 * webstats.js for http://www.bartonphillips.net/webstats.php. Uses
 * webstats-ajax.php for AJAX calls.
 */

'use strict';

const DEBUG = false; // BLP 2023-10-17 - if true we do the debug_performanceObserver function.

const flags = {all: false, webmaster: false, bots: false, ip6: true};
const ajaxurl = './webstats-ajax.php'; // URL for all ajax calls.

jQuery(document).ready(function($) {
  if(DEBUG) debug_performanceObserver();

  $("#logagent").tablesorter({
    theme: 'blue',
    sortList: [[0][1]]
  }); //.addClass('tablesorter');
  
  // Add two special tablesorter functions: hex and strnum
  
  $.tablesorter.addParser({
    id: 'hex',
    is: function(s) {
          return false;
    },
    format: function(s) {
          return parseInt(s, 16);
    },
    type: 'numeric'
  });

  $.tablesorter.addParser({
    id: 'strnum',
    is: function(s) {
          return false;
        },
        format: function(s) {
          s = s.replace(/,/g, "");
          return parseInt(s, 10);
        },
        type: 'numeric'
  });
});
