<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Tests\Services;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Roomies\Geolocatable\Services\Ip2Location;
use Roomies\Geolocatable\Tests\TestCase;

class Ip2LocationTest extends TestCase
{
    public function test_it_fetches_ip_address_metadata()
    {
        Http::fake([
            'api.ip2location.io/*' => $this->getResponse(),
        ]);

        $result = (new Ip2Location('key'))->ip('8.8.8.8');

        $this->assertEquals('8.8.8.8', $result->ipAddress);
        $this->assertEquals('Mountain View', $result->location->city);
        $this->assertEquals('Google LLC', $result->network->isp);
    }

    public function test_it_handles_failures(): void
    {
        Http::fake([
            'api.ip2location.io/*' => 400,
        ]);

        $result = (new Ip2Location('key'))->ip('8.8.8.8');

        $this->assertNull($result);
    }

    public function test_it_handles_invalid_requests(): void
    {
        Http::fake([
            'api.ip2location.io/*' => [
                'error' => [
                    'error_code' => 10001,
                    'error_message' => 'Invalid IP address.',
                ],
            ],
        ]);

        $this->expectException(InvalidArgumentException::class);

        $result = (new Ip2Location('key'))->ip('8.8.8.8');
    }

    protected function getResponse()
    {
        return $data = [
            'ip' => '8.8.8.8',
            'country_code' => 'US',
            'country_name' => 'United States of America',
            'region_name' => 'California',
            'city_name' => 'Mountain View',
            'latitude' => 37.405992,
            'longitude' => -122.078515,
            'zip_code' => '94043',
            'time_zone' => '-07:00',
            'asn' => '15169',
            'as' => 'Google LLC',
            'isp' => 'Google LLC',
            'domain' => 'google.com',
            'net_speed' => 'T1',
            'idd_code' => '1',
            'area_code' => '650',
            'weather_station_code' => 'USCA0746',
            'weather_station_name' => 'Mountain View',
            'mcc' => '-',
            'mnc' => '-',
            'mobile_brand' => '-',
            'elevation' => 32,
            'usage_type' => 'DCH',
            'address_type' => 'Anycast',
            'continent' => [
                'name' => 'North America',
                'code' => 'NA',
                'hemisphere' => ['north', 'west'],
                'translation' => [
                    'lang' => 'ko',
                    'value' => '북아메리카',
                ],
            ],
            'country' => [
                'name' => 'United States of America',
                'alpha3_code' => 'USA',
                'numeric_code' => 840,
                'demonym' => 'Americans',
                'flag' => 'https://cdn.ip2location.io/assets/img/flags/us.png',
                'capital' => 'Washington, D.C.',
                'total_area' => 9826675,
                'population' => 331002651,
                'currency' => [
                    'code' => 'USD',
                    'name' => 'United States Dollar',
                    'symbol' => '$',
                ],
                'language' => [
                    'code' => 'EN',
                    'name' => 'English',
                ],
                'tld' => 'us',
                'translation' => [
                    'lang' => 'ko',
                    'value' => '미국',
                ],
            ],
            'region' => [
                'name' => 'California',
                'code' => 'US-CA',
                'translation' => [
                    'lang' => 'ko',
                    'value' => '캘리포니아주',
                ],
            ],
            'city' => [
                'name' => 'Mountain View',
                'translation' => [
                    'lang' => null,
                    'value' => null,
                ],
            ],
            'time_zone_info' => [
                'olson' => 'America/Los_Angeles',
                'current_time' => '2022-04-18T23:41:57-07:00',
                'gmt_offset' => -25200,
                'is_dst' => true,
                'sunrise' => '06:27',
                'sunset' => '19:47',
            ],
            'geotargeting' => [
                'metro' => '807',
            ],
            'ads_category' => 'IAB19',
            'ads_category_name' => 'Technology & Computing',
            'district' => 'San Diego County',
            'is_proxy' => false,
            'fraud_score' => 0,
            'proxy' => [
                'last_seen' => 18,
                'proxy_type' => 'DCH',
                'threat' => '-',
                'provider' => '-',
                'is_vpn' => false,
                'is_tor' => false,
                'is_data_center' => true,
                'is_public_proxy' => false,
                'is_web_proxy' => false,
                'is_web_crawler' => false,
                'is_residential_proxy' => false,
                'is_consumer_privacy_network' => false,
                'is_enterprise_private_network' => false,
                'is_spammer' => false,
                'is_scanner' => false,
                'is_botnet' => false,
            ],
        ];
    }
}
