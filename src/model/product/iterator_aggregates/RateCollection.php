<?php

class RateCollection implements IteratorAggregate {
    private array $rateCollection;
	function __construct() {
        $this->rateCollection = array();
	}
    function getItem($key):RateInterface{
        return $this->rateCollection[(string)$key];
    }
    function getItems():array{
        return $this->rateCollection;
    }
    function add(RateInterface $rate):void{
        $this->rateCollection[(string)$rate->getUuid()] = $rate;
    }
	function getIterator():Iterator {
        return new ArrayIterator($this->rateCollection);
    }
}