<?php declare(strict_types=1);

namespace Tests\Services;

class ServiceWithOptionalDependency
{
    public function __construct(private ?SimpleService $child = null)
    {}

    public function getChildService(): ?SimpleService
    {
        return $this->child;
    }
}
