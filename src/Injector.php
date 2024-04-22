<?php declare(strict_types=1);

namespace Mskocik\ForgeryDI;

use Auryn;
use Auryn\Reflector;
use Nette\Utils\Arrays;

class Injector extends Auryn\Injector
{
    public function __construct(private Container $container, ?Reflector $reflector = null) 
    {
        parent::__construct($reflector);
        $this->container = $container;
        foreach ($container->getParameters() as $key => $value) {
            $this->defineParam($key, $value);
        }
    }

    public function make($name, array $args = []) 
    {
        $existing = $this->container->getByType($name, false);
        if ($existing) {
            return $existing;
        }
        $this->parseArgs($args);
        if ($name[0] === '#') {
            $name = \substr($name, 1);
        }
        return parent::make($name, $args);
    }

    /**
     * Resolve arguments internally.
     *
     * @param array $args
     * @return void
     */
    private function parseArgs(array &$args)
    {
        foreach ($args as $key => $value) {
            if ($key[0] === '@') {  // reference parameter related arguments
                $param = $this->escapeParam($key);
                $className = $this->escapeParam($args[$param]);
                $this->define($className, $value);
            }
            if (\is_string($value)) {
                if ($value[0] === '%') {
                    $value = \trim($value, '%');
                    $path = explode('.', $value);
                    $param = Arrays::get($this->container->getParameters(), $path);
                    $args[$key] = $param;
                    continue;
                } 
                if ($value[0] === '@') {
                    $serviceName = \substr($value, 1);
                    if ($key[0] === ':') {
                        $args[$key] = $this->container->getService($serviceName);
                    }
                }
            }
        }
    }

    public function escapeParam($param)
    {
        if (\is_string($param) && \in_array($param[0], ['#', '@'])) {
            return substr($param, 1);
        }
        return $param;
    }
}
