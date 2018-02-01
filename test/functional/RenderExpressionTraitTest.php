<?php

namespace Dhii\Expression\Renderer\FuncTest;

use Dhii\Expression\ExpressionInterface;
use Dhii\Expression\Renderer\ExpressionContextInterface;
use Exception;
use InvalidArgumentException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class RenderExpressionTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Expression\Renderer\RenderExpressionTrait';

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
                '__',
                '_renderExpression',
                '_containerGet',
                '_createInvalidArgumentException',
            ]
        );

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                     ->setMethods($methods)
                     ->getMockForTrait();

        $mock->method('__')
             ->willReturnArgument(0);
        $mock->method('_createInvalidArgumentException')->willReturnCallback(
            function($m, $c, $p) {
                return new InvalidArgumentException($m, $c, $p);
            }
        );
        $mock->method('_containerGet')->willReturnCallback(
            function($c, $k) {
                return $c[$k];
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
     * Creates a mock that both extends a class and implements interfaces.
     *
     * This is particularly useful for cases where the mock is based on an
     * internal class, such as in the case with exceptions. Helps to avoid
     * writing hard-coded stubs.
     *
     * @since [*next-version*]
     *
     * @param string   $className      Name of the class for the mock to extend.
     * @param string[] $interfaceNames Names of the interfaces for the mock to implement.
     *
     * @return MockObject The object that extends and implements the specified class and interfaces.
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

        return $this->getMockForAbstractClass($paddingClassName);
    }

    /**
     * Creates a new mock expression instance.
     *
     * @since [*next-version*]
     *
     * @param  string $type  The expression type.
     * @param array   $terms The expression terms.
     *
     * @return ExpressionInterface The created instance.
     */
    public function createExpression($type, $terms = [])
    {
        return $this->mock('Dhii\Expression\ExpressionInterface')
                    ->getType($type)
                    ->getTerms($terms)
                    ->new();
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
     * Tests the render method to assert whether the subject correctly retrieves the expression from the context and
     * invokes the internal abstract render method.
     *
     * @since [*next-version*]
     */
    public function testRender()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $expression = $this->createExpression(uniqid('type-'), []);
        $context = [ExpressionContextInterface::K_EXPRESSION => $expression];
        $expected = uniqid('result-');

        $subject->expects($this->atLeastOnce())
                ->method('_containerGet')
                ->with($context, $this->anything())
                ->willReturn($expression);

        $subject->expects($this->once())
                ->method('_renderExpression')
                ->with($expression, $context)
                ->willReturn($expected);

        $actual = $reflect->_render($context);

        $this->assertEquals($expected, $actual, 'Expected and actual render results are not equal.');
    }

    /**
     * Tests the render method with no context to assert whether the correct exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testRenderNoContext()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $this->setExpectedException('InvalidArgumentException');

        $reflect->_render();
    }

    /**
     * Tests the render method with an invalid context to assert whether the correct exception is thrown.
     *
     * @since [*next-version*]
     */
    public function testRenderInvalidContext()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $expression = $this->createExpression(uniqid('type-'), []);
        $context = [ExpressionContextInterface::K_EXPRESSION => $expression];

        $inner = $this->mockClassAndInterfaces('Exception', ['Psr\Container\NotFoundExceptionInterface']);

        $subject->expects($this->atLeastOnce())
                ->method('_containerGet')
                ->with($context, $this->anything())
                ->willThrowException($inner);

        try {
            $reflect->_render($context);

            $this->fail('Expected an exception to be thrown.');
        } catch (Exception $exception) {
            $this->assertInstanceOf('InvalidArgumentException', $exception, 'Exception is invalid.');
            $this->assertSame($inner, $exception->getPrevious(), 'Inner exception is invalid.');
        }
    }
}
