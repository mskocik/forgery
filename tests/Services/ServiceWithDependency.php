<?php declare(strict_types=1);

namespace Tests\Services;

class ServiceWithDependency
{
    public function __construct(public SimpleService $child)
    {
    }
}