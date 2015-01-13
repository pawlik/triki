<?php
namespace Useful;

function N($times) {
    return new Iterator($times);
}

class Useful {
    /**
     * this one is just to trigger autoloading for function in Useful namespace
     * @see https://wiki.php.net/rfc/function_autoloading
     */
    public static function load(){}
}