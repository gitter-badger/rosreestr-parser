<?php
namespace GetSky\RegisterLocations;

use SplDoublyLinkedList;

class Parser extends SplDoublyLinkedList
{
    const ROW = "1\r\n\r\n2\r\n\r\n3\r\n\r\n4\r\n\r\n5\r\n\r\n6\r\n\r\n7";
    const PAGES = "#Стр. [0-9]{1,3} из [0-9]{1,3}#";
    const DATE = "#[0-9]{2}/[0-9]{2}/[0-9]{4}#";
    const NUMBER = "#№№#";
    const ID_REG = "#[0-9]{7}#";

    protected $text;
    protected $region;
    protected $location;

    public function  __construct(LocationInterface $location = null)
    {
        if ($location !== null) {
            $this->locations = $location;
        } else {
            $this->locations = new Location();
        }
    }

    public function generation()
    {
        foreach ($this as $link) {
            $this->text = file_get_contents($link);
            $this->cleanText();
            $this->fixedRegion();
            $this->parser();
        }
    }

    protected function cleanText()
    {
        $this->text = str_replace(array($this::ROW), array(''), $this->text);
        $this->text = preg_replace($this::PAGES, '', $this->text);
    }

    protected function fixedRegion()
    {
        preg_match($this::DATE, $this->text, $date, PREG_OFFSET_CAPTURE);
        preg_match($this::NUMBER, $this->text, $number, PREG_OFFSET_CAPTURE);

        $position = $date[0][1] + 10;
        $this->region = substr(
            $this->text,
            $position,
            $number[0][1] - $position
        );
    }

    protected function parser()
    {
        preg_match_all($this::ID_REG, $this->text, $id, PREG_OFFSET_CAPTURE);

        foreach ($id[0] as $key => $string) {
            echo substr(
                $this->text,
                $id[0][$key][1],
                $id[0][$key + 1][1] - ($id[0][$key][1] + 6)
            );
            echo '<br/>';
        }
    }
} 