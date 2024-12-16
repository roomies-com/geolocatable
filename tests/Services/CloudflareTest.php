<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Tests\Services;

use Illuminate\Http\Request;
use Roomies\Geolocatable\Services\Cloudflare;
use Roomies\Geolocatable\Tests\TestCase;

class CloudflareTest extends TestCase
{
    public function test_it_fetches_ip_address_metadata(): void
    {
        $request = Request::create(uri: '/', server: [
            'HTTP_CF_IPCITY' => 'San Francisco',
            'HTTP_CF_IPCOUNTRY' => 'GB',
            'HTTP_CF_IPCONTINENT' => 'EU',
            'HTTP_CF_LONGITUDE' => '-122.39055',
            'HTTP_CF_LATITUDE' => '37.78044',
            'HTTP_CF_REGION' => 'Texas',
            'HTTP_CF_REGION_CODE' => 'TX',
            'HTTP_CF_POSTAL_CODE' => '70801',
            'HTTP_CF_TIMEZONE' => 'America/Chicago',
        ]);

        $result = (new Cloudflare($request))->ip('127.0.0.1');

        $this->assertEquals('127.0.0.1', $result->ipAddress);
        $this->assertEquals('San Francisco', $result->location->city);
    }

    public function test_it_handles_failures(): void
    {
        $request = Request::create(uri: '/');

        $result = (new Cloudflare($request))->ip('127.0.0.2');

        $this->assertNull($result);
    }
}
