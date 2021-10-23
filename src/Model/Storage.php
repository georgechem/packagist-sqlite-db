<?php
declare(strict_types=1);

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
                value text,
                type text NOT NULL
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

    private function isKeyExists(string $key): bool
    {
        $stmt = self::$pdo->prepare("
            SELECT key from storage where key = :key;
        ");
        $stmt->execute([
            ':key' => $key
        ]);
        $count = count($stmt->fetchAll());
        if($count > 0) return true;
        return false;
    }

    public function save(string $key, mixed $value, bool $overwrite = false): ?bool
    {
        $keyExists = $this->isKeyExists($key);
        if($keyExists === true && $overwrite !== true) return false;

        $data = null;
        $type = null;
        $status = $this->isPrimitive($value);

        if(true === $status['primitive']){
            $data = (string) $value;
            $type = $status['type'];
        }
        else if(false === $status['primitive']){
            // TODO object and array so serialize
            $type = $status['type'];
        }else{
            return null;
        }

        if(!$keyExists){
            $stmt = self::$pdo->prepare("
            INSERT INTO storage (key, value, type) VALUES (:key, :value, :type)
        ");
        }else {
            $stmt = self::$pdo->prepare("
                UPDATE storage SET value = :value, type = :type WHERE key = :key;
            ");
        }
        return $stmt->execute([
            ':key' => $key,
            ':value' => $data,
            ':type' => $type
        ]);
        return false;
    }
}