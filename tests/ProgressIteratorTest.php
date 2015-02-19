<?php
namespace UsefulTests\Tests;

use \Useful\ProgressIterator;

class ProgressIteratorTest extends \PHPUnit_Framework_TestCase {

    public function test_instance()
    {
        new ProgressIterator(new \ArrayIterator([]));
    }

    public function test_decorates_array()
    {
        $arr = [1, 2, 3];
        $i = new ProgressIterator(new \ArrayIterator($arr));
        $this->assertIterates3Times($i);
    }

    public function test_after_iterating_half_gives_50_percent_progress()
    {
        $arr = array_fill(0, 4, 0);
        $i = new ProgressIterator(new \ArrayIterator($arr));
        $i->next();
        $i->next();

        $this->assertEquals(50, $i->progress());
    }

    public function test_after_iterating_one_fourth_gives_25_percent_progress()
    {
        $arr = array_fill(0, 4, 0);
        $i = new ProgressIterator(new \ArrayIterator($arr));
        $i->next();

        $this->assertEquals(25, $i->progress());

    }


    public function assertIterates3Times($object, $message = "")
    {
        $n  = 0;
        foreach($object as $x) {
            $n++;
        }
        $this->assertEquals(3, $n, __FUNCTION__ . " failed: object consitst $n items \n" . $message);
    }

}
 