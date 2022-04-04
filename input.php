<?php

class Input {
    public static function json() {
        $dataRow = file_get_contents('php://input');

        file_put_contents('alisalog.txt', date('Y-m-d H:i:s') . PHP_EOL . $dataRow . PHP_EOL, FILE_APPEND);

        return json_decode($dataRow, true);
    }
}
