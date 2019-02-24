<?php

namespace App\Inspections;

use \Exception;

class InvalidKeywords
{
    protected $keywords = [
        'yahoo costumer support'
    ];

    public function detect($text)
    {
        foreach ($this->keywords as $keyword) {
            if (stripos($text, $keyword) !== false) {
                throw new Exception('Your reply contains spam.');
            }
        }
    }
}
