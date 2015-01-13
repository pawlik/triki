<?php
namespace UsefulTests\Tests;

use \Useful;


class Int{
    private $i;
    public function __construct($i)
    {

    }
}

class FirstTest extends \PHPUnit_Framework_TestCase {

    public function test_N_times_should_return_iterator()
    {
//        Useful\Useful::load();
//        $this->assertInstanceOf('Useful\Iterator', \Useful\N(3));
    }
//
//    public function test_fail()
//    {
//        $result = N::times(3)->run(function(){return 1;});
//        $this->assertEquals([1, 1, 1], $result);
//    }


    public function test_N()
    {
        Useful\Useful::load();
        $result = Useful\N(3)->times(function(){return 1;});
        $this->assertEquals([1, 1, 1], $result);
    }

}
 