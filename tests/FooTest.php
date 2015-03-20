<?php
namespace UsefulTests\Tests;

trait Timer {
    private $timerTimer;
    public function setTimer(callable $timer){
        $this->timerTimer = $timer;
    }

    /**
     * @param bool $get_as_float
     * @link http://php.net/manual/en/function.microtime.php
     * @return mixed
     */
    public function microtime($get_as_float = false)
    {
        if(null === $this->timerTimer) {
            $this->setTimer(function($get_as_float){
                    return microtime($get_as_float);
            });
        }
        return call_user_func_array($this->timerTimer, [$get_as_float]);
    }

    /**
     * @link http://php.net/time
     * @return int
     */
    public function time()
    {
        return (int)$this->microtime(true);
    }
}


class NeedAccessToTime {

    // Version A: allow injecting custom microtime callback, use standard by defaul
    use Timer;

    public function getEstimatedTimeLeft()
    {
        return $this->microtime();
    }

    // version B: "namespace mock"
    public function usesMicrotimeFunction()
    {
        /**
         * in this version you have to require devs not to
         * explicitly call
         * \microtime();
         */
        return microtime(true);
    }
}


// mocked version only for this namespace, but 1 value per whole test
// can't simulate time passing here
function microtime($get_as_float = false) {
    return 1424434114.4196;
}

// to simulate passing time you need something like this
//
abstract class CurrentTimeHolder {
    public static $timestamp;
}
/**
function microtime($get_as_float = false) {
    return CurrentTimeHolder::$timestamp;
}
 **/


class StaticContainer {
    private static $container;

    public static function get()
    {
        return self::$container;
    }

    public static function set($value)
    {
        self::$container = $value;
    }
}


class FooTest extends \PHPUnit_Framework_TestCase {

    public function multiAssert($what, $stuff, $message = "Some of assertions failed")
    {
        $method = "assert$what";
        $issues = [];
        foreach($stuff as $args) {
            try {
                call_user_func_array([$this, $method], $args);
            }catch (\PHPUnit_Framework_AssertionFailedError $e) {
                $issues[] = $e->toString();
            }
        }
        if(!empty($issues)) {
            $this->fail(implode("\n", $issues));
        }
    }

    public function test_time_sensitive_feature()
    {

        $this->multiAssert('Equals',
                           [
                               [2, 2, 'Platform Id should be 2'],
                               [1, 3, 'Order type should be 2'],
                               [5, 7, 'Cancel date should be 5'],
                               [9, 9, 'Payment method should be '],
                           ]
        );

        StaticContainer::set('x');
        $this->assertEquals('x', StaticContainer::get());

        $uut = new NeedAccessToTime();
        $uut->setTimer(function(){
                return 1424434114.4196;
        });

        $this->assertEquals(1424434114.4196, $uut->getEstimatedTimeLeft());
    }
    public function test_mock_with_namespacing()
    {
        $uut = new NeedAccessToTime();
        $this->assertEquals(1424434114.4196, $uut->usesMicrotimeFunction());


        $data = [
            'x' => 'X',
            'y' => 'Y'
        ];

        $this->assertArraySubset(['x' => "X"], $data);


//        $this->assertArraySubset()
    }

}


/**
 * version B:
 * -- con's:
 *      Either only one time for all tests in the file,
 *      or bigger boilerplate code for each test (not DRY)
 *      Need convention not to use \microtime() anywhere (it's nothing like
 *      dependency injection, more like function overwrite.
 * ++ pro's:
 *      No extra code outside of tests
 *
 * version A:
 * -- con's:
 *      All this code is needed only for testing, in most cases on production code
 *      it's not needed at all.
 *      Need convention to use Timer trait
 *      $this->microtime() looks weird, looks like my class has specific implementation
 *      of microtime (making it private fixes it a little - no need to extend interface)
 * -- pro's
 *      Trait is simple, not buggy, keeps signatures of native functions, easy to include
 *      in class
 *
 */