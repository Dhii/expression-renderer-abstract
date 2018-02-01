<?php

namespace Dhii\Expression\Renderer;

use ArrayAccess;
use Dhii\Expression\ExpressionInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\ContainerInterface;
use stdClass;

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
     * @param ExpressionInterface                                $expression    The expression instance.
     * @param string[]|Stringable[]                              $renderedTerms An array of rendered terms.
     * @param array|ArrayAccess|stdClass|ContainerInterface|null $context       The context.
     *
     * @return string|Stringable The rendered expression.
     */
    protected function _compileExpressionTerms(ExpressionInterface $expression, array $renderedTerms, $context = null)
    {
        $glue = $this->_getCompileExpressionTermsGlue($expression, $renderedTerms, $context);
        $result = implode($glue, $renderedTerms);

        return $result;
    }

    /**
     * Retrieves the implosion glue to use for compiling an expression's full render result.
     *
     * @since [*next-version*]
     *
     * @param ExpressionInterface                                $expression    The expression instance.
     * @param string[]|Stringable[]                              $renderedTerms TAn array of rendered terms.
     * @param array|ArrayAccess|stdClass|ContainerInterface|null $context       The context.
     *
     * @return string|Stringable The implosion glue string.
     */
    abstract protected function _getCompileExpressionTermsGlue(
        ExpressionInterface $expression,
        array $renderedTerms,
        $context = null
    );
}
