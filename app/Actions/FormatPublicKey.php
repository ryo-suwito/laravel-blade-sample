<?php

namespace App\Actions;

class FormatPublicKey
{
    public function format($key)
    {
        $key = str_replace(["\n", "\r\n"], ["\n", "\n"], $key);
        $key = explode("\n", $key);

        $head = array_shift($key);

        $footer = array_pop($key);
        $footer = preg_replace("/[[:^print:]]/", " ", $footer);
        $footer = preg_replace("/\s+/", " ", $footer);

        $key = implode("\n", [$head, ...$key, $footer]);

        return $key;
    }
}