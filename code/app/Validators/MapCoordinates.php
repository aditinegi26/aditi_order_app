<?php

namespace App\Validators;

class MapCoordinates
{
    /**
     * @var string
     */
    protected $error;

    public function getError()
    {
        return $this->error;
    }

    /**
     *
     *Validating given lat and longs
     * @param float $sourceLat
     * @param float $startLong
     * @param float $destinationLat
     * @param float $destinationLong
     *
     * @return bool
     */
    public function validate(
        $startLat,
        $startLong,
        $destinationLat,
        $destinationLong
    ) {

        if ($startLat == $destinationLat && $startLong == $destinationLong) {
            $this->error = 'REQUESTED_SOURCE_DESTINATION_SAME';
        } elseif (!$startLat || !$startLong || !$destinationLat
            || !$destinationLong) {
            $this->error = 'REQUEST_PARAMETER_MISSING';
        } elseif ($startLat < -90 || $startLat > 90 || $destinationLat
            < -90 || $destinationLat > 90) {
            $this->error = 'LATITUDE_OUT_OF_RANGE';
        } elseif ($startLong < -180 || $startLong > 180 || $destinationLong
            < -180 || $destinationLong > 180) {
            $this->error = 'LONGITUDE_OUT_OF_RANGE';
        } elseif (!is_numeric($startLat) || !is_numeric($destinationLat)
            || !is_numeric($startLong) || !is_numeric($destinationLong)) {
            $this->error = 'INVALID_PARAMETERS';
        }

        return $this->error ? false : true;
    }
}
