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
        $this->createStorage();;
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

    /**
     * Storage for objects created automatically when child class is instantiated
     * @return bool
     */
    abstract public function createStorage():bool;

    abstract public function destroyStorage():bool;

    abstract public function emptyStorage():bool;

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

    protected function serialize(mixed $variable):string
    {
       return serialize($variable);
    }

    protected function unSerialize(string $variable):mixed
    {
        return unserialize($variable);
    }


}