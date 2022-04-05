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

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">


</head>
<body>
  
  <ul class="list-group">
    <?php foreach ($log as $row) { ?>
      <li class="list-group-item">
        <b> <?php echo $row['time'] ?> </b>
        <span> <?php echo $row['text']['request']['command'] ?> </span>
      </li>
    <?php } ?>
  </ul>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>
