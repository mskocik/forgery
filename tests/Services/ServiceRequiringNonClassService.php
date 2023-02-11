<?php declare(strict_types=1);

namespace Tests\Services;

class ServiceRequiringNonClassService
{
    public function __construct(public ServiceWithNonClassDeps $myDep) 
    {}
}