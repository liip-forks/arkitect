<?php

declare(strict_types=1);

namespace Arkitect\Tests\Unit\Expressions\ForClasses;

use Arkitect\Analyzer\ClassDependency;
use Arkitect\Analyzer\ClassDescription;
use Arkitect\Analyzer\FullyQualifiedClassName;
use Arkitect\Expression\ForClasses\DependsOnClassesInNamespace;
use PHPUnit\Framework\TestCase;

class DependsOnClassesInNamespaceTest extends TestCase
{
    public function test_it_should_return_violation_error(): void
    {
        $namespace = 'myNamespace';
        $dependOnClasses = new DependsOnClassesInNamespace($namespace);

        $classDescription = new ClassDescription(
            'full/path',
            FullyQualifiedClassName::fromString('HappyIsland'),
            [],
            []
        );

        $this->assertFalse($dependOnClasses->evaluate($classDescription));
        $this->assertEquals('HappyIsland depends on classes in namespace '.$namespace, $dependOnClasses->describe($classDescription)->toString());
    }

    public function test_it_should_return_true_if_not_depends_on_namespace(): void
    {
        $dependOnClasses = new DependsOnClassesInNamespace('myNamespace');
        $classDescription = new ClassDescription(
            'full/path',
            FullyQualifiedClassName::fromString('HappyIsland'),
            [],
            []
        );

        $this->assertFalse($dependOnClasses->evaluate($classDescription));
    }

    public function test_it_should_return_false_if_depends_on_namespace(): void
    {
        $dependOnClasses = new DependsOnClassesInNamespace('myNamespace');
        $classDescription = new ClassDescription(
            'full/path',
            FullyQualifiedClassName::fromString('HappyIsland'),
            [new ClassDependency('myNamespace', 100)],
            []
        );

        $this->assertTrue($dependOnClasses->evaluate($classDescription));
    }
}