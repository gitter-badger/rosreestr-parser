<?php

require_once __DIR__ . '\..\vendor\autoload.php';

use GetSky\RegisterLocations\Parser;

$exm = new Parser();
$exm->push('sample.txt');

$exm->generation();

//print_r($exm->getLocations());

foreach ($exm->getLocations() as $v) {
    echo $v;
}