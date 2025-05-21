<?php

namespace Tools\Utils;

class NamedLog {

    static public function clear(string $logName, ...$text): void
    {
        file_put_contents(
            $_SERVER['DOCUMENT_ROOT'] . '/log/'.$logName.'.txt',
            '',
            LOCK_EX
        );
    }

    static public function write(string $logName, ...$text): void
    {
        file_put_contents(
            $_SERVER['DOCUMENT_ROOT'] . '/log/'.$logName.'.txt',
            '***** '.date('Y-m-d H:i:s', time()) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
        foreach ($text as $param) {
            if (is_array($param)) {
                $data = print_r($param, true);
            }
            else if (is_object($param)) {
                $data = print_r((array) $param, true);
            }
            else {
                $data = $param;
            }
            file_put_contents(
                $_SERVER['DOCUMENT_ROOT'] . '/log/'.$logName.'.txt',
                $data . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );
        }
    }

}
