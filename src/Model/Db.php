<?php

namespace Georgechem\SqliteDb\Model;

abstract class Db
{
    protected static ?\PDO $pdo = null;

    final public function __construct()
    {
        self::getConnection();
    }

    public static function getConnection():?\PDO
    {
        if(empty(self::$pdo)){
            try {
                self::$pdo = new \PDO('sqlite:' . __DIR__ . '/' . $_ENV['DB_FILE'], null, null, [
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_PERSISTENT,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]);
            }catch(\Exception $e){
                echo $e->getFile() . '<br/>' . $e->getMessage();
            }
        }
        return self::$pdo;

    }

}