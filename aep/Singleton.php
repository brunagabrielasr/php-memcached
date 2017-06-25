<?php

class Singleton {

    private static $instancia;
    private $pdo;
    private $cache;

    function getCache() {
        if (!$this->cache) {
            $this->cache = new Memcache();
            $this->cache->addserver("localhost", 11211);
        }
        return $this->cache;
    }

    function getPdo() {
        if (!$this->pdo) {
            $this->pdo = new PDO("mysql:host=localhost;dbname=aep_3ano;charset=utf8", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return $this->pdo;
    }

    public static function getInstancia() {
        if (!self::$instancia) {
            self::$instancia = new Singleton();
        }
        return self::$instancia;
    }

}
