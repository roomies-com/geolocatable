<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Services;

use GeoIp2\Database\Reader;
use Illuminate\Support\Arr;
use Roomies\Geolocatable\GeolocatesIpAddresses;
use Roomies\Geolocatable\Result\Geolocation;
use Roomies\Geolocatable\Result\Location;
use Roomies\Geolocatable\Result\Network;

class MaxmindDatabase implements GeolocatesIpAddresses
{
    /**
     * Create a new instance.
     */
    public function __construct(protected Reader $reader)
    {
        //
    }

    /**
     * Geolocate the given IP address.
     */
    public function ip(string $ipAddress): ?Geolocation
    {
        $result = $this->reader->city($ipAddress);

        $subdivision = $result->mostSpecificSubdivision;

        return new Geolocation(
            source: 'maxmind-database',
            ipAddress: $result->traits->ipAddress,
            location: new Location(
                continent: Arr::get($result->continent->names, 'en'),
                country: Arr::get($result->country->names, 'en'),
                countryIso: $result->country->isoCode,
                state: Arr::get($subdivision->names, 'en'),
                stateIso: $subdivision->isoCode,
                city: Arr::get($result->city->names, 'en'),
                postalCode: $result->postal->code,
                latitude: $result->location->latitude,
                longitude: $result->location->longitude,
                timeZone: $result->location->timeZone,
            ),
            network: new Network(
                domain: $result->traits->domain,
                isp: $result->traits->isp,
                organization: $result->traits->organization,
                isHosting: $result->traits->isHostingProvider,
                isAnonymous: $result->traits->isAnonymous,
            ),
            raw: $result
        );
    }
}
