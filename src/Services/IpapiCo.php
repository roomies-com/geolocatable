<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Services;

use Illuminate\Support\Facades\Http;
use Roomies\Geolocatable\GeolocatesIpAddresses;
use Roomies\Geolocatable\Result\Currency;
use Roomies\Geolocatable\Result\Geolocation;
use Roomies\Geolocatable\Result\Location;
use Roomies\Geolocatable\Result\Network;

class IpapiCo implements GeolocatesIpAddresses
{
    /**
     * Geolocate the given IP address.
     */
    public function ip(string $ipAddress): ?Geolocation
    {
        $response = Http::baseUrl('https://ipapi.co/')
            ->get("/{$ipAddress}/json");

        if (! $response->ok()) {
            return null;
        }

        return new Geolocation(
            source: 'ipapi.co',
            ipAddress: $response->json('ip'),
            location: new Location(
                continent: $response->json('continent_code'),
                country: $response->json('country_name'),
                countryIso: $response->json('country_code'),
                state: $response->json('region'),
                stateIso: $response->json('region_code'),
                city: $response->json('city'),
                postalCode: $response->json('postal'),
                latitude: $response->json('latitude'),
                longitude: $response->json('longitude'),
                timeZone: $response->json('timezone'),
            ),
            network: new Network(
                organization: $response->json('org'),
            ),
            currency: new Currency(
                name: $response->json('currency_name'),
                code: $response->json('currency'),
            ),
            raw: $response->json(),
        );
    }
}
