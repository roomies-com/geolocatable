<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Services;

use Illuminate\Support\Facades\Http;
use Roomies\Geolocatable\GeolocatesIpAddresses;
use Roomies\Geolocatable\Result\Currency;
use Roomies\Geolocatable\Result\Geolocation;
use Roomies\Geolocatable\Result\Location;
use Roomies\Geolocatable\Result\Network;

class Ipdata implements GeolocatesIpAddresses
{
    /**
     * Create a new instance.
     */
    public function __construct(protected string $apiKey)
    {
        //
    }

    /**
     * Geolocate the given IP address.
     */
    public function ip(string $ipAddress): ?Geolocation
    {
        $response = Http::baseUrl('https://api.ipdata.co')
            ->get("/{$ipAddress}", [
                'api-key' => $this->apiKey,
            ]);

        if (! $response->ok()) {
            return null;
        }

        return new Geolocation(
            source: 'ipdata',
            ipAddress: $response->json('ip'),
            location: new Location(
                continent: $response->json('continent_name'),
                country: $response->json('country_name'),
                countryIso: $response->json('country_code'),
                state: $response->json('region'),
                stateIso: $response->json('region_code'),
                city: $response->json('city'),
                postalCode: $response->json('postal'),
                latitude: $response->json('latitude'),
                longitude: $response->json('longitude'),
                timeZone: $response->json('time_zone.name'),
            ),
            network: new Network(
                isp: $response->json('asn.name'),
                domain: $response->json('asn.domain'),
                organization: $response->json('company.name'),
                isProxy: $response->json('threat.is_proxy'),
                isHosting: $response->json('threat.is_datacenter'),
                isAnonymous: $response->json('threat.is_anonymous')
            ),
            currency: new Currency(
                name: $response->json('currency.name'),
                code: $response->json('currency.code'),
                symbol: $response->json('currency.symbol')
            ),
            raw: $response->json(),
        );
    }
}
