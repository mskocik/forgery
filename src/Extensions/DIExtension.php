<?php

namespace Mskocik\Forgery\Extensions;

use Nette;

class DIExtension extends Nette\DI\CompilerExtension
{
	public function afterCompile(\Nette\PhpGenerator\ClassType $class)
	{
		$constructor = $class->getMethod('__construct');
		$constructor->addBody('static::$self = $this;');
		parent::afterCompile($class);
	}
}