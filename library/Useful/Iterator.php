<?php
namespace Useful;


final class Iterator{
    private $times;
    public function __construct($times)
    {
        $this->times = $times;
    }

    public function times(callable $do)
    {
        $result = [];
        for($i = 0; $i< $this->times; $i++) {
            $result[$i] = $do($i);
        }
        return $result;
    }

}

