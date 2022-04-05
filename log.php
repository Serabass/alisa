<?php

include_once 'common.php';
include_once 'clear.php';

include_once 'history/history-base.php';

function getLog()
{
  $cont = file_get_contents('alisalog.txt');
  $lines = preg_split('/[\r\n]+/', $cont);
  $res = [];

  for ($i = 0; $i < count($lines); $i++) {
    [$time, $text] = explode('|', $lines[$i]);
    $text = json_decode($text, true);
    yield compact('time', 'text');
  }
}

$log = getLog();

header('Content-Type: text/html; charset=utf-8');
echo '<pre>';
echo '<ul>';
  foreach ($log as $row) {
    echo '<li>';
    echo '<b>' . $row['time'] . '</b>';
    echo '<span> ' . $row['text']['request']['command'] . '</span>';
    echo '</li>';
  }
echo '</ul>';
echo '</pre>';
