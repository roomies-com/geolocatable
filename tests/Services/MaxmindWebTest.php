<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Tests\Services;

use Illuminate\Support\Facades\Http;
use Roomies\Geolocatable\Services\MaxmindWeb;
use Roomies\Geolocatable\Tests\TestCase;

class MaxmindWebTest extends TestCase
{
    public function test_it_fetches_ip_address_metadata()
    {
        Http::fake([
            'geoip.maxmind.com/*' => $this->getResponse(),
        ]);

        $result = (new MaxmindWeb('accountId', 'licenseKey', 'city'))->ip('127.0.0.1');

        $this->assertEquals('127.0.0.1', $result->ipAddress);
        $this->assertEquals('Sydney', $result->location->city);
        $this->assertEquals('TPG Internet', $result->network->isp);
    }

    public function test_it_handles_failures(): void
    {
        Http::fake([
            'geoip.maxmind.com/*' => 400,
        ]);

        $result = (new MaxmindWeb('accountId', 'licenseKey', 'city'))->ip('127.0.0.1');

        $this->assertNull($result);
    }

    protected function getResponse()
    {
        return [
            'city' => ['names' => ['en' => 'Sydney']],
            'continent' => ['names' => ['en' => 'Oceania']],
            'country' => [
                'iso_code' => 'AU',
                'names' => ['en' => 'Australia'],
            ],
            'location' => [
                'latitude' => -33.8612,
                'longitude' => 151.1982,
                'time_zone' => 'Australia/Sydney',
            ],
            'postal' => [
                'code' => '2015',
            ],
            'subdivisions' => [
                [
                    'iso_code' => 'NSW',
                    'names' => ['en' => 'New South Wales'],
                ],
            ],
            'traits' => [
                'domain' => 'tpgi.com.au',
                'isp' => 'TPG Internet',
                'organization' => 'TPG Internet',
                'ip_address' => '127.0.0.1',
            ],
        ];
    }
}
