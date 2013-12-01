<?php

require_once __DIR__ . '\..\vendor\autoload.php';

use GetSky\RegisterLocations\Parser;

$exm = new Parser();
$exm->push('sample.txt');

$exm->generation();