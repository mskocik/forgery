<?php declare(strict_types=1);

namespace Mskocik\ForgeryDI;

use Nette;

class Container extends Nette\DI\Container
{
    const
        INSTANCE_UNIQUE = 0,
        INSTANCE_SHARED = 1;

    /** @var Injector */
    private $auryn; 
    
    public function initAuryn()
    {   
        // TODO: create CacheReflector
        $this->auryn = new Injector(null, $this);
    }

    public function make($className, $args, $type)
    {
        if ($type === self::INSTANCE_SHARED) {
            $this->auryn->share($className);
        }
        return $this->auryn->make($className, $args);
    }

    public function defineAurynParam(string $paramName, $paramValue): void 
    {
        $this->auryn->defineParam($paramName, $paramValue);
    }

    /**
     * OVERRIDING default implementation using Auryn DI
     * 
     * Creates new instance using autowiring.
     * @param  string  class
     * @param  array   arguments
     * @return object
     */
    public function createInstance($class, array $args = [])
    {
        return $this->auryn->make($class, $args);
    }
}
