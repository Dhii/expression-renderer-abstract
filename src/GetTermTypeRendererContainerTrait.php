<?php

namespace Dhii\Expression\Renderer;

use ArrayAccess;
use Dhii\Output\TemplateInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use stdClass;

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
     * @param string|Stringable                                  $termType The term type for which to retrieve a renderer.
     * @param array|ArrayAccess|stdClass|ContainerInterface|null $context  The context.
     *
     * @return TemplateInterface The renderer instance.
     *
     * @throws ContainerExceptionInterface If an error occurred while reading from the container.
     * @throws NotFoundExceptionInterface If no renderer was found for the given term type.
     */
    protected function _getTermTypeRenderer($termType, $context = null)
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
