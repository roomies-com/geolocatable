<?php

declare(strict_types=1);

namespace Roomies\Geolocatable;

use Roomies\Geolocatable\Result\Geolocation;

interface GeolocatesIpAddresses
{
    /**
     * Geolocate the given IP address.
     */
    public function ip(string $ipAddress): ?Geolocation;
}
