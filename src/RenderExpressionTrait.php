<?php

namespace Dhii\Expression\Renderer;

use ArrayAccess;
use Dhii\Expression\ExpressionInterface;
use Dhii\Expression\Renderer\ExpressionContextInterface as ExprCtx;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use stdClass;

/**
 * Abstract functionality for expression renderers.
 *
 * Provides the basic functionality for validating the render context, extracting the expression from it and passing
 * it to the actual rendering method.
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

        try {
            $expr = $this->_containerGet($context, ExprCtx::K_EXPRESSION);
            $result = $this->_renderExpression($expr, $context);

            return $result;
        } catch (NotFoundExceptionInterface $notFoundException) {
            throw $this->_createInvalidArgumentException(
                $this->__('Context does not contain an expression'),
                null,
                $notFoundException,
                $context
            );
        }
    }

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
    abstract protected function _renderExpression(ExpressionInterface $expression, $context = null);

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
