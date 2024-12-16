<?php

namespace Roomies\Geolocatable\Tests\Testing;

use Roomies\Geolocatable\GeolocationFake;
use Roomies\Geolocatable\Result\Geolocation;
use Roomies\Geolocatable\Result\Location;
use Roomies\Geolocatable\Result\Network;
use Roomies\Geolocatable\Tests\TestCase;

class GeolocationFakeTest extends TestCase
{
    public function test_faking_result()
    {
        $fake = new GeolocationFake(
            new Geolocation(
                source: 'test',
                ipAddress: '192.168.0.1',
                location: new Location,
                network: new Network,
                raw: null,
            ),
        );

        $result = $fake->ip('192.168.0.1');

        $this->assertInstanceOf(Geolocation::class, $result);
    }
}
