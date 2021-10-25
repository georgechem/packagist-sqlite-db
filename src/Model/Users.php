<?php

namespace Georgechem\SqliteDb\Model;

/**
 * Users - storage
 */
class Users extends Db
{
    /**
     * Insert user into database
     * @param $email
     * @param $password
     * @param null $session
     * @return bool
     */
    public function insert($email, $password, $session = null):bool
    {
        if($this->isUser($email)) return false;
        $password_hash = $this->generateHash($password);
        $session_hash = null;
        if($session){
            $session_hash = $this->generateHash($session);
        }
        return $this->query("
            INSERT INTO users (email, password, session_hash)
            VALUES (:email, :password, :session_hash)
        ", false, [
            ':email' => $email,
            ':password' => $password_hash,
            ':session_hash' => $session_hash
        ]);
    }

    /**
     * Check if user with given email already exist
     * Optionally can return that user
     * @param string $email
     * @param false $fetch
     * @return bool|array
     */
    public function isUser(string $email, bool $fetch = false): bool|array
    {
        $result = $this->query("
            SELECT * FROM users WHERE email = :email;
        ", true, [':email' => $email]);
        if(!$fetch){
            return (bool) $result;
        }
        return $result;
    }

    /**
     * Return All users from database
     * @return bool|array
     */
    public function getUsers(): bool|array
    {
        return $this->query("
            SELECT * FROM users WHERE 1;
        ", true);

    }

    /**
     * Verify user password against stored hash
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function verifyPassword(string $email, string $password): bool
    {
        $result = $this->query("
            SELECT password FROM users WHERE email = :email;
        ", true, [':email' => $email]);

        if(count($result) < 1) return false;

        return password_verify($password, $result[0]['password']);

    }

    public function create(): bool
    {
        return $this->query(
            "CREATE TABLE IF NOT EXISTS users(
                id integer PRIMARY KEY AUTOINCREMENT,
                email text NOT NULL UNIQUE,
                password text NOT NULL,
                session_hash text
            )
        ");
    }

    public function destroy(): bool
    {
        return $this->query("
            DROP TABLE IF EXISTS users;
        ");
    }

    public function empty(): bool
    {
        return $this->query("
            DELETE FROM users WHERE 1;
        ");
    }
}