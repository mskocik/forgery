<?php declare(strict_types=1);

/**
 * @TestCase
 */

use Tester\Assert;
use Tester\FileMock;
use Tester\TestCase;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Mskocik\ForgeryDI\Forgery;
use Tests\Services\SimpleService;
use Tests\Services\ServiceWithDependency;
use Tests\Services\ServiceWithNonClassDeps;
use Tests\Services\ServiceWithNoTypedParam;
use Mskocik\ForgeryDI\Extensions\DIExtension;
use Tests\Services\ServiceWithTypedParameters;
use Tests\Services\ServiceWithOptionalDependency;
use Tests\Services\ServiceWithRequiredDependency;
use Tests\Services\ServiceRequiringNonClassService;

require_once __DIR__ . '/../bootstrap.php';

class InjectorTest extends TestCase
{
    use Forgery;

    public function setUp(): void
    {
        $loader = new ContainerLoader(TEMP_DIR, true);
        $class = $loader->load(static function (Compiler $compiler): void {
            $compiler->addExtension('forgery', new DIExtension());
            $compiler->loadConfig(FileMock::create('
            parameters:
                myParamInt: 123
                myParamString: demo
                my:
                    deep:
                        param: 1
            services:
                - Tests\Services\SimpleService                
                namedService: Tests\Services\ServiceWithTypedParameters(%myParamInt%, %myParamString%)

            ', 'neon'));
        });
        $this->injectAuryn(new $class());
        /** @var Container $container */
    }

    public function testSimple(): void
    {
        $simpleClass = $this->forge(SimpleService::class);
        Assert::same(SimpleService::class, get_class($simpleClass));
    }

    public function testOptionalDependencyEmpty(): void
    {
        // /** @var ServiceWithOptionalDependency */
        $noDepedency = $this->forge(ServiceWithOptionalDependency::class);

        Assert::null($noDepedency->getChildService());
    }

    public function testOptionalDependencyFilled(): void
    {
        $withDependency = $this->forge(ServiceWithOptionalDependency::class, [
            'child' => SimpleService::class
        ]);

        Assert::same(SimpleService::class, get_class($withDependency->getChildService()));
    }

    public function testPassedTypeParams(): void
    {
        $obj = $this->forge(ServiceWithTypedParameters::class);

        Assert::same(123, $obj->requiredInt);
        Assert::same('demo', $obj->requiredString);
    }

    public function testDefinedService(): void
    {
        $this->defineForgeryParam('customParam', 42);
        $obj = $this->forge(ServiceWithNoTypedParam::class);
        Assert::same(42, $obj->customParam);
        Assert::same(ServiceWithNoTypedParam::class, get_class($obj));
    }

    public function testRequiredDependencies(): void
    {
        $obj = $this->forge(ServiceWithRequiredDependency::class, [
            ':requiredContract' => '@namedService'
        ], \Mskocik\ForgeryDI\Container::INSTANCE_UNIQUE);

        Assert::same(ServiceWithDependency::class, get_class($obj->autoCreated));
        Assert::same(SimpleService::class, get_class($obj->autoCreated->child));
        Assert::same(ServiceWithTypedParameters::class, get_class($obj->requiredContract));
        Assert::null($obj->optionalParam);

        $obj = $this->forge(ServiceWithRequiredDependency::class, [
            'requiredContract' => SimpleService::class,
            ':optionalParam' => '%my.deep.param%'
        ], \Mskocik\ForgeryDI\Container::INSTANCE_UNIQUE);
        Assert::same(SimpleService::class, get_class($obj->requiredContract));
        Assert::same(1, $obj->optionalParam);
    }

    public function testInjectorFeature(): void
    {
        $obj = $this->forge(ServiceRequiringNonClassService::class, [
            '@myDep' => [
                ':requiredInt' => 1,
                ':requiredString' => 'abc'
            ],
            'myDep' => ServiceWithNonClassDeps::class
        ]);
        Assert::same(1, $obj->myDep->requiredInt);
        Assert::same('abc', $obj->myDep->requiredString);
        Assert::same(ServiceRequiringNonClassService::class, get_class($obj));
        Assert::same(ServiceWithNonClassDeps::class, get_class($obj->myDep));
    }

    public function testInjectorFeature2(): void
    {
        $now = time() - 1548;
        $this->defineForgeryDelegate(ServiceWithNonClassDeps::class, function() use ($now) {
            return new ServiceWithNonClassDeps($now, 'abc');
        });
        $obj = $this->forge(ServiceRequiringNonClassService::class);
        Assert::same($now, $obj->myDep->requiredInt);
        Assert::same('abc', $obj->myDep->requiredString);
        Assert::same(ServiceRequiringNonClassService::class, get_class($obj));
        Assert::same(ServiceWithNonClassDeps::class, get_class($obj->myDep));
    }

}

(new InjectorTest)->run();