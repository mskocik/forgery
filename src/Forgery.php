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
        if (isset($args[Container::FORGERY_DEFINE])) {
            $this->aurynContainer->addAurynDefinition($className, $args[Container::FORGERY_DEFINE]);
            unset($args[Container::FORGERY_DEFINE]);
        }
        if (isset($args[Container::FORGERY_ALIAS])) {
            $this->aurynContainer->addAurynAlias($className, $args[Container::FORGERY_ALIAS]);
            unset($args[Container::FORGERY_ALIAS]);
        }
        return $this->aurynContainer->make($className, $args, $type);
    }

    protected function defineForgeryParam(string $paramName, $value): void
    {
        $this->aurynContainer->defineAurynParam($paramName, $value);
    }

    protected function defineForgeryDelegate(string $paramName, $value): void
    {
        $this->aurynContainer->defineAurynDelegate($paramName, $value);
    }

    public function injectAuryn(NetteContainer $container): void
    {
        $this->aurynContainer = $container;
    }
}
