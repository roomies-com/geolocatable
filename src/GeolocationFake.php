<?php

namespace Roomies\Geolocatable;

use Illuminate\Support\Testing\Fakes\Fake;
use Roomies\Geolocatable\Result\Geolocation;

class GeolocationFake implements Fake, GeolocatesIpAddresses
{
    /**
     * Create a new fake instance.
     */
    public function __construct(public ?Geolocation $result)
    {
        //
    }

    /**
     * Geolocate the given IP address.
     */
    public function ip(string $ipAddress): ?Geolocation
    {
        return $this->result;
    }
}
