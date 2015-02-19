<?php
namespace UsefulTests\Tests;

use \Useful;



class FirstTest extends \PHPUnit_Framework_TestCase {

    public function test_N()
    {
        Useful\Useful::load();
        $result = Useful\N(3)->times(function(){return 1;});
        $this->assertEquals([1, 1, 1], $result);
    }

}
 