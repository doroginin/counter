<?php

namespace dd\Counter;

interface StorageInterface
{
    /**
     * @param $domain string
     * @return int
     */
    public function count($domain);

    /**
     * @param $domain string
     * @param bool $unique
     * @return $this
     */
    public function addVisit($domain, $unique = false);

    /**
     * @return array
     */
    public function report();
}