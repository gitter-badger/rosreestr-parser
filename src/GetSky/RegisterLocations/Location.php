<?php
namespace GetSky\RegisterLocations;


class Location implements LocationInterface {

    public function setUid($uid)
    {
        $this->uid = $uid;
    }
}