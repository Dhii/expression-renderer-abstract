<?php

namespace Dhii\Expression\Renderer\FuncTest;

use Dhii\Expression\ExpressionInterface;
use Dhii\Expression\Renderer\CompileExpressionTermsImplodeTrait as TestSubject;
use Dhii\Expression\Renderer\ExpressionContextInterface;
use Xpmock\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class CompileExpressionTermsImplodeTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Expression\Renderer\CompileExpressionTermsImplodeTrait';

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
                '_getCompileExpressionTermsGlue',
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
     * @param string $type  The expression type.
     * @param array  $terms The expression terms.
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
     * Tests the expression terms compile method to assert whether the result contains the rendered terms, joined by a
     * specific glue.
     *
     * @since [*next-version*]
     */
    public function testCompileExpressionTerms()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $expression = $this->createExpression(uniqid('type-'));
        $context = [ExpressionContextInterface::K_EXPRESSION => $expression];
        $rTerms = [
            $r1 = uniqid('rendered-term-'),
            $r2 = uniqid('rendered-term-'),
            $r3 = uniqid('rendered-term-'),
        ];
        $glue = '::';
        $expected = "$r1::$r2::$r3";

        $subject->expects($this->atLeastOnce())
                ->method('_getCompileExpressionTermsGlue')
                ->with($expression, $rTerms, $context)
                ->willReturn($glue);

        $actual = $reflect->_compileExpressionTerms($expression, $rTerms, $context);

        $this->assertEquals($expected, $actual, 'Expected and retrieved results do not match');
    }
}
