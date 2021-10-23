### Simple Sqlite Storage
<hr>

Storage should be used only for simple key-pair data. Objects storage 
should be avoided as bad practice however it is fully supported.
To store objects it is better to create separate class and extends abstract Db class.

ex:
```php
class User extends Db {
    self::$pdo - object is available 
    
    ... add your methods like: 
    public function insertUser() ... etc.
    
    Methods that must be implemented (required by abstarct class Db)
    
    public function createStorage(){}
    public function destroyStorage(){}
    public function emptyStorage(){}
    
}
```

```php
mixed $value - it can be any type (under the hood $value is serialized and
unserialized so type is preserved);

string $key - as it states must be a string
```

Install using composer:
```
composer require georgechem/sqlite-db
```
Require autoloader as normal:
```php
require __DIR__ . '/vendor/autoload.php';
```
Usage
<hr>

```php
$storage = new Storage(); 

// save value associated with certain key in database (keys are unique)
$storage->save(string 'key_name', mixed $value);

// if you want to overwrite certain key set appropriate flag
$storage->save(string 'key_name', mixed $value, true);

// To read value associated wit certain key
$storage->read(string 'test');

// returns array of all available key in storage OR empty array if none
$storage->getAllKeys();

// empty storage (delete only entries but keep table)
$storage->emptyStorage();

// destroy storage
$storage->destroyStorage();
```