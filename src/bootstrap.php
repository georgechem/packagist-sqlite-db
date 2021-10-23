<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$mode = $_ENV['MODE'] ?? null;

if($mode = 'dev'){
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}