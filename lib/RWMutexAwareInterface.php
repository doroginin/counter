<?php

namespace dd\Counter;

interface RWMutexAwareInterface
{
    /**
     * Locks for writing
     * @return $this
     */
    public function Lock();

    /**
     * @return $this
     */
    public function Unlock();

    /**
     * Locks for reading
     * @return $this
     */
    public function RLock();

    /**
     * @return $this
     */
    public function RUnlock();
}