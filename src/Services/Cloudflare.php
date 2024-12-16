<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Services;

use Illuminate\Http\Request;
use Roomies\Geolocatable\GeolocatesIpAddresses;
use Roomies\Geolocatable\Result\Geolocation;
use Roomies\Geolocatable\Result\Location;

class Cloudflare implements GeolocatesIpAddresses
{
    /**
     * Create a new instance.
     */
    public function __construct(protected Request $request)
    {
        //
    }

    /**
     * Geolocate the given IP address.
     */
    public function ip(string $ipAddress): ?Geolocation
    {
        if ($this->request->ip() !== $ipAddress) {
            return null;
        }

        return new Geolocation(
            source: 'cloudflare',
            ipAddress: $this->request->ip(),
            location: new Location(
                continent: $this->request->header('cf-ipcontinent'),
                country: $this->request->header('cf-ipcountry'),
                // countryIso: $this->request->header('countryCode'),
                state: $this->request->header('cf-region'),
                stateIso: $this->request->header('cf-region-code'),
                city: $this->request->header('cf-ipcity'),
                postalCode: $this->request->header('cf-postal-code'),
                latitude: $this->request->header('cf-iplatitude'),
                longitude: $this->request->header('cf-iplongitude'),
                timeZone: $this->request->header('cf-timezone'),
            ),
            raw: $this->request->headers->all(),
        );
    }
}
