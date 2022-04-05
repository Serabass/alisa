<?php

include_once 'common.php';
include_once 'clear.php';

include_once 'history/history-base.php';

function getLog()
{
  $cont = file_get_contents('alisalog.txt');
  $lines = preg_split('/[\r\n]+/', $cont);
  $res = [];

  for ($i = 0; $i < count($lines); $i += 2) {
    $res[] = [
      'time' => $lines[$i],
      'text' => json_decode($lines[$i + 1], true),
    ];
  }

  return $res;
}

$log = getLog();

echo '<pre>';
echo '<ul>';
  foreach ($log as $row) {
    echo '<li>';
    echo '<b>' . $row['time'] . '</b>';
    echo '<pre>';
    echo $row['text']['request']['command'];
    echo '</pre>';
    echo '</li>';
  }
echo '</ul>';
echo '</pre>';
