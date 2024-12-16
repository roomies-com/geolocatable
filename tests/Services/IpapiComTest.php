<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Tests\Services;

use Illuminate\Support\Facades\Http;
use Roomies\Geolocatable\Services\IpApiCom;
use Roomies\Geolocatable\Tests\TestCase;

class IpApiComTest extends TestCase
{
    public function test_it_fetches_ip_address_metadata(): void
    {
        Http::fake([
            'api.ipapi.com/*' => $this->getResponse(),
        ]);

        $result = (new IpApiCom('accessKey'))->ip('161.185.160.93');

        $this->assertEquals('161.185.160.93', $result->ipAddress);
        $this->assertEquals('Bath Beach', $result->location->city);
        $this->assertEquals('the city of new york', $result->network->organization);
        $this->assertEquals('$', $result->currency->symbol);
    }

    public function test_it_handles_failures(): void
    {
        Http::fake([
            'api.ipapi.com/*' => 400,
        ]);

        $result = (new IpApiCom('accessKey'))->ip('161.185.160.93');

        $this->assertNull($result);
    }

    protected function getResponse()
    {
        return [
            'ip' => '161.185.160.93',
            'type' => 'ipv4',
            'continent_code' => 'NA',
            'continent_name' => 'North America',
            'country_code' => 'US',
            'country_name' => 'United States',
            'region_code' => 'NY',
            'region_name' => 'New York',
            'city' => 'Bath Beach',
            'zip' => '11201',
            'latitude' => 40.69459915161133,
            'longitude' => -73.99063873291016,
            'msa' => '35620',
            'dma' => '501',
            'radius' => 30.012950897216797,
            'ip_routing_type' => 'fixed',
            'connection_type' => 'tx',
            'location' => [
                'geoname_id' => 5108111,
                'capital' => 'Washington D.C.',
                'languages' => [
                    [
                        'code' => 'en',
                        'name' => 'English',
                        'native' => 'English',
                    ],
                ],
                'country_flag' => 'http://assets.ipapi.com/flags/us.svg',
                'country_flag_emoji' => '🇺🇸',
                'country_flag_emoji_unicode' => 'U+1F1FA U+1F1F8',
                'calling_code' => '1',
                'is_eu' => false,
            ],
            'time_zone' => [
                'id' => 'America/New_York',
                'current_time' => '2018-09-24T05:07:10-04:00',
                'gmt_offset' => -14400,
                'code' => 'EDT',
                'is_daylight_saving' => true,
            ],
            'currency' => [
                'code' => 'USD',
                'name' => 'US Dollar',
                'plural' => 'US dollars',
                'symbol' => '$',
                'symbol_native' => '$',
            ],
            'connection' => [
                'asn' => 22252,
                'isp' => 'The City of New York',
                'sld' => 'nyc',
                'tld' => 'gov',
                'carrier' => 'the city of new york',
                'home' => false,
                'organization_type' => 'Government (Municipal)',
                'isic_code' => 'O8411',
                'naics_code' => '009211',
            ],
            'security' => [
                'is_proxy' => null,
                'proxy_type' => null,
                'is_crawler' => false,
                'crawler_name' => null,
                'crawler_type' => null,
                'is_tor' => false,
                'threat_level' => 'low',
                'threat_types' => null,
                'proxy_last_detected' => null,
                'proxy_level' => null,
                'vpn_service' => null,
                'anonymizer_status' => null,
                'hosting_facility' => false,
            ],
        ];
    }
}
