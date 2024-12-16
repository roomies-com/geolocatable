<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Tests\Services;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Roomies\Geolocatable\Services\IpDashApi;
use Roomies\Geolocatable\Tests\TestCase;

class IpDashApiTest extends TestCase
{
    public function test_it_fetches_ip_address_metadata()
    {
        Http::fake([
            'ip-api.com/*' => $this->getResponse(),
        ]);

        $result = (new IpDashApi)->ip('185.246.209.154');

        $this->assertEquals('185.246.209.154', $result->ipAddress);
        $this->assertEquals('Chicago', $result->location->city);
        $this->assertEquals('CDN77 - Chicago POP II', $result->network->isp);
    }

    public function test_it_handles_failures(): void
    {
        Http::fake([
            'ip-api.com/*' => 400,
        ]);

        $result = (new IpDashApi)->ip('185.246.209.154');

        $this->assertNull($result);
    }

    public function test_it_handles_invalid_requests(): void
    {
        Http::fake([
            'ip-api.com/*' => [
                'status' => 'fail',
                'message' => 'private range',
            ],
        ]);

        $this->expectException(InvalidArgumentException::class);

        $result = (new IpDashApi)->ip('185.246.209.154');
    }

    protected function getResponse()
    {
        return [
            'status' => 'success',
            'continent' => 'North America',
            'country' => 'United States',
            'countryCode' => 'US',
            'region' => 'IL',
            'regionName' => 'Illinois',
            'city' => 'Chicago',
            'zip' => '60605',
            'lat' => 41.871,
            'lon' => -87.6289,
            'timezone' => 'America/Chicago',
            'isp' => 'CDN77 - Chicago POP II',
            'org' => 'Overkill Beta s.r.o',
            'mobile' => false,
            'proxy' => true,
            'hosting' => true,
            'query' => '185.246.209.154',
        ];
    }
}
