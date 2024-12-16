<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Services;

use Illuminate\Support\Facades\Http;
use Roomies\Geolocatable\GeolocatesIpAddresses;
use Roomies\Geolocatable\Result\Currency;
use Roomies\Geolocatable\Result\Geolocation;
use Roomies\Geolocatable\Result\Location;
use Roomies\Geolocatable\Result\Network;

class IpapiCom implements GeolocatesIpAddresses
{
    /**
     * Create a new instance.
     */
    public function __construct(protected string $accessKey)
    {
        //
    }

    /**
     * Geolocate the given IP address.
     */
    public function ip(string $ipAddress): ?Geolocation
    {
        $response = Http::baseUrl('https://api.ipapi.com/api')
            ->get("/{$ipAddress}", [
                'access_key' => $this->accessKey,
            ]);

        if (! $response->ok()) {
            return null;
        }

        return new Geolocation(
            source: 'ipapi.com',
            ipAddress: $response->json('ip'),
            location: new Location(
                continent: $response->json('continent_name'),
                country: $response->json('country_name'),
                countryIso: $response->json('country_code'),
                state: $response->json('region_name'),
                stateIso: $response->json('region_code'),
                city: $response->json('city'),
                postalCode: $response->json('zip'),
                latitude: $response->json('latitude'),
                longitude: $response->json('longitude'),
                timeZone: $response->json('time_zone.id'),
            ),
            network: new Network(
                isp: $response->json('connection.isp'),
                organization: $response->json('connection.carrier'),
                isProxy: $response->json('security.is_proxy'),
                isAnonymous: $response->json('security.anonymizer_status'),
                isHosting: $response->json('security.hosting_facility'),
            ),
            currency: new Currency(
                name: $response->json('currency.name'),
                code: $response->json('currency.code'),
                symbol: $response->json('currency.symbol'),
            ),
            raw: $response->json(),
        );
    }
}
