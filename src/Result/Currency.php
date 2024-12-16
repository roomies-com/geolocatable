<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Result;

readonly class Currency
{
    public function __construct(
        public ?string $name = null,
        public ?string $code = null,
        public ?string $symbol = null,
    ) {
        //
    }
}
