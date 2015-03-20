<?php
namespace DelegatorTest;


interface Workable {
    public function work($with);
}


class Delegator {
    private $definitions = [];

    public function delegateSimpleChain($chain)
    {
        $lastInChain = array_pop($chain);
        $this->definitions[$lastInChain] = array_merge($chain);
    }

    public function call($methodName, $arguments, $caller)
    {
        if(array_key_exists($methodName, $this->definitions)) {
            $result = $caller;
            foreach($this->definitions[$methodName] as $nextInChain) {
                $result = call_user_func([$result, $nextInChain]);
            }
            return call_user_func_array([$result, $methodName], $arguments);
        }
        throw new \BadMethodCallException(
            "$methodName is not defined in " . __CLASS__ . " definitions"
        );
    }
}

class BaseWithDelegatorTool {

    private $tool;
    private $delegator;
    public function __construct(Workable $tool)
    {
        $this->tool = $tool;
        $this->delegator = new Delegator();

        /**
         * below (togheter with __call method below) replaces
         * ```
         * function work($arg) {
         *   return $this->getTool()->getThis()->getThat()->work($arg);
         * }
         */
        $this->delegator->delegateSimpleChain(
            ['getTool', 'getThis', 'getThat', 'work']
        );

    }

    public function __call($methodName, $args)
    {
        return $this->delegator->call($methodName, $args, $this);
    }

    public function getTool()
    {
        return $this->tool;
    }
}



class DelegatorTest extends \PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        \Mockery::close();
    }

    public function test_new()
    {
        $tool = \Mockery::mock('\DelegatorTest\Workable');
        $tool->shouldReceive('getThis->getThat->work')
            ->with('foo')
            ->andReturn('done!');
        $base = new BaseWithDelegatorTool($tool);

        $this->assertEquals('done!', $base->work('foo'));

    }
}