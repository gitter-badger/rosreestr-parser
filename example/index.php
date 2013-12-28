<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use GetSky\RegisterLocations\ErrorParserException;
use GetSky\RegisterLocations\Parser;

$exm = new Parser();

$exm->push('sample.txt');

try {
    $exm->generation();
} catch (ErrorParserException $e) {
    print_r($exm->errors);
}


//print_r($exm->getLocations());

foreach ($exm->getLocations() as $v) {
    echo $v;
}