<?php

use Georgechem\SqliteDb\Model\Storage;

require __DIR__ . '/vendor/autoload.php';

// Code

$storage = new Storage();

//$storage->save('abcdef', 'test2');

//$storage->read('test');

print_r($storage->getAllKeys());

//$storage->destroyStorage();