<?php
 /**
 * @category   Nexway
 * @package    Nexway_...
 * @author     Grzegorz Pawlik <gpawlik@nexway.com>
 */

namespace Useful;

/**
 * Class ProgressIterator
 * @package Useful
 * example:
 *
 * $myIterator = getLotOfStuffToWorkOnFromDb();
 * $myIterator = new \Useful\ProgressIterator($myIterator);
 *
 * foreach($myIterator as $value) {
 *      doLongJob($value);
 *      $cli->progressBarUpdate("Progress: %d", $myIterator->progress());
 * }
 *
 *
 */
class ProgressIterator implements \Iterator, \Countable{

    /** @var \Traversable|array */
    private $collection;
    /** @var number */
    private $size,
            $position = 0;

    public function __construct(\Iterator $collection){

        if(!$collection instanceof \Countable) {
            throw new \InvalidArgumentException(
                __CLASS__ .
                " requires object that implements \\Iterator and " .
                "\\Countable as constructor parameter"
            );
        }

        $this->collection = $collection;
        $this->size = $collection->count();
    }

    public function progress()
    {
        return $this->position * 100 / $this->size;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->collection->current();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->position++;
        return $this->collection->next();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->collection->key();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->collection->valid();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        return $this->collection->rewind();
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        $this->collection->count();
    }
}