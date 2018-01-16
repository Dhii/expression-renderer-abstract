<?php

namespace Dhii\Expression\Renderer;

use Dhii\Output\Exception\RendererExceptionInterface;
use Dhii\Output\TemplateInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Common functionality for objects that can provide a term type renderer via a container.
 *
 * @since [*next-version*]
 */
trait GetTermTypeRendererContainerTrait
{
    /**
     * Retrieves the renderer for a given term.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $termType The term type for which to retrieve a renderer.
     *
     * @return TemplateInterface The renderer instance.
     *
     * @throws ContainerExceptionInterface If an error occurred while reading from the container.
     * @throws NotFoundExceptionInterface If no renderer was found for the given term type.
     */
    protected function _getTermTypeRenderer($termType)
    {
         return $this->_getTermTypeRendererContainer()->get($termType);
    }

    /**
     * Retrieves the container that contains the renderers, keyed by term type.
     *
     * @since [*next-version*]
     *
     * @return ContainerInterface The container that contains the renderer instances, keyed by term type.
     */
    abstract protected function _getTermTypeRendererContainer();

    /**
     * Creates a new invalid argument exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     *
     * @return RendererExceptionInterface The new exception.
     */
    abstract protected function _createRendererException(
        $message = null,
        $code = null,
        RootException $previous = null
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
