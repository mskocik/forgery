<?php

namespace Mskocik\ForgeryDI;

use Core\Utils\InstanceEnum;

/**
 * Trait enabling dynamic object creation, 
 * 
 * NOTE: propably violating DI principle
 */
trait Forgery {

	protected function forge(string $className, array $args = [], int $type = InstanceEnum::UNIQUE)
	{
		return Container::getInstance(__TRAIT__)->make($className, $args, $type);
	}
}
