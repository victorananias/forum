<?php

namespace App;

class Spam
{
    public function detect($text)
    {
        $this->detectInvalidKeywords($text);

        return false;
    }

    public function detectInvalidKeywords($text)
    {
        $invalidKeywords = [
            'yahoo costumer support'
        ];

        foreach ($invalidKeywords as $keyword) {
            if (stripos($text, $keyword) !== false) {
                throw new \Exception('Your reply contains spam.');
            }
        }
    }
}
