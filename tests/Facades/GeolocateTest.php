<?php

namespace Roomies\Geolocatable\Tests\Facades;

use Roomies\Geolocatable\Facades\Geolocate;
use Roomies\Geolocatable\GeolocationFake;
use Roomies\Geolocatable\Tests\TestCase;

class GeolocateTest extends TestCase
{
    public function test_it_returns_instance_of_fake()
    {
        $result = Geolocate::fake();

        $this->assertInstanceOf(GeolocationFake::class, $result);
    }
}
