<?php

namespace Mskocik\ForgeryDI\Extensions;

use Nette;

class DIExtension extends Nette\DI\CompilerExtension
{
	public function afterCompile(\Nette\PhpGenerator\ClassType $class)
	{
		$constructor = $class->getMethod('__construct');
		$constructor->addBody('$this->initAuryn();');
		$constructor->addBody('static::$self = $this;');
		parent::afterCompile($class);
	}
}