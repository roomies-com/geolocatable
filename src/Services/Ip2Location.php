<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Services;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Roomies\Geolocatable\GeolocatesIpAddresses;
use Roomies\Geolocatable\Result\Geolocation;
use Roomies\Geolocatable\Result\Location;
use Roomies\Geolocatable\Result\Network;

class Ip2Location implements GeolocatesIpAddresses
{
    /**
     * Create a new instance.
     */
    public function __construct(protected ?string $key)
    {
        //
    }

    /**
     * Geolocate the given IP address.
     */
    public function ip(string $ipAddress): ?Geolocation
    {
        $response = Http::baseUrl('https://api.ip2location.io')
            ->get('/', [
                'ip' => $ipAddress,
                'key' => $this->key,
            ]);

        if (! $response->ok()) {
            return null;
        }

        if ($response->json('error')) {
            throw new InvalidArgumentException($response->json('error.error_message'));
        }

        return new Geolocation(
            source: 'ip2location',
            ipAddress: $response->json('ip'),
            location: new Location(
                continent: $response->json('continent.name'),
                country: $response->json('country_name'),
                countryIso: $response->json('country_code'),
                state: $response->json('region_name'),
                city: $response->json('city_name'),
                postalCode: $response->json('zip'),
                latitude: $response->json('latitude'),
                longitude: $response->json('longitude'),
                timeZone: $response->json('time_zone'),
            ),
            network: new Network(
                isp: $response->json('isp'),
                organization: $response->json('as'),
                isProxy: $response->json('is_proxy'),
            ),
            raw: $response->json(),
        );
    }
}
