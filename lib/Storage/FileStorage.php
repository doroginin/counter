<?php

namespace dd\Counter\Storage;

use dd\Counter\RWMutexAwareInterface;
use dd\Counter\StorageInterface;

class FileStorage implements StorageInterface, RWMutexAwareInterface
{
    /**
     * @var \SplFileObject
     */
    protected $_file;

    /**
     * @var array
     */
    protected $_data = [];

    public function __construct($file)
    {
        $this->_file = new \SplFileObject($file, 'a+');
        $this->_file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);
    }

    /**
     * @param string $domain
     * @return int
     */
    public function count($domain)
    {
        $this->_read();
        $count = 0;
        if (!empty($this->_data[$domain])) {
            foreach ($this->_data[$domain] as $item) {
                if (!empty($item['count'])) {
                    $count += $item['count'];
                }
            }
        }
        return $count;
    }

    /**
     * @param $domain string
     * @param bool $unique
     * @return $this
     */
    public function addVisit($domain, $unique = false)
    {
        $date = date('Y-m-d');
        $this->_read();
        if (empty($this->_data[$domain][$date]['count'])) {
            $this->_data[$domain][$date]['count'] = 1;
        } else {
            $this->_data[$domain][$date]['count']++;
        }
        if (empty($this->_data[$domain][$date]['uCount'])) {
            $this->_data[$domain][$date]['uCount'] = 1;
        } else {
            $this->_data[$domain][$date]['uCount'] += (int) $unique;
        }
        $this->_write();
    }

    /**
     * @return array
     */
    public function report()
    {
        $this->_read();
        return $this->_data;
    }

    /**
     * Locks for writing
     * @return $this
     */
    public function Lock()
    {
        $this->_file->flock(LOCK_EX);
    }

    /**
     * @return $this
     */
    public function Unlock()
    {
        $this->_file->flock(LOCK_UN);
    }

    /**
     * Locks for reading
     * @return $this
     */
    public function RLock()
    {
        $this->_file->flock(LOCK_SH);
    }

    /**
     * @return $this
     */
    public function RUnlock()
    {
        $this->_file->flock(LOCK_UN);
    }

    private function _read()
    {
        $this->_data = [];
        foreach ($this->_file as $row) {
            if (!empty($row)) {
                list($domain, $date, $count, $uCount) = $row;
                $this->_data[$domain][$date] = ['count' => $count, 'uCount' => $uCount];
            }
        }
    }

    private function _write()
    {
        $this->_file->ftruncate(0);
        foreach ($this->_data as $domain => $stats) {
            foreach ($stats as $date => $row) {
                $this->_file->fputcsv([$domain, $date] + $row);
            }
        }
    }
}