<?php

namespace Mskocik\ForgeryDI;

/**
 * Trait enabling dynamic object creation, 
 * 
 * NOTE: propably violating DI principle
 */
trait Forgery {

	static

	protected function forge(string $className, array $args = [], int $type = Instance::UNIQUE)
	{
		return Container::getInstance(__TRAIT__)->make($className, $args, $type);
	}
}
