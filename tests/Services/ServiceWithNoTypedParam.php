<?php declare(strict_types=1);

namespace Tests\Services;

class ServiceWithNoTypedParam
{
    public function __construct(public $customParam)
    {
        
    }
}