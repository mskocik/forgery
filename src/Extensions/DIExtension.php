<?php declare(strict_types=1);

namespace Mskocik\ForgeryDI\Extensions;

use Nette;
use Mskocik\ForgeryDI\Container;

class DIExtension extends Nette\DI\CompilerExtension
{
	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		parent::afterCompile($class);
		$class->setExtends(Container::class);
		$constructor = $class->getMethod('__construct');
		$constructor->addBody('$this->initAuryn();');
	}
}