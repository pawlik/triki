<?php
namespace Triki;

class Partial {
    public static function load()
    {
        require_once __DIR__ . "/Partial/functions.php";
    }

    public static function _()
    {
        return new Partial\Placeholder();
    }
} 