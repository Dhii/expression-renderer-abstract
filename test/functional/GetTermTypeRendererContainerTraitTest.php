<?php

namespace Dhii\Expression\Renderer\FuncTest;

use Dhii\Expression\Renderer\GetTermTypeRendererContainerTrait as TestSubject;
use Exception;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use stdClass;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class GetTermTypeRendererContainerTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Expression\Renderer\GetTermTypeRendererContainerTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods The methods to mock.
     *
     * @return MockObject The new instance.
     */
    public function createInstance($methods = [])
    {
        $methods = $this->mergeValues(
            $methods,
            [
                '_getTermTypeRendererContainer',
                '_createRendererException',
                '__',
            ]
        );

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                     ->setMethods($methods)
                     ->getMockForTrait();

        $mock->method('__')->willReturnArgument(0);
        $mock->method('_createRendererException')->willReturnCallback(
            function($m, $c, $p) {
                return new Exception($m, $c, $p);
            }
        );

        return $mock;
    }

    /**
     * Merges the values of two arrays.
     *
     * The resulting product will be a numeric array where the values of both inputs are present, without duplicates.
     *
     * @since [*next-version*]
     *
     * @param array $destination The base array.
     * @param array $source      The array with more keys.
     *
     * @return array The array which contains unique values
     */
    public function mergeValues($destination, $source)
    {
        return array_keys(array_merge(array_flip($destination), array_flip($source)));
    }

    /**
     * Creates a new mock container instance.
     *
     * @since [*next-version*]
     *
     * @return MockObject The created instance.
     */
    public function createContainer()
    {
        return $this->getMockBuilder('Psr\Container\ContainerInterface')
                    ->setMethods(['get', 'has'])
                    ->getMockForAbstractClass();
    }

    /**
     * Creates a new mock not found exception instance.
     *
     * @since [*next-version*]
     *
     * @return MockObject The created instance.
     */
    public function createNotFoundException()
    {
        return $this->mockClassAndInterfaces('Exception', ['Psr\Container\NotFoundExceptionInterface'])
                    ->getMockForAbstractClass();
    }

    /**
     * Creates a new mock container exception instance.
     *
     * @since [*next-version*]
     *
     * @return MockObject The created instance.
     */
    public function createContainerException()
    {
        return $this->mockClassAndInterfaces('Exception', ['Psr\Container\ContainerExceptionInterface'])
                    ->getMockForAbstractClass();
    }

    /**
     * Creates a mock that both extends a class and implements interfaces.
     *
     * This is particularly useful for cases where the mock is based on an
     * internal class, such as in the case with exceptions. Helps to avoid
     * writing hard-coded stubs.
     *
     * @since [*next-version*]
     *
     * @param string $className      Name of the class for the mock to extend.
     * @param array  $interfaceNames Names of the interfaces for the mock to implement.
     *
     * @return MockBuilder The object that extends and implements the specified class and interfaces.
     */
    public function mockClassAndInterfaces($className, $interfaceNames = [])
    {
        $paddingClassName = uniqid($className);
        $definition = vsprintf(
            'abstract class %1$s extends %2$s implements %3$s {}',
            [
                $paddingClassName,
                $className,
                implode(', ', $interfaceNames),
            ]
        );
        eval($definition);

        return $this->getMockBuilder($paddingClassName);
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType(
            'object',
            $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests the term type renderer method to assert whether it internally asks the container for the renderer
     * instance and return the renderer retrieved from it.
     *
     * @since [*next-version*]
     */
    public function testGetTermTypeRenderer()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $type = uniqid('type-');
        $renderer = new stdClass();
        $container = $this->createContainer();

        $container->method('get')
                  ->with($type)
                  ->willReturn($renderer);

        $subject->expects($this->atLeastOnce())
                ->method('_getTermTypeRendererContainer')
                ->willReturn($container);

        $result = $reflect->_getTermTypeRenderer($type);

        $this->assertSame($renderer, $result, 'Expected and retrieved renderer instances are not the same.');
    }

    /**
     * Tests the term type renderer getter method to assert whether any not found exceptions thrown by the
     * container are wrapped in renderer exceptions and thrown.
     *
     * @since [*next-version*]
     */
    public function testGetTermTypeRendererNotFoundException()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $type = uniqid('type-');
        $container = $this->createContainer();

        $container->method('get')
                  ->with($type)
                  ->willThrowException($nfe = $this->createNotFoundException());

        $subject->expects($this->atLeastOnce())
                ->method('_getTermTypeRendererContainer')
                ->willReturn($container);

        try {
            $reflect->_getTermTypeRenderer($type);

            $this->fail('Expected an exception - no exception was thrown.');
        } catch (Exception $exception) {
            $this->assertSame($nfe, $exception->getPrevious(), 'Inner exception is not the not found exception.');
        }
    }

    /**
     * Tests the term type renderer getter method to assert whether any container exceptions thrown by the
     * container are wrapped in renderer exceptions and thrown.
     *
     * @since [*next-version*]
     */
    public function testGetTermTypeRendererContainerException()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $type = uniqid('type-');
        $container = $this->createContainer();

        $container->method('get')
                  ->with($type)
                  ->willThrowException($nfe = $this->createContainerException());

        $subject->expects($this->atLeastOnce())
                ->method('_getTermTypeRendererContainer')
                ->willReturn($container);

        try {
            $reflect->_getTermTypeRenderer($type);

            $this->fail('Expected an exception - no exception was thrown.');
        } catch (Exception $exception) {
            $this->assertSame($nfe, $exception->getPrevious(), 'Inner exception is not the "not-found" exception.');
        }
    }
}
