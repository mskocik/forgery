<?php declare(strict_types=1);

namespace Tests\Services;

class ServiceWithNonClassDeps
{
    public function __construct(public int $requiredInt, public string $requiredString)
    {}
}