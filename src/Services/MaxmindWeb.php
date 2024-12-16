<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Services;

use Illuminate\Support\Facades\Http;
use Roomies\Geolocatable\GeolocatesIpAddresses;
use Roomies\Geolocatable\Result\Geolocation;
use Roomies\Geolocatable\Result\Location;
use Roomies\Geolocatable\Result\Network;

class MaxmindWeb implements GeolocatesIpAddresses
{
    /**
     * Create a new instance.
     */
    public function __construct(
        protected string $accountId,
        protected string $licenseKey,
        protected string $level
    ) {
        //
    }

    /**
     * Geolocate the given IP address.
     */
    public function ip(string $ipAddress): ?Geolocation
    {
        $response = Http::baseUrl('https://geoip.maxmind.com/geoip/v2.1')
            ->withBasicAuth($this->accountId, $this->licenseKey)
            ->get("/{$this->level}/{$ipAddress}");

        if (! $response->ok()) {
            return null;
        }

        return new Geolocation(
            source: 'maxmind-geoip',
            ipAddress: $response->json('traits.ip_address'),
            location: new Location(
                continent: $response->json('continent.names.en'),
                country: $response->json('country.names.en'),
                countryIso: $response->json('country.iso_code'),
                state: $response->json('subdivisions.0.names.en'),
                stateIso: $response->json('subdivisions.0.iso_code'),
                city: $response->json('city.names.en'),
                postalCode: $response->json('postal.code'),
                latitude: $response->json('location.latitude'),
                longitude: $response->json('location.longitude'),
                timeZone: $response->json('location.time_zone'),
            ),
            network: new Network(
                domain: $response->json('traits.domain'),
                isp: $response->json('traits.isp'),
                organization: $response->json('traits.organization'),
                isHosting: $response->json('traits.is_hosting'),
                isAnonymous: $response->json('traits.is_anonymous'),
            ),
            raw: $response->json(),
        );
    }
}
