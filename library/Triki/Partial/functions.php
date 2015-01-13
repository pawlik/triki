<?php
namespace Triki\Partial;

/**
 * creates partial function
 *
 * i.e. consider function add($x, $y),
 * $addTo2 = makePartial('add', 2);
 * $addTo2(6); // 8
 *
 * params are 'frozen' from first to last
 *
 * @param $fun
 * @returns callable
 */
/**
 * @param $fun
 * @return callable
 */
function makePartial() {
    $partial = new Partial();
    return call_user_func_array([$partial, 'makePartial'], func_get_args());
}

/**
 * allows executing partial immediately without storing partial in varialbe
 *
 * i.e. someFunction($username, $address, $action);
 * withPartual('someFunction', 'grzegorz', 'highInTheSky', function($partial) {
 *      $partial('action1');
 *      $partial('action2');
 *      // etc.
 * });
 *
 */
function withPartialDo($fun) {
    $args = func_get_args();
    /** @var callable $callback */
    $callback = array_pop($args);
    $partial = call_user_func_array('Triki\Partial\makePartial', $args);
    return $callback($partial);
}