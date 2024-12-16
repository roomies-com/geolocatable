<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Tests\Services;

use GeoIp2\Database\Reader;
use GeoIp2\Model\City;
use Mockery;
use Roomies\Geolocatable\Services\MaxmindDatabase;
use Roomies\Geolocatable\Tests\TestCase;

class MaxmindDatabaseTest extends TestCase
{
    public function test_it_fetches_ip_address_metadata()
    {
        $database = Mockery::mock(Reader::class);

        $database->shouldReceive('city')->andReturn($this->getResponse());

        $result = (new MaxmindDatabase($database))->ip('62.106.78.154');

        $this->assertEquals('62.106.78.154', $result->ipAddress);
        $this->assertEquals('Atlanta', $result->location->city);
    }

    protected function getResponse()
    {
        return new City([
            'city' => [
                'geoname_id' => 4180439,
                'names' => [
                    'de' => 'Atlanta',
                    'en' => 'Atlanta',
                    'es' => 'Atlanta',
                    'fr' => 'Atlanta',
                    'ja' => 'アトランタ',
                    'pt-BR' => 'Atlanta',
                    'ru' => 'Атланта',
                    'zh-CN' => '亚特兰大',
                ],
            ],
            'continent' => [
                'code' => 'NA',
                'geoname_id' => 6255149,
                'names' => [
                    'de' => 'Nordamerika',
                    'en' => 'North America',
                    'es' => 'Norteamérica',
                    'fr' => 'Amérique du Nord',
                    'ja' => '北アメリカ',
                    'pt-BR' => 'América do Norte',
                    'ru' => 'Северная Америка',
                    'zh-CN' => '北美洲',
                ],
            ],
            'country' => [
                'geoname_id' => 6252001,
                'iso_code' => 'US',
                'names' => [
                    'de' => 'USA',
                    'en' => 'United States',
                    'es' => 'Estados Unidos',
                    'fr' => 'États Unis',
                    'ja' => 'アメリカ',
                    'pt-BR' => 'EUA',
                    'ru' => 'США',
                    'zh-CN' => '美国',
                ],
            ],
            'location' => [
                'accuracy_radius' => 20,
                'latitude' => 33.7485,
                'longitude' => -84.3871,
                'metro_code' => 524,
                'time_zone' => 'America/New_York',
            ],
            'postal' => [
                'code' => '30303',
            ],
            'registered_country' => [
                'geoname_id' => 2635167,
                'iso_code' => 'GB',
                'names' => [
                    'de' => 'UK',
                    'en' => 'United Kingdom',
                    'es' => 'Reino Unido',
                    'fr' => 'Royaume-Uni',
                    'ja' => '英国',
                    'pt-BR' => 'Reino Unido',
                    'ru' => 'Британия',
                    'zh-CN' => '英国',
                ],
            ],
            'subdivisions' => [
                [
                    'geoname_id' => 4197000,
                    'iso_code' => 'GA',
                    'names' => [
                        'de' => 'Georgia',
                        'en' => 'Georgia',
                        'es' => 'Georgia',
                        'fr' => 'Géorgie',
                        'ja' => 'ジョージア州',
                        'pt-BR' => 'Geórgia',
                        'ru' => 'Джорджия',
                        'zh-CN' => '乔治亚',
                    ],
                ],
            ],
            'traits' => [
                'ip_address' => '62.106.78.154',
                'prefix_len' => 24,
            ],
        ]);
    }
}
