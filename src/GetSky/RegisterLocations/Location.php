<?php
namespace GetSky\RegisterLocations;

/**
 * Class Location
 * @package GetSky\RegisterLocations
 */
class Location implements LocationInterface
{

    /**
     * @var
     */
    protected $uid;
    /**
     * @var
     */
    protected $geom;
    /**
     * @var
     */
    protected $region;
    /**
     * @var
     */
    protected $mapCode;
    /**
     * @var
     */
    protected $nameVariants;
    /**
     * @var
     */
    protected $type;
    /**
     * @var
     */
    protected $name;
      /**
       * @var
       */
    protected $description;

    /**
     * @param $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * @param $geom
     */
    public function setGeom($geom)
    {
        $this->geom = $geom;
    }

    /**
     * @param $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $mapCode
     */
    public function setMapCode($mapCode)
    {
        $this->mapCode = $mapCode;
    }

    /**
     * @param $nameVariants
     */
    public function setNameVariants($nameVariants)
    {
        $this->nameVariants = $nameVariants;
    }

    /**
     * @param $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @param $region
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function  __toString()
    {
        return
            $this->uid . "\t" .
            $this->name . "\t" .
            $this->type . "\t" .
            $this->nameVariants . "\t" .
            $this->mapCode . "\t" .
            $this->description . "\t" .
            $this->region . "\t" .
            $this->geom . "\r\n";
    }
}