<?php

namespace Dhii\Expression\Renderer\FuncTest;

use Dhii\Expression\ExpressionInterface;
use Dhii\Expression\Renderer\DelegateRenderTermCapableTrait as TestSubject;
use Dhii\Expression\Renderer\ExpressionContextInterface;
use Exception;
use OutOfRangeException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class DelegateRenderTermCapableTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Expression\Renderer\DelegateRenderTermCapableTrait';

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
                '_getTermDelegateRenderer',
                '_throwRendererException',
                '__',
            ]
        );

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                     ->setMethods($methods)
                     ->getMockForTrait();

        $mock->method('__')->willReturnArgument(0);
        $mock->method('_throwRendererException')->willReturnCallback(
            function($m, $c, $p) {
                throw new Exception($m, $c, $p);
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
    public function testDelegateRenderTerm()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $expression = $this->createExpression(uniqid('type-'), []);
        $ctx = [ExpressionContextInterface::K_EXPRESSION => $expression];
        $expected = uniqid('result-');
        $renderer = $this->mock('Dhii\Output\TemplateInterface')->render($expected)->new();

        $subject->expects($this->atLeastOnce())
                ->method('_getTermDelegateRenderer')
                ->with($expression, $ctx)
                ->willReturn($renderer);

        $actual = $reflect->_delegateRenderTerm($expression, $ctx);

        $this->assertEquals($expected, $actual, 'Expected and actual render results are not equal.');
    }

    /**
     * Tests the render method to assert whether a "out of range" exceptions are correctly wrapped in renderer
     * exceptions when a delegate renderer could not be found.
     *
     * @since [*next-version*]
     */
    public function testDelegateRenderTermOutOfRangeException()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $expression = $this->createExpression(uniqid('type-'), []);
        $ctx = [ExpressionContextInterface::K_EXPRESSION => $expression];

        $subject->expects($this->atLeastOnce())
                ->method('_getTermDelegateRenderer')
                ->with($expression, $ctx)
                ->willThrowException($oor = new OutOfRangeException());

        try {
            $reflect->_delegateRenderTerm($expression, $ctx);

            $this->fail('Expected an exception - no exception was thrown.');
        } catch (Exception $exception) {
            $this->assertSame($oor, $exception->getPrevious(), 'Inner exception is not the "out-of-range" exception.');
        }
    }
}
