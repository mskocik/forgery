<?php declare(strict_types=1);

namespace Mskocik\ForgeryDI;

use Nette;

class Container extends Nette\DI\Container
{
    const
        INSTANCE_UNIQUE = 0,
        INSTANCE_SHARED = 1;

    const
        FORGERY_DEFINE = 'forgery_define',
        FORGERY_ALIAS = 'forgery_alias';

    /** @var Injector */
    private $auryn; 
    
    public function initAuryn()
    {   
        // TODO: create CacheReflector
        $this->auryn = new Injector($this, null);
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

    public function defineAurynDelegate(string $className, $callable): void
    {
        $this->auryn->delegate($className, $callable);
    }

    public function addAurynDefinition(string $className, array $definitions): void
    {
        $this->auryn->define($className, $definitions);
    }

    public function addAurynAlias(string $interface, string $implClass): void 
    {
        $this->auryn->alias($interface, $implClass);
    }

    /**
     * OVERRIDING default implementation using Auryn DI
     * 
     * Creates new instance using autowiring.
     * @param  string  class
     * @param  array   arguments
     */
    public function createInstance($class, array $args = []): object
    {
        return $this->auryn->make($class, $args);
    }
}
