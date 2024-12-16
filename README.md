# Roomies Geocodable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/roomies/geocodable.svg?style=flat-square)](https://packagist.org/packages/roomies/geocodable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/roomies-com/geocodable/test.yml?branch=main&label=tests&style=flat-square)](https://github.com/roomies-com/geocodable/actions?query=workflow%3Atest+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/roomies/geocodable.svg?style=flat-square)](https://packagist.org/packages/roomies/geocodable)

Determine the geographical location, currency and network information of website users based on their IP addresses.

Geocodable is an abstraction over multiple IP geocoding services including [Cloudflare](https://www.cloudflare.com), [Ip2Location](https://www.ip2location.io), [ipapi.co](https://ipapi.co) [ipapi.com](https://ipapi.com), [ip-api.com](https://ip-api.com), [ipdata](https://ipdata.co), [Maxmind GeoIP database and web services](https://www.maxmind.com/en/home).

It's based off of [`laravel-geoip`](https://github.com/Torann/laravel-geoip), but there are some key differences that may affect your decision:
* it allows you to use multiple providers with different configurations,
* it doesn't implement caching behaviour,
* it doesn't implement a fallback/default location.

## Installation

You can install the package via Composer:

```bash
composer require roomies/geolocatable
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="geolocatable-config"
```

Read through the config file to understand the supported services and provide the correct configuration for your preferred services.

## Getting started

You can perform an IP address geolcaton using the Facade.

```php
use Roomies\Geocodable\Facades\Geocode;

// Returns an instance of \Roomies\Geolocatable\Result\Geolocation
$result = Geolocate::ip('129.168.0.1');

echo "Looks like you are in {$result->country}, roughly around {$result->latitude}, {$result->longitude}.";
```

Depending on the provider you choose different combinations of information will be available. It will also depend specifically on the IP address itself and what data the provider has. However, Geolocatable returns a typed readonly class that provides access to structured attributes as well as the raw result if you require it.

* IP address
* location: `Roomies\Geolocatable\Result\Location`
  * continent name
  * country name and country ISO
  * state name and state ISO
  * city name
  * postal code
  * latitude
  * longitude
  * timezone
* network: `Roomies\Geolocatable\Result\Network`
  * organization
  * ISP
  * domain
  * is a proxy network
  * is a mobile network
  * is a hosting network
  * is an anonymous network
* currency: `Roomies\Geolocatable\Result\Currency`
  * name
  * code
  * symbol
* raw: the raw result from the geolocation provider

You can also change the provider you use on the fly.

```php
Geolocate::using('maxmind_database')->ip('192.168.0.1');
```

### Cloudflare

If you have Cloudflare in front of your website you can [`add visitor location headers`](https://developers.cloudflare.com/network/ip-geolocation/#add-ip-geolocation-information) to your app using a managed transform. It doesn't have as much information as other providers and it can only be used for the IP address that makes the request, but it is a great free option if you don't need greater detail.

### [Ip2Location](https://www.ip2location.io)

Ip2Location is a freemium service that starts at 30,000 lookups/month for free.

Check the config file or provide your `IP2LOCATION_KEY` in the environment.

### [ipapi.co](https://ipapi.co)

ipapi is a freemium service.

### [ipapi.com](https://ipapi.com)

Proivide your `IPAPI_KEY` API key to use this API.

### [ip-api.com](https://ip-api.com)

ip-api is a freemium service.

### [ipdata](https://ipdata.co)

Provide your `IPDATA_KEY` API key to use this API.

### Maxmind Database

Provide your Maxmind `account_id` and `license_key` to download the Maxmind database.

Once configured you need to install the `geoip2` dependency and then download the database.

```sh
composer require geoip2/geoip2
php artisan geolocatable:download
```

You can choose to update the database on your own schedule.

### Maxmind Web Services

Provide your Maxmind `account_id` and `license_key` to use Maxmind Web Services.

By default it will use the `city` level for IP address details but you can control this.

## Testing

Geolocatable includes a fake which makes it easy for you to test how your app handles different results. It allows you to stub the res

```php
$result = new Roomies\Geolocatable\Result\Geolocation(
    source: 'fake',
    ipAddress: '192.168.0.1',
    raw: []
);

Geocode::fake($result);
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
