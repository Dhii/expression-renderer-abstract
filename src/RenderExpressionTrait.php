<?php

namespace Dhii\Expression\Renderer;

use Dhii\Data\Container\Exception\NotFoundExceptionInterface;
use Dhii\Expression\ExpressionInterface;
use Dhii\Expression\Renderer\ExpressionContextInterface as ExprCtx;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Abstract functionality for expression renderers.
 *
 * @since [*next-version*]
 */
trait RenderExpressionTrait
{
    /**
     * Renders the given context as an expression.
     *
     * @since [*next-version*]
     *
     * @param array|ContainerInterface|null $context The context.
     *
     * @throws ContainerExceptionInterface If the context container encountered an error.
     * @throws NotFoundExceptionInterface  If the expression was not found in the context container.
     *
     * @return string|Stringable The rendered result.
     */
    protected function _render($context = null)
    {
        if ($context === null) {
            throw $this->_createInvalidArgumentException(
                $this->__('Cannot render with a null context'),
                null,
                null,
                $context
            );
        }

        $expr   = $this->_containerGet($context, ExprCtx::K_EXPRESSION);
        $result = $this->_renderExpression($expr);

        return $result;
    }

    /**
     * Renders a given expression and its terms.
     *
     * @since [*next-version*]
     *
     * @param ExpressionInterface $expression The expression instance to render.
     *
     * @return string|Stringable The rendered expression.
     */
    abstract protected function _renderExpression(ExpressionInterface $expression);

    /**
     * Retrieves an entry from a container or data set.
     *
     * @since [*next-version*]
     *
     * @param array|ContainerInterface $container The container or array to retrieve from.
     * @param string|Stringable        $key       The key of the value to retrieve.
     *
     * @throws ContainerExceptionInterface If an error occurred while reading from the container.
     * @throws NotFoundExceptionInterface  If the key was not found in the container.
     *
     * @return mixed The value mapped to by the key.
     */
    abstract protected function _containerGet($container, $key);

    /**
     * Creates a new invalid argument exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     * @param mixed|null             $argument The invalid argument, if any.
     *
     * @return InvalidArgumentException The new exception.
     */
    abstract protected function _createInvalidArgumentException(
        $message = null,
        $code = null,
        RootException $previous = null,
        $argument = null
    );

    /**
     * Translates a string, and replaces placeholders.
     *
     * @since [*next-version*]
     * @see   sprintf()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);
}
