<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Result;

readonly class Geolocation
{
    /**
     * Create a new geolocation result instance.
     */
    public function __construct(
        public string $source,
        public string $ipAddress,
        public Location $location,
        public mixed $raw,
        public ?Network $network = null,
        public ?Currency $currency = null,
    ) {
        //
    }
}
