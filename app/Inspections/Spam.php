<?php

namespace App\Inspections;

class Spam
{
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    public function detect($text)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($text);
        }

        return false;
    }
}
