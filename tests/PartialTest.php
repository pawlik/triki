<?php


use function /** @noinspection PhpParamsInspection */
    \Triki\Partial\makePartial;
use function  /** @noinspection PhpParamsInspection */
    \Triki\Partial\withPartialDo;
use \Triki\Partial;

/**
 * returns args you'be passed as array - great for testing
 * @param $x
 * @param $y
 * @return array
 */
function giveMeAllYourArgs($x, $y) {
    return func_get_args();
}

class SomeObject {
    public function publicMethod($x, $y)
    {
        return [$x, $y];
    }
}

class PartialTest extends \PHPUnit_Framework_TestCase {

    public function setUp()
    {
        \Triki\Partial::load();
    }

    public function test_partial_function()
    {
        /** @var callable $originalWithXSetTo2 */
        $originalWithXSetTo2 = makePartial('giveMeAllYourArgs', 2);
        $this->assertEquals([2, 4], $originalWithXSetTo2(4));
    }

    public function test_partial_with_placeholder()
    {
        /** @var callable $originalWithYSetTo2 */
        $originalWithYSetTo2 = makePartial('giveMeAllYourArgs', Partial::_(), 2);
        $this->assertEquals([1, 2], $originalWithYSetTo2(1));
    }

    public function test_partial_with_many_placeholders()
    {
        /** @var callable $functionWithOddParamsSet */
        $functionWithOddParamsSet = makePartial(
            'giveMeAllYourArgs',
            1, Partial::_(), 3, Partial::_(), 5
            );

        $this->assertEquals(
            [1, 2, 3, 4, 5, 6],
            $functionWithOddParamsSet(2, 4, 6)
        );
    }

    public function test_partial_for_object()
    {
        $object  = new SomeObject();
        /** @var callable $partialWithFirstParamSetTo0 */
        $partialWithFirstParamSetTo0 = makePartial([$object, 'publicMethod'], 0);

        $this->assertEquals([0, 1], $partialWithFirstParamSetTo0(1));
    }

    public function test_with_partial_do()
    {
        $this->assertEquals(
            [0, 1],
            withPartialDo('giveMeAllYourArgs', 0, function($partial){
              return $partial(1);
            })
        );
    }

    public function test_withPartialDo_with_object()
    {
        $object = new SomeObject();

        $this->assertEquals(
            [-1, 1],
            withPartialDo(
                [$object, 'publicMethod'],
                -1,
                function ($part) {
                    return $part(1);
                }
            )
        );

    }
}
