<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Tests\Services;

use Illuminate\Support\Facades\Http;
use Roomies\Geolocatable\Services\Ipdata;
use Roomies\Geolocatable\Tests\TestCase;

class IpdataTest extends TestCase
{
    public function test_it_fetches_ip_address_metadata(): void
    {
        Http::fake([
            'api.ipdata.co/*' => $this->getResponse(),
        ]);

        $result = (new Ipdata('apiKey'))->ip('185.246.209.154');

        $this->assertEquals('185.246.209.154', $result->ipAddress);
        $this->assertEquals('Chicago', $result->location->city);
        $this->assertEquals('Cdn77 Chicago Pop', $result->network->organization);
        $this->assertEquals('$', $result->currency->symbol);
    }

    public function test_it_handles_failures(): void
    {
        Http::fake([
            'api.ipdata.co/*' => 400,
        ]);

        $result = (new Ipdata('apiKey'))->ip('185.246.209.154');

        $this->assertNull($result);
    }

    protected function getResponse()
    {
        return [
            'ip' => '185.246.209.154',
            'is_eu' => false,
            'city' => 'Chicago',
            'region' => 'Illinois',
            'region_code' => 'IL',
            'region_type' => 'state',
            'country_name' => 'United States',
            'country_code' => 'US',
            'continent_name' => 'North America',
            'continent_code' => 'NA',
            'latitude' => 41.870998382568,
            'longitude' => -87.628898620605,
            'postal' => '60605',
            'calling_code' => '1',
            'flag' => 'https://ipdata.co/flags/us.png',
            'emoji_flag' => '🇺🇸',
            'emoji_unicode' => 'U+1F1FA U+1F1F8',
            'asn' => [
                'asn' => 'AS60068',
                'name' => 'Datacamp Limited',
                'domain' => 'datacamp.co.uk',
                'route' => '185.246.209.0/24',
                'type' => 'business',
            ],
            'company' => [
                'name' => 'Cdn77 Chicago Pop',
                'domain' => '',
                'network' => '185.246.209.128/26',
                'type' => 'business',
            ],
            'languages' => [
                [
                    'name' => 'English',
                    'native' => 'English',
                    'code' => 'en',
                ],
            ],
            'currency' => [
                'name' => 'US Dollar',
                'code' => 'USD',
                'symbol' => '$',
                'native' => '$',
                'plural' => 'US dollars',
            ],
            'time_zone' => [
                'name' => 'America/Chicago',
                'abbr' => 'CST',
                'offset' => '-0600',
                'is_dst' => false,
                'current_time' => '2024-12-09T23:07:49-06:00',
            ],
            'threat' => [
                'is_tor' => false,
                'is_icloud_relay' => false,
                'is_proxy' => false,
                'is_datacenter' => false,
                'is_anonymous' => false,
                'is_known_attacker' => true,
                'is_known_abuser' => true,
                'is_threat' => true,
                'is_bogon' => false,
                'blocklists' => [
                    [
                        'name' => 'Wikimedia Global Blocklist',
                        'site' => 'https://wikimedia.org',
                        'type' => 'general',
                    ],
                ],
            ],
            'count' => '0',
        ];
    }
}
