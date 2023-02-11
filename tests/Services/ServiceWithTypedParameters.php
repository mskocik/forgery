<?php declare(strict_types=1);

namespace Tests\Services;

class ServiceWithTypedParameters implements ServiceContract
{
    public function __construct(public int $requiredInt, public string $requiredString)
    {}
}