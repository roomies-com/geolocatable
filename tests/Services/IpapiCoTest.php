<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Tests\Services;

use Illuminate\Support\Facades\Http;
use Roomies\Geolocatable\Services\IpApiCo;
use Roomies\Geolocatable\Tests\TestCase;

class IpApiCoTest extends TestCase
{
    public function test_it_fetches_ip_address_metadata()
    {
        Http::fake([
            'ipapi.co/*' => $this->getResponse(),
        ]);

        $result = (new IpApiCo)->ip('8.8.8.8');

        $this->assertEquals('8.8.8.8', $result->ipAddress);
        $this->assertEquals('Mountain View', $result->location->city);
        $this->assertEquals('Google LLC', $result->network->organization);
    }

    public function test_it_handles_failures(): void
    {
        Http::fake([
            'ipapi.co/*' => 400,
        ]);

        $result = (new IpApiCo)->ip('8.8.8.8');

        $this->assertNull($result);
    }

    protected function getResponse()
    {
        return [
            'ip' => '8.8.8.8',
            'version' => 'IPv4',
            'city' => 'Mountain View',
            'region' => 'California',
            'region_code' => 'CA',
            'country_code' => 'US',
            'country_code_iso3' => 'USA',
            'country_name' => 'United States',
            'country_capital' => 'Washington',
            'country_tld' => '.us',
            'continent_code' => 'NA',
            'in_eu' => false,
            'postal' => '94035',
            'latitude' => 37.386,
            'longitude' => -122.0838,
            'timezone' => 'America/Los_Angeles',
            'utc_offset' => '-0800',
            'country_calling_code' => '+1',
            'currency' => 'USD',
            'currency_name' => 'Dollar',
            'languages' => 'en-US,es-US,haw,fr',
            'country_area' => 9629091.0,
            'country_population' => 310232863,
            'asn' => 'AS15169',
            'org' => 'Google LLC',
            'hostname' => 'dns.google',
        ];
    }
}
