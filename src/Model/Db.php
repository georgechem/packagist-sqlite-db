<?php

namespace Georgechem\SqliteDb\Model;

abstract class Db
{
    protected static ?\PDO $pdo = null;

    final public function __construct()
    {
        /**
         * Create connection with relevant database
         */
        self::getConnection();
        /**
         * Create relevant storage
         */
        $this->create();;
    }

    public static function getConnection():?\PDO
    {
        if(empty(self::$pdo)){
            try {
                self::$pdo = new \PDO('sqlite:' . __DIR__ . '/' . 'sqlite_db.sqlite3', null, null, [
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

    /**
     * Storage for objects created automatically when child class is instantiated
     * @return bool
     */
    abstract public function create():bool;

    /**
     * Should be responsible for dropping table in database
     * @return bool
     */
    abstract public function destroy():bool;

    /**
     * Should be responsible for deleting everything from database
     * @return bool
     */
    abstract public function empty():bool;

    /**
     * For details:
     * https://www.php.net/manual/en/function.filter-input-array
     * @param array $input ex: ['email' => FILTER_SANITIZE_EMAIL, 'password' => FILTER_UNSAFE_RAW]
     * data are provided by $_POST['email'], $_POST['password']
     * @param int $type
     * @return array|false
     */
    protected function sanitizeInput(array $input, int $type = INPUT_POST):array|false
    {
        return filter_input_array($type, $input,false);
    }

    /**
     * For details:
     * https://www.php.net/manual/en/function.filter-var
     * @param mixed $variable
     * @param int $type
     * @return mixed
     */
    protected function sanitizeVariable(mixed $variable, int $type = FILTER_SANITIZE_STRING):mixed
    {
        return filter_var($variable, $type);
    }

    /**
     * Serialize value
     * @param mixed $variable
     * @return string
     */
    protected function serialize(mixed $variable):string
    {
       return serialize($variable);
    }

    /**
     * Unserialize value
     * @param string $variable
     * @return mixed
     */
    protected function unSerialize(string $variable):mixed
    {
        return unserialize($variable);
    }

    /**
     * Query helper function
     * @param string $query
     * @param bool $isFetch
     * @param array|null $params
     * @return array|bool
     */
    protected function query(string $query, bool $isFetch = false, array $params = null): array|bool
    {
        $stmt = self::$pdo->prepare($query);

        $result = $stmt->execute($params);
        if(!$isFetch){
            return $result;
        }
        return $stmt->fetchAll();
    }

    protected function generateHash(string $variable):string
    {
        return password_hash($variable, PASSWORD_ARGON2ID, []);
    }

    protected function compareAgainstHash(string $variable, string $hash):bool
    {
        return password_verify($variable, $hash);
    }


}