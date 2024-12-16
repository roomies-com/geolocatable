<?php

declare(strict_types=1);

namespace Roomies\Geolocatable\Result;

readonly class Network
{
    public function __construct(
        public ?string $organization = null,
        public ?string $isp = null,
        public ?string $domain = null,
        public ?bool $isProxy = null,
        public ?bool $isMobile = null,
        public ?bool $isHosting = null,
        public ?bool $isAnonymous = null,
    ) {
        //
    }
}
