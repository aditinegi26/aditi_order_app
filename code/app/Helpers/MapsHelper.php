<?php

namespace App\Helpers;

use \App\Library\DistanceInterface;

class MapsHelper
{
    /**
     * @var DistanceInterface
     */
    protected $distanceObj;

    /**
     * Constructor.
     *
     * @param DistanceInterface $distanceObj
     */
    public function __construct(DistanceInterface $distanceObj)
    {
        $this->distanceObj = $distanceObj;
    }

    /**
     * Fetches distance between two pairs of lat and long
     *
     * @param string $source
     * @param string $destination
     *
     * @return int Distance in meters
     */
    public function getDistanceFromMapHelper($source, $destination)
    {
        return $this->distanceObj->getDistance($source, $destination);
    }
}
