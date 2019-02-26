<?php

namespace App\Inspections;

use \Exception;

class KeyHeldDown
{
    public function detect($text)
    {
        preg_match('/(.)\\1{4,}/', $text);

        if (preg_match('/(.)\\1{4,}/', $text)) {
            throw new Exception('Your reply contains spam.');
        }
    }
}
