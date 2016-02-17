<?php

namespace dd\Counter\Storage;

use dd\Counter\StorageInterface;

class PdoStorage implements StorageInterface
{
    protected $_dbh;

    /**
     * Pdo constructor.
     * @param $dsn
     * @param $user
     * @param $pwd
     * @throws \PDOException
     */
    public function __construct($dsn, $user, $pwd)
    {
        $this->_dbh = new \PDO($dsn, $user, $pwd);
    }

    /**
     * @param $domain
     * @return int
     */
    public function count($domain)
    {
        $stmt = $this->_dbh->prepare("SELECT SUM(`count`) FROM `visitors` WHERE `domain` = ?");
        $stmt->execute([$domain]);
        $result = $stmt->fetchColumn();
        return (int) $result;
    }

    /**
     * @param $domain string
     * @param bool $unique
     * @return $this
     */
    public function addVisit($domain, $unique = false)
    {
        $stmt = $this->_dbh->prepare(
            "INSERT INTO `visitors` (`domain`, `date`, `count`, `unique_count`) VALUES (?, DATE(NOW()), 1, 1)
             ON DUPLICATE KEY UPDATE `count` = `count` + 1, `unique_count` = `unique_count` + ?");
        $stmt->execute([$domain, (int) $unique]);
    }

    /**
     * @return array
     */
    public function report()
    {
        // @todo add pagination
        $stmt = $this->_dbh->query("SELECT `domain`, `date`, `count`, `unique_count` FROM `visitors`");
        $data = [];
        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $data[$row['domain']][$row['date']] = [
                'count' => $row['count'],
                'uCount' => $row['unique_count']
            ];
        }
        return $data;
    }
}