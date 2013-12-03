<?php
namespace GetSky\RegisterLocations;


class Location implements LocationInterface {

    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    public function setGeom($geom)
    {
        $this->geom = $geom;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}