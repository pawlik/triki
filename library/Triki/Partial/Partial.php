<?php
namespace Triki\Partial;


class Partial {

    public function makePartial($fun)
    {
        $bindedArgs = func_get_args();
        array_shift($bindedArgs);
        return function()use($fun, $bindedArgs) {
            $_args = func_get_args();

            foreach($bindedArgs as &$argument)
            {
                if($argument instanceof \Triki\Partial\Placeholder) {
                    $argument =  array_shift($_args);
                }
            }

            $_args = array_merge($bindedArgs, $_args);
            return call_user_func_array($fun, $_args);
        };
    }
} 