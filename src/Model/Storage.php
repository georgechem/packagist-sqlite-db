<?php
declare(strict_types=1);

namespace Georgechem\SqliteDb\Model;
/**
 * General Purpose storage
 */
class Storage extends Db
{

    /**
     * Create Table storage in database
     * @Return True|False on success|failure
     */
    public function create():bool
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
    public function destroy():bool
    {
        $stmt = self::$pdo->prepare("
            DROP TABLE IF EXISTS storage
        ");
        return $stmt->execute();
    }

    /**
     * @Return True|False on success|failure
     */
    public function empty(): bool
    {
        $stmt = self::$pdo->prepare("
            DELETE FROM storage WHERE 1;
        ");
        return $stmt->execute();
    }

    /**
     * Check is such as key already exists in Storage
     * @param string $key
     * @return bool
     */
    private function isKeyExists(string $key): bool
    {
        try {
            $stmt = self::$pdo->prepare("
            SELECT key from storage where key = :key;
        ");
            $stmt->execute([
                ':key' => $key
            ]);
            $count = count($stmt->fetchAll());
            if($count > 0) return true;
            return false;
        }catch(\Exception $e){
            echo $e->getMessage() . ' | line=' . $e->getLine() . ' | file=' . $e->getFile();
            exit;
        }

    }

    /**
     * Returns all key present in storage database
     * @return array
     */
    public function getAllKeys():array
    {
        $stmt = self::$pdo->prepare("
            SELECT key FROM storage WHERE 1;
        ");
        $stmt->execute();

        $results = $stmt->fetchAll();
        $keys = [];
        foreach($results as $item){
            $keys[] = $item['key'];
        }

        return $keys;
    }

    /**
     * Save pair (key, value) in storage
     * If flag overwrite is set to true, and key already exists value will be overwritten
     * @param string $key
     * @param mixed $value
     * @param bool $overwrite
     * @return bool|null
     */
    public function save(string $key, mixed $value, bool $overwrite = false): ?bool
    {
        $keyExists = $this->isKeyExists($key);
        if($keyExists === true && $overwrite !== true) return false;

        $data = $this->serialize($value);

        if(!$keyExists){
            $stmt = self::$pdo->prepare("
            INSERT INTO storage (key, value) VALUES (:key, :value)
        ");
        }else {
            $stmt = self::$pdo->prepare("
                UPDATE storage SET value = :value WHERE key = :key;
            ");
        }
        return $stmt->execute([
            ':key' => $key,
            ':value' => $data
        ]);
    }

    /**
     * Allow reading value associated with given key
     * @param string $key
     * @return mixed
     */
    public function read(string $key): mixed
    {
        $keyExists = $this->isKeyExists($key);
        if(!$keyExists) return null;

        $stmt = self::$pdo->prepare("
            SELECT * FROM storage WHERE key = :key;
        ");

        $stmt->execute([
            ':key' => $key
        ]);

        $result = $stmt->fetchAll()[0];

        return $this->unSerialize($result['value']);

    }
}