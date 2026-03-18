<?php

namespace Roomies\Geolocatable\Facades;

use Illuminate\Support\Facades\Facade;
use Roomies\Geolocatable\GeolocationFake;
use Roomies\Geolocatable\Manager;
use Roomies\Geolocatable\Result\Geolocation;

/**
 * @see Manager
 */
class Geolocate extends Facade
{
    /**
     * Replace the bound instance with a fake.
     */
    public static function fake(?Geolocation $result = null): GeolocationFake
    {
        return tap(new GeolocationFake($result), function ($fake) {
            static::swap($fake);
        });
    }

    protected static function getFacadeAccessor()
    {
        return 'geolocatable';
    }
}
