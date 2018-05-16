<?php

namespace Dhii\Expression\Renderer\FuncTest;

use Dhii\Expression\ExpressionInterface;
use Dhii\Expression\Renderer\ExpressionContextInterface;
use Dhii\Expression\Renderer\RenderExpressionAndTermsCapableTrait as TestSubject;
use InvalidArgumentException;
use Xpmock\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class RenderExpressionAndTermsCapableTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Expression\Renderer\RenderExpressionAndTermsCapableTrait';

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
                '_renderExpressionTerm',
                '_compileExpressionTerms',
            ]
        );

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                     ->setMethods($methods)
                     ->getMockForTrait();

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
     * Tests the render method to assert whether the subject correctly renders the expression terms using the abstract
     * method and compiles those results into a final render.
     *
     * @since [*next-version*]
     */
    public function testRenderExpressionAndTerms()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $expression = $this->createExpression(
            uniqid('type-'),
            [
                $child1 = $this->createExpression(uniqid('child-')),
                $child2 = $this->createExpression(uniqid('child-')),
                $child3 = $this->createExpression(uniqid('child-')),
            ]
        );
        $ctx = ['expression' => $expression];

        $renders = [
            $render1 = uniqid('rendered-'),
            $render2 = uniqid('rendered-'),
            $render3 = uniqid('rendered-'),
        ];
        $expected = uniqid('result-');

        $subject->expects($this->exactly(3))
                ->method('_renderExpressionTerm')
                ->withConsecutive([$child1, $ctx], [$child2, $ctx], [$child3, $ctx])
                ->willReturnOnConsecutiveCalls($render1, $render2, $render3);
        $subject->expects($this->atLeastOnce())
                ->method('_compileExpressionTerms')
                ->with($expression, $renders, $ctx)
                ->willReturn($expected);

        $actual = $reflect->_renderExpressionAndTerms($expression, $ctx);

        $this->assertEquals($expected, $actual, 'Expected and actual render results are not equal.');
    }
}
