<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Services;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Roomies\Geolocatable\GeolocatesIpAddresses;
use Roomies\Geolocatable\Result\Geolocation;
use Roomies\Geolocatable\Result\Location;
use Roomies\Geolocatable\Result\Network;

class IpDashApi implements GeolocatesIpAddresses
{
    /**
     * Geolocate the given IP address.
     */
    public function ip(string $ipAddress): ?Geolocation
    {
        $response = Http::baseUrl('http://ip-api.com')
            ->get("/json/{$ipAddress}", [
                'fields' => 'query,continent,country,countryCode,regionName,region,city,zip,lat,lon,timezone,isp,org,mobile,proxy,hosting',
            ]);

        if (! $response->ok()) {
            return null;
        }

        if ($response->json('status') === 'fail') {
            throw new InvalidArgumentException($response->json('message'));
        }

        return new Geolocation(
            source: 'ip-api.co',
            ipAddress: $response->json('query'),
            location: new Location(
                continent: $response->json('continent'),
                country: $response->json('country'),
                countryIso: $response->json('countryCode'),
                state: $response->json('regionName'),
                stateIso: $response->json('region'),
                city: $response->json('city'),
                postalCode: $response->json('zip'),
                latitude: $response->json('lat'),
                longitude: $response->json('lon'),
                timeZone: $response->json('timezone'),
            ),
            network: new Network(
                isp: $response->json('isp'),
                organization: $response->json('org'),
                isProxy: $response->json('proxy'),
                isMobile: $response->json('mobile'),
                isHosting: $response->json('hosting'),
            ),
            raw: $response->json(),
        );
    }
}
