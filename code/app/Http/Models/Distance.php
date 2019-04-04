<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Distance extends Model
{
    /**
     * @var string
     */
    protected $table = 'distance';

    /**
     * Fetches distance between two geo coordinates.
     * //if distance alraedy available in DB for given geo lat and long
     *
     *
     * @param string $sourceLat
     * @param string $sourceLong
     * @param string $destinationLat
     * @param string $destinationLong
     *
     * @return self
     */
    public function getAvailableDistance(
        $sourceLat,
        $sourceLong,
        $destinationLat,
        $destinationLong
    ) {

        //check if for the same source and destnation lat long already available then use that
        $distance = self::where([
            ['start_lat', '=', $sourceLat],
            ['start_long', '=', $sourceLong],
            ['end_lat', '=', $destinationLat],
            ['end_long', '=', $destinationLong],
        ])->first();

        return $distance;
    }

    public function saveDistance(
        $sourceLat,
        $sourceLong,
        $destinationLat,
        $destinationLong,
        $distanceBetween
    ) {

        //inserting data in distance table
        $distance                        = new Distance;
        $distance->start_lat = $sourceLat;
        $distance->start_long = $sourceLong;
        $distance->end_lat  = $destinationLat;
        $distance->end_long = $destinationLong;
        $distance->distance = $distanceBetween;
        $distance->save();

        return $distance;
    }
}
