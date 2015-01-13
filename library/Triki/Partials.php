<?php
namespace Triki;

use Triki\Partial\Placeholder;

class Partials {
    public static function load()
    {
        require_once __DIR__ . "/Partial/functions.php";
    }

    public static function _()
    {
        return new Placeholder();
    }
}