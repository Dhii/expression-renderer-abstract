<?php

namespace Dhii\Expression\Renderer;

use Dhii\Expression\ExpressionInterface;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * Common functionality for objects that can compile rendered expression terms by imploding them with a glue.
 *
 * @since [*next-version*]
 */
trait CompileExpressionTermsImplodeTrait
{
    /**
     * Compiles an expression's full render result.
     *
     * @since [*next-version*]
     *
     * @param ExpressionInterface   $expression    The expression instance.
     * @param string[]|Stringable[] $renderedTerms An array of rendered terms.
     *
     * @return string|Stringable The rendered expression.
     */
    protected function _compileExpressionTerms(ExpressionInterface $expression, array $renderedTerms)
    {
        $glue = $this->_getCompileExpressionTermsGlue($expression, $renderedTerms);
        $result = implode($glue, $renderedTerms);

        return $result;
    }

    /**
     * Retrieves the implosion glue to use for compiling an expression's full render result.
     *
     * @since [*next-version*]
     *
     * @param ExpressionInterface   $expression    The expression instance.
     * @param string[]|Stringable[] $renderedTerms TAn array of rendered terms.
     *
     * @return string|Stringable The implosion glue string.
     */
    abstract protected function _getCompileExpressionTermsGlue(ExpressionInterface $expression, array $renderedTerms);
}
