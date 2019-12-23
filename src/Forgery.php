<?php declare(strict_types=1);

namespace Mskocik\ForgeryDI;

use Nette\DI\Container as NetteContainer;

/**
 * Trait enabling dynamic object creation, 
 */
trait Forgery {

    /** @var NetteContainer */
    private $aurynContainer;

    protected function forge(string $className, array $args = [], int $type = Container::INSTANCE_SHARED)
    {
        return $this->aurynContainer->make($className, $args, $type);
    }

    public function injectAuryn(NetteContainer $container): void
    {
        $this->aurynContainer = $container;
    }
}
