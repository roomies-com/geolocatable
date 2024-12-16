<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Result;

readonly class Location
{
    public function __construct(
        public ?string $continent = null,
        public ?string $country = null,
        public ?string $countryIso = null,
        public ?string $state = null,
        public ?string $stateIso = null,
        public ?string $city = null,
        public ?string $postalCode = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?string $timeZone = null,
    ) {
        //
    }
}
