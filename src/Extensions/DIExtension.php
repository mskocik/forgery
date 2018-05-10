<?php

namespace Mskocik\ForgeryDI\Extensions;

use Nette;
use Auryn\CachingReflector;
use Auryn\ReflectionCacheArray;
use Auryn\StandardReflector;
use Mskocik\ForgeryDI\Injector;

class DIExtension extends Nette\DI\CompilerExtension
{
	private $defaults = [
		'params' => [],
		'classes' => [],
		'aliases' => [],
		'appendParams' => true
	];

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$config = $this->getConfig($this->defaults);

		$reflector = $builder->addDefinition($this->prefix('reflector'))
			->setClass(StandardReflector::class);
		$cache = $builder->addDefinition($this->prefix('cache'))
			->setClass(ReflectionCacheArray::class);
		$reflector = $builder->addDefinition($this->prefix('caching'))
			->setFactory(CachingReflector::class, [
				$reflector, $cache
			]);
	}

	public function afterCompile(\Nette\PhpGenerator\ClassType $class)
	{
		$constructor = $class->getMethod('__construct');
		$constructor->addBody('$this->initAuryn($this->getService("'.$this->prefix('caching').'"));');
		// $constructor->addBody('$this->initAuryn($this->getByName("forgery.reflector"));');
		$constructor->addBody('static::$self = $this;');
		parent::afterCompile($class);
	}
}