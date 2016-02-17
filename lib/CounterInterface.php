<?php

namespace dd\Counter;

interface CounterInterface
{
    /**
     * @return int
     */
    public function count();

    /**
     * @return $this
     */
    public function process();

    /**
     * @return array
     */
    public function report();
}