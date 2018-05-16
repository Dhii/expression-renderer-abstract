<?php

namespace Dhii\Expression\Renderer;

use ArrayAccess;
use Dhii\Expression\ExpressionInterface;
use Dhii\Expression\TermInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\ContainerInterface;
use stdClass;

/**
 * Common functionality for objects that can render expressions and their terms.
 *
 * This trait provides a basic term iteration algorithm that uses an abstracted term render method.
 * When all the expression terms have been rendered and the render results are populated into an array, a second
 * abstract method is used to "compile" those renders into the final result.
 *
 * @since [*next-version*]
 */
trait RenderExpressionAndTermsCapableTrait
{
    /**
     * Renders a given expression and its terms.
     *
     * @since [*next-version*]
     *
     * @param ExpressionInterface                                $expression The expression instance to render.
     * @param array|ArrayAccess|stdClass|ContainerInterface|null $context    The context.
     *
     * @return string|Stringable The rendered expression.
     */
    protected function _renderExpressionAndTerms(ExpressionInterface $expression, $context = null)
    {
        $renderedTerms = [];

        foreach ($expression->getTerms() as $_term) {
            $renderedTerms[] = $this->_renderExpressionTerm($_term, $context);
        }

        return $this->_compileExpressionTerms($expression, $renderedTerms, $context);
    }

    /**
     * Renders an expression's term.
     *
     * @since [*next-version*]
     *
     * @param TermInterface                                      $term    The term to render.
     * @param array|ArrayAccess|stdClass|ContainerInterface|null $context The context.
     *
     * @return string|Stringable The rendered term.
     */
    abstract protected function _renderExpressionTerm(TermInterface $term, $context = null);

    /**
     * Compiles an expression's full render result.
     *
     * @since [*next-version*]
     *
     * @param ExpressionInterface                                $expression    The expression instance.
     * @param string[]|Stringable[]                              $renderedTerms An array of rendered terms.
     * @param array|ArrayAccess|stdClass|ContainerInterface|null $context       The context.
     *
     * @return string|Stringable The rendered expression.
     */
    abstract protected function _compileExpressionTerms(
        ExpressionInterface $expression,
        array $renderedTerms,
        $context = null
    );
}
