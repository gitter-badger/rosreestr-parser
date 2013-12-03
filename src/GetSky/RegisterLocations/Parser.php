<?php
namespace GetSky\RegisterLocations;

use Exception;
use SplDoublyLinkedList;

/**
 * Class Parser
 * @package GetSky\RegisterLocations
 */
class Parser extends SplDoublyLinkedList
{
    /**
     * Excess lines
     */
    const ROW = "1\r\n\r\n2\r\n\r\n3\r\n\r\n4\r\n\r\n5\r\n\r\n6\r\n\r\n7";

    /**
     * New row
     */
    const NEW_ROW = "\r\n";

    /**
     * Substitute newline
     */
    const SUB_NEW_ROW = "|";


    /**
     * Pages sheets
     */
    const PAGES = "#Стр. [0-9]{1,3} из [0-9]{1,3}#";

    /**
     * Date of renovation
     */
    const DATE = "#[0-9]{2}/[0-9]{2}/[0-9]{4}#";

    /**
     * Number Number
     */
    const NUMBER = "#№№#";

    /**
     * UID locations
     */
    const ID_REG = "#[0-9]{7}#";

    /**
     * Latitude and longitude of the location
     */
    const LAT_LONG = "#[0-9]{2}° [0-9]{2}' С.Ш. [0-9]{2}° [0-9]{2}' В.Д.#";

    /**
     * Nomenclature of map sheet
     */
    const MAP = "#[A-Z]-[0-9]{2}-[0-9]{1,3}#";

    /**
     * Type of settlement
     *
     * @var string[]
     */
    protected static $typeOfLocations = [
        'деревня',
        'село',
        'пст',
        'город',
        'пгт'
    ];

    /**
     * Text from a file
     *
     * @var string
     */
    protected $text;

    /**
     * Name of the region
     *
     * @var
     */
    protected $region;

    /**
     * Object location to create clones
     *
     * @var Location
     */
    protected $location;

    /**
     * Array prepared locations
     *
     * @var LocationInterface[]
     */
    protected $locations = [];

    /**
     * @param LocationInterface $location
     */
    public function  __construct(LocationInterface $location = null)
    {
        if ($location !== null) {
            $this->location = $location;
        } else {
            $this->location = new Location();
        }
    }

    /**
     * Generate array with locations
     */
    public function generation()
    {

        $this->locations = [];

        foreach ($this as $link) {
            $this->text = file_get_contents($link);
            $this->cleanText();
            $this->fixedRegion();
            $this->parser();
        }
    }

    /**
     * Clean text from garbage
     */
    protected function cleanText()
    {
        $this->text = str_replace(array($this::ROW), array(''), $this->text);
        $this->text = preg_replace($this::PAGES, '', $this->text);
    }

    /**
     * Fix a region of a file
     */
    protected function fixedRegion()
    {
        preg_match($this::DATE, $this->text, $date, PREG_OFFSET_CAPTURE);
        preg_match($this::NUMBER, $this->text, $number, PREG_OFFSET_CAPTURE);

        $position = $date[0][1] + 10;
        $this->region = trim(
            substr($this->text, $position, $number[0][1] - $position)
        );
    }

    /**
     * Parse the file and populate an array of locations
     */
    protected function parser()
    {
        preg_match_all($this::ID_REG, $this->text, $id, PREG_OFFSET_CAPTURE);

        header('Content-Type: text/html; charset=utf-8');

        $this->location->setRegion($this->region);

        foreach ($id[0] as $key => $value) {

            $location = clone $this->location;

            $positionFest = $id[0][$key][1];
            $positionSecond = $id[0][$key + 1][1];
            $start = $positionFest + 7;
            $end = $positionSecond - ($start + 6);

            if ($positionSecond === null) {
                $string = substr($this->text, $start);
            } else {
                $string = substr($this->text, $start, $end);
            }

            preg_match($this::LAT_LONG, $string, $coord, PREG_OFFSET_CAPTURE);
            preg_match($this::MAP, $string, $mapCode, PREG_OFFSET_CAPTURE);

            $geom = $this->convertStringToGeom($coord[0][0]);
            $type = $this->searchTypeLocation($string);

            $name = trim(
                str_replace($type, '', substr($string, 0, $coord[0][1]))
            );

            $nameVariants = str_replace(
                array($this::NEW_ROW),
                array($this::SUB_NEW_ROW),
                trim(substr($string, $mapCode[0][1] + strlen($mapCode[0][0])))
            );

            $mapCode = trim($mapCode[0][0]);


            $location->setUid($value[0]);
            $location->setGeom($geom);
            $location->setType($type);
            $location->setName($name);
            $location->setMapCode($mapCode);
            $location->setNameVariants($nameVariants);

            $this->locations[] = $location;
        }
    }

    /**
     * Convert a string:
     * [0-9]{2}° [0-9]{2}' С.Ш. [0-9]{2}° [0-9]{2}' В.Д.)
     *
     * to the geometry:
     * POINT(X Y)
     *
     * @param string $string
     * @return string
     */
    public function convertStringToGeom($string)
    {
        $lat = (float)substr($string, 0, 2) + (float)substr($string, 5, 2) / 60;
        $long = (float)substr($string, 16, 2) + (float)substr($string, 21, 2) / 60;

        $geom = 'POINT(' . $long . ' ' . $lat . ')';

        return $geom;
    }

    /**
     * @param $string
     * @return Exception
     */
    public function searchTypeLocation($string)
    {
        foreach ($this::$typeOfLocations as $type) {

            $position = strpos($string, $type.$this::NEW_ROW);

            if ($position !== false) {

                return $type;
            }
        }
        return new Exception('Not found type of location!');
    }

    /**
     * @return LocationInterface[]
     */
    public function getLocations()
    {
        return $this->locations;
    }
} 