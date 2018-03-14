<?php

namespace intercom\libraries;

class LocationLibrary
{
    const EARTH_RADIUS =  6371000;

    public function getCoordinatesDistanceInKilometersByHavesineFormula(
        $firstLatitude,
        $firstLongitude,
        $secondLatitude,
        $secondLongitude
    ) {
        // convert from degrees to radians
        $latitudeFrom = deg2rad($firstLatitude);
        $longitudeFrom = deg2rad($firstLongitude);
        $latitudeTo = deg2rad($secondLatitude);
        $longitudeTo = deg2rad($secondLongitude);

        $latitudeDelta = $latitudeTo - $latitudeFrom;
        $longitudeDelta = $longitudeTo - $longitudeFrom;

        $angle = 2 * asin(sqrt(pow(sin($latitudeDelta / 2), 2) + cos($latitudeFrom) * cos($latitudeTo) * pow(sin($longitudeDelta / 2), 2)));

        return ($angle * self::EARTH_RADIUS) / 1000;
    }
}
