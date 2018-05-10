<?php

namespace Mskocik\ForgeryDI;

use Nette;
use Auryn\Reflector;

class Container extends Nette\DI\Container
{
    /** @var Injector */
	private $auryn; 
	
	protected static $self = null;

    public function initAuryn(Reflector $reflector = null)
    {   
        // TODO: create CacheReflector
        $this->auryn = new Injector($reflector, $this);
    }

    public function make($className, $args, $type)
    {
        if ($type === Instance::SHARED) {
            $this->auryn->share($className);
        }
        return $this->auryn->make($className, $args);
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

	/**
	 * Available for trait access
	 *
	 * @return void
	 */
	public static function getInstance() {
		
		if (!(func_num_args() > 0) && func_get_arg(0) !== Forgery::class) {
			throw new \DomainException('Trying to get container instance outside allowed scope - through Forgery Trait');
		}
		return static::$self;
	}
}
