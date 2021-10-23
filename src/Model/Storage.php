<?php

namespace Georgechem\SqliteDb\Model;

class Storage extends Db
{

    /**
     * Create Table storage in database
     * @Return True|False on success|failure
     */
    public function createStorage():bool
    {
        $stmt = self::$pdo->prepare("
            CREATE TABLE IF NOT EXISTS storage(
                id integer PRIMARY KEY AUTOINCREMENT,
                key text NOT NULL UNIQUE,
                value text 
            )
        ");
        return $stmt->execute();

    }

    /**
     * Drop table storage in database
     * @Return True|False on success|failure
     */
    public function destroyStorage():bool
    {
        $stmt = self::$pdo->prepare("
            DROP TABLE IF EXISTS storage
        ");
        return $stmt->execute();
    }

    /**
     * @Return True|False on success|failure
     */
    public function emptyStorage(): bool
    {
        $stmt = self::$pdo->prepare("
            DELETE FROM storage WHERE 1;
        ");
        return $stmt->execute();
    }
}