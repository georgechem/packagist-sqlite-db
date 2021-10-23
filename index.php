<?php

use Georgechem\SqliteDb\Model\Storage;

require __DIR__ . '/src/bootstrap.php';

// Code

$storage = new Storage();
print_r($storage->createStorage());