<?php
namespace GetSky\RegisterLocations;


interface LocationInterface {

    public function setUid($uid);

    public function setGeom($geom);

    public function setType($type);

    public function setName($name);
}