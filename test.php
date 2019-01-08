<?php
require "vendor/autoload.php";
include('vendor/ressio/pharse/pharse.php');

$from = time();
//$a = file_get_contents("http://global.espn.com/football/fixtures/_/date/20180814");
//$dom->load($a);
//var_dump ($dom->find('#sched-container .schedule'));
//die;

$html = Pharse::file_get_dom('http://global.espn.com/football/fixtures/_/date/20180814');
//$trs  = $html("#sched-container table.schedule")[0]("tbody > tr");
//foreach ($trs as $tr) {
//    echo $tr->toString() . "\n============================\n\n\n";
//}
echo time() - $from;

//$f = fopen("a.txt", "w");
//fwrite($f, $a);
//fclose($f);PHPHtmlParser\Dom