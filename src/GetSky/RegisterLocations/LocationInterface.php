<?php
namespace GetSky\RegisterLocations;


interface LocationInterface {

    public function setUid($uid);

    public function setGeom($geom);

    public function setType($type);

    public function setName($name);

    public function setMapCode($mapCode);

    public function setNameVariants($nameVariants);

    public function setRegion($region);

    public function setDescription($description);

    public function setDateChange($dateChange);
}