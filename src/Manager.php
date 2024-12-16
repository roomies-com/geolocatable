<?php

namespace Roomies\Geolocatable;

use GeoIp2\Database\Reader;
use Illuminate\Support\MultipleInstanceManager;

class Manager extends MultipleInstanceManager
{
    /**
     * The name of the default instance.
     */
    protected ?string $defaultInstance;

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultInstance()
    {
        return $this->defaultInstance
            ?? $this->app['config']->get('geolocatable.default');
    }

    /**
     * Set the default instance name.
     *
     * @param  string  $name
     * @return void
     */
    public function setDefaultInstance($name)
    {
        $this->defaultInstance = $name;
    }

    /**
     * Get the instance specific configuration.
     *
     * @param  string  $name
     * @return array
     */
    public function getInstanceConfig($name)
    {
        return $this->app['config']->get("geolocatable.services.{$name}");
    }

    /**
     * Create an instance of the ip-api.co driver.
     */
    public function createCloudflareDriver(array $config): Services\Cloudflare
    {
        return new Services\Cloudflare;
    }

    /**
     * Create an instance of the ip-api.co driver.
     */
    public function createIp2LocationDriver(array $config): Services\Ip2Location
    {
        return new Services\Ip2Location($config['key']);
    }

    /**
     * Create an instance of the ipapi.co driver.
     */
    public function createIpapiCoDriver(array $config): Services\IpapiCo
    {
        return new Services\IpapiCo;
    }

    /**
     * Create an instance of the ipapi.com driver.
     */
    public function createIpapiComDriver(array $config): Services\IpapiCom
    {
        return new Services\IpapiCom($config['key']);
    }

    /**
     * Create an instance of the ip-api.com driver.
     */
    public function createIpDashApiDriver(array $config): Services\IpDashApi
    {
        return new Services\IpDashApi;
    }

    /**
     * Create an instance of the ipdata.com driver.
     */
    public function createIpdataDriver(array $config): Services\Ipdata
    {
        return new Services\Ipdata(
            $config['key']
        );
    }

    /**
     * Create an instance of the Maxmind database driver.
     */
    public function createMaxmindDatabaseDriver(array $config): Services\MaxmindDatabase
    {
        $reader = new Reader(storage_path($config['path']));

        return new Services\MaxmindDatabase($reader);
    }

    /**
     * Create an instance of the Maxmind GeoIP2 driver.
     */
    public function createMaxmindWebDriver(array $config): Services\MaxmindWeb
    {
        return new Services\MaxmindWeb(
            $config['account_id'], $config['license_key'], $config['level']
        );
    }
}
