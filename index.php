<?php

use Georgechem\SqliteDb\Model\Storage;

require __DIR__ . '/src/bootstrap.php';

// Code

$storage = new Storage();

$storage->save('data', 'test1', true);
//$storage->destroyStorage();