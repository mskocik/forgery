<?php declare(strict_types=1);

namespace Tests\Services;

use Tests\Services\ServiceWithOptionalDependency;

class ServiceWithRequiredDependency
{
    public function __construct(
        public ServiceWithDependency $autoCreated,
        public ServiceContract $requiredContract,
        public ?ServiceWithOptionalDependency $optional = null,
        public ?int $optionalParam = null
    ) {}
}