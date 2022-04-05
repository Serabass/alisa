<?php

include_once 'common.php';
include_once 'clear.php';

include_once 'history/history-base.php';

function getLog()
{
  $cont = file_get_contents('alisalog.txt');
  $converted = mb_convert_encoding($cont, 'Windows-1251', 'UTF-8'); 
  $lines = preg_split('/[\r\n]+/', $converted);
  $res = [];

  for ($i = 0; $i < count($lines); $i++) {
    [$time, $text] = explode('|', $lines[$i]);
    $text = json_decode($text, true);
    yield compact('time', 'text');
  }
}

$log = getLog();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logs</title>
</head>
<body>
  
  <ul>
    <?php foreach ($log as $row) { ?>
      <li>
        <b> <?php echo $row['time'] ?> </b>
        <span> <?php echo $row['text']['request']['command'] ?> </span>
      </li>
    <?php } ?>
  </ul>

</body>
</html>
