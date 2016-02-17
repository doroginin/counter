<?php

namespace dd\Counter;

class Counter implements CounterInterface
{
    const SESSION_DURATION = 86400; // 1 day

    /** @var StorageInterface */
    protected $_storage;

    public function __construct(StorageInterface $storage)
    {
        $this->_storage = $storage;
    }

    public function count()
    {
        if ($this->_storage instanceof RWMutexAwareInterface) {
            $this->_storage->RLock();
        }
        $count = $this->_storage->count($_SERVER['SERVER_NAME']);
        if ($this->_storage instanceof RWMutexAwareInterface) {
            $this->_storage->RUnlock();
        }
        return $count;
    }

    /**
     * @return $this
     */
    public function process()
    {
        $unique = empty($_COOKIE['cc']);
        if ($unique) {
            setcookie('cc', true, time() + static::SESSION_DURATION);
        }
        if ($this->_storage instanceof RWMutexAwareInterface) {
            $this->_storage->Lock();
        }
        $this->_storage->addVisit($_SERVER['SERVER_NAME'], $unique);
        if ($this->_storage instanceof RWMutexAwareInterface) {
            $this->_storage->Unlock();
        }
        return $this;
    }

    /**
     * @return array
     */
    public function report()
    {
        if ($this->_storage instanceof RWMutexAwareInterface) {
            $this->_storage->RLock();
        }
        $data = $this->_storage->report();
        if ($this->_storage instanceof RWMutexAwareInterface) {
            $this->_storage->RUnlock();
        }
        return $data;
    }
}